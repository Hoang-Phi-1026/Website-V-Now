<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đặt lại mật khẩu | VéNow</title>
  <link rel="stylesheet" href="../../css/theme.css">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="../../css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <?php
  // Kiểm tra tham số URL
  $email = isset($_GET['email']) ? $_GET['email'] : '';
  $code = isset($_GET['code']) ? $_GET['code'] : '';
  
  if (empty($email) || empty($code)) {
    echo '<div class="auth-container">
            <div class="auth-form-container" style="max-width: 500px; margin: 0 auto; padding: 40px;">
              <div class="auth-header">
                <h1>Liên kết không hợp lệ</h1>
                <p>Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.</p>
              </div>
              <div class="auth-footer">
                <p><a href="quen-mat-khau.php" class="btn-auth" style="display: inline-block; margin-top: 20px;">Yêu cầu đặt lại mật khẩu mới</a></p>
              </div>
            </div>
          </div>';
    exit();
  }
  ?>
  
  <div class="auth-container">
    <div class="auth-left">
      <div class="auth-logo">
        <a href="/venow/index.php">
          <img src="../../images/logo.png" alt="VéNow Logo">
        </a>
      </div>
      <div class="auth-image">
        <img src="../../images/auth-image.jpg" alt="Sự kiện VéNow">
        <div class="auth-image-overlay">
          <h2>Khám phá và đặt vé cho các sự kiện hấp dẫn</h2>
          <p>Tham gia cùng hàng triệu người dùng VéNow để không bỏ lỡ những trải nghiệm tuyệt vời</p>
        </div>
      </div>
    </div>
    <div class="auth-right">
      <div class="auth-form-container">
        <div class="theme-toggle" id="theme-toggle" title="Chuyển đổi chế độ" style="position: absolute; top: 20px; right: 20px;">
          <i class="fas fa-moon"></i>
        </div>
        <div class="auth-header">
          <h1>Đặt lại mật khẩu</h1>
          <p>Tạo mật khẩu mới cho tài khoản của bạn</p>
        </div>
        
        <?php
        // Hiển thị thông báo nếu có
        session_start();
        if (isset($_SESSION['reset_message'])) {
            $messageType = isset($_SESSION['reset_success']) && $_SESSION['reset_success'] ? 'success' : 'danger';
            echo '<div class="alert alert-' . $messageType . '">';
            echo '<p>' . $_SESSION['reset_message'] . '</p>';
            echo '</div>';
            
            // Xóa thông báo sau khi hiển thị
            unset($_SESSION['reset_message']);
            unset($_SESSION['reset_success']);
        }
        ?>
        
        <form class="auth-form" action="xu-ly-dat-lai-mat-khau.php" method="POST">
          <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
          <input type="hidden" name="code" value="<?php echo htmlspecialchars($code); ?>">
          
          <div class="form-group">
            <label for="password">Mật khẩu mới</label>
            <div class="input-with-icon">
              <input type="password" id="password" name="password" placeholder="Nhập mật khẩu mới" required>
              <i class="far fa-eye-slash toggle-password"></i>
            </div>
            <div class="form-error" id="password-error"></div>
            
            <div class="password-strength">
              <div class="strength-meter">
                <div class="strength-bar" id="strength-bar"></div>
              </div>
              <div class="strength-text" id="strength-text">Độ mạnh: Chưa nhập</div>
            </div>
          </div>
          
          <div class="form-group">
            <label for="confirm_password">Xác nhận mật khẩu</label>
            <div class="input-with-icon">
              <input type="password" id="confirm_password" name="confirm_password" placeholder="Nhập lại mật khẩu mới" required>
              <i class="far fa-eye-slash toggle-confirm-password"></i>
            </div>
            <div class="form-error" id="confirm-password-error"></div>
          </div>
          
          <button type="submit" class="btn-auth">Đặt lại mật khẩu</button>
        </form>
        
        <div class="auth-footer">
          <p>Đã nhớ mật khẩu? <a href="login.php" class="login-link">Đăng nhập ngay</a></p>
        </div>
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

  <script src="../../js/theme.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Toggle password visibility
      const togglePassword = document.querySelector('.toggle-password');
      const passwordInput = document.getElementById('password');
      const toggleConfirmPassword = document.querySelector('.toggle-confirm-password');
      const confirmPasswordInput = document.getElementById('confirm_password');
      
      if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);
          
          // Toggle eye icon
          this.classList.toggle('fa-eye');
          this.classList.toggle('fa-eye-slash');
        });
      }
      
      if (toggleConfirmPassword && confirmPasswordInput) {
        toggleConfirmPassword.addEventListener('click', function() {
          const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          confirmPasswordInput.setAttribute('type', type);
          
          // Toggle eye icon
          this.classList.toggle('fa-eye');
          this.classList.toggle('fa-eye-slash');
        });
      }
      
      // Password strength meter
      const strengthBar = document.getElementById('strength-bar');
      const strengthText = document.getElementById('strength-text');
      
      if (passwordInput && strengthBar && strengthText) {
        passwordInput.addEventListener('input', function() {
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
      const authForm = document.querySelector('.auth-form');
      const passwordError = document.getElementById('password-error');
      const confirmPasswordError = document.getElementById('confirm-password-error');
      
      if (authForm) {
        authForm.addEventListener('submit', function(e) {
          let isValid = true;
          
          // Validate password
          if (!passwordInput.value.trim()) {
            passwordError.textContent = 'Vui lòng nhập mật khẩu mới';
            isValid = false;
          } else if (passwordInput.value.length < 8) {
            passwordError.textContent = 'Mật khẩu phải có ít nhất 8 ký tự';
            isValid = false;
          } else {
            passwordError.textContent = '';
          }
          
          // Validate confirm password
          if (!confirmPasswordInput.value.trim()) {
            confirmPasswordError.textContent = 'Vui lòng xác nhận mật khẩu';
            isValid = false;
          } else if (confirmPasswordInput.value !== passwordInput.value) {
            confirmPasswordError.textContent = 'Mật khẩu xác nhận không khớp';
            isValid = false;
          } else {
            confirmPasswordError.textContent = '';
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
</body>
</html>
