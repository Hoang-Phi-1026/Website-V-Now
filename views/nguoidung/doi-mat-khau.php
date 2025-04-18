<?php
session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    // Lưu trang hiện tại để chuyển hướng sau khi đăng nhập
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    
    // Chuyển hướng đến trang đăng nhập
    header("Location: /venow/views/dangnhap/login.php");
    exit();
}

include_once("../../layout/header.php");
?>

<link rel="stylesheet" href="../../css/auth.css">
<link rel="stylesheet" href="../../css/profile.css">

<div class="container profile-container">
    <div class="profile-sidebar">
        <div class="profile-menu">
            <h3>Tài khoản của tôi</h3>
            <ul>
                <li><a href="thong-tin-ca-nhan.php">Thông tin cá nhân</a></li>
                <li><a href="doi-mat-khau.php" class="active">Đổi mật khẩu</a></li>
                <li><a href="lich-su-dat-ve.php">Lịch sử đặt vé</a></li>
                <li><a href="su-kien-yeu-thich.php">Sự kiện yêu thích</a></li>
                <li><a href="../dangnhap/dang-xuat.php">Đăng xuất</a></li>
            </ul>
        </div>
    </div>
    
    <div class="profile-content">
        <div class="profile-section">
            <h2>Đổi mật khẩu</h2>
            
            <?php
            // Hiển thị thông báo nếu có
            if (isset($_SESSION['password_message'])) {
                $messageType = isset($_SESSION['password_success']) && $_SESSION['password_success'] ? 'success' : 'danger';
                echo '<div class="alert alert-' . $messageType . '">';
                echo '<p>' . $_SESSION['password_message'] . '</p>';
                echo '</div>';
                
                // Xóa thông báo sau khi hiển thị
                unset($_SESSION['password_message']);
                unset($_SESSION['password_success']);
            }
            ?>
            
            <form action="xu-ly-doi-mat-khau.php" method="POST" class="profile-form">
                <div class="form-group">
                    <label for="current_password">Mật khẩu hiện tại</label>
                    <div class="input-with-icon">
                        <input type="password" id="current_password" name="current_password" required>
                        <i class="far fa-eye-slash toggle-password" data-target="current_password"></i>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="new_password">Mật khẩu mới</label>
                    <div class="input-with-icon">
                        <input type="password" id="new_password" name="new_password" required>
                        <i class="far fa-eye-slash toggle-password" data-target="new_password"></i>
                    </div>
                    
                    <div class="password-strength">
                        <div class="strength-meter">
                            <div class="strength-bar" id="strength-bar"></div>
                        </div>
                        <div class="strength-text" id="strength-text">Độ mạnh: Chưa nhập</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Xác nhận mật khẩu mới</label>
                    <div class="input-with-icon">
                        <input type="password" id="confirm_password" name="confirm_password" required>
                        <i class="far fa-eye-slash toggle-password" data-target="confirm_password"></i>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary">Cập nhật mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .alert p {
        margin: 5px 0;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle password visibility
        const toggleButtons = document.querySelectorAll('.toggle-password');
        
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Toggle eye icon
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        });
        
        // Password strength meter
        const newPasswordInput = document.getElementById('new_password');
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        
        if (newPasswordInput && strengthBar && strengthText) {
            newPasswordInput.addEventListener('input', function() {
                const password = this.value;
                const strength = checkPasswordStrength(password);
                
                // Update strength bar
                strengthBar.style.width = strength.percent + '%';
                strengthBar.style.backgroundColor = strength.color;
                
                // Update strength text
                strengthText.textContent = 'Độ mạnh: ' + strength.text;
            });
        }
        
        // Form validation
        const form = document.querySelector('.profile-form');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                const currentPassword = document.getElementById('current_password').value;
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('confirm_password').value;
                
                let isValid = true;
                
                // Validate current password
                if (!currentPassword) {
                    isValid = false;
                    alert('Vui lòng nhập mật khẩu hiện tại');
                }
                
                // Validate new password
                if (!newPassword) {
                    isValid = false;
                    alert('Vui lòng nhập mật khẩu mới');
                } else if (newPassword.length < 8) {
                    isValid = false;
                    alert('Mật khẩu mới phải có ít nhất 8 ký tự');
                }
                
                // Validate confirm password
                if (!confirmPassword) {
                    isValid = false;
                    alert('Vui lòng xác nhận mật khẩu mới');
                } else if (newPassword !== confirmPassword) {
                    isValid = false;
                    alert('Mật khẩu xác nhận không khớp');
                }
                
                if (!isValid) {
                    e.preventDefault();
                }
            });
        }
        
        // Check password strength
        function checkPasswordStrength(password) {
            if (!password) {
                return { percent: 0, color: '#e0e0e0', text: 'Chưa nhập' };
            }
            
            let strength = 0;
            
            // Length check
            if (password.length >= 8) strength += 1;
            if (password.length >= 12) strength += 1;
            
            // Character type checks
            if (/[A-Z]/.test(password)) strength += 1;
            if (/[a-z]/.test(password)) strength += 1;
            if (/[0-9]/.test(password)) strength += 1;
            if (/[^A-Za-z0-9]/.test(password)) strength += 1;
            
            // Calculate percentage
            const percent = Math.min(100, (strength / 6) * 100);
            
            // Determine color and text
            let color, text;
            
            if (strength < 2) {
                color = '#f44336';
                text = 'Yếu';
            } else if (strength < 4) {
                color = '#ff9800';
                text = 'Trung bình';
            } else if (strength < 6) {
                color = '#4caf50';
                text = 'Mạnh';
            } else {
                color = '#2e7d32';
                text = 'Rất mạnh';
            }
            
            return { percent, color, text };
        }
    });
</script>

<?php
include_once("../../layout/footer.php");
?>
