<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng nhập | VéNow</title>
  <link rel="stylesheet" href="../../css/theme.css">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="../../css/auth.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
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
          <h1>Đăng nhập</h1>
          <p>Chào mừng bạn quay trở lại với VéNow</p>
        </div>
        
        <?php
        // Hiển thị thông báo lỗi nếu có
        session_start();
        if (isset($_SESSION['login_errors']) && !empty($_SESSION['login_errors'])) {
            echo '<div class="alert alert-danger">';
            foreach ($_SESSION['login_errors'] as $error) {
                echo '<p>' . $error . '</p>';
            }
            echo '</div>';
            // Xóa thông báo lỗi sau khi hiển thị
            unset($_SESSION['login_errors']);
        }
        ?>
        
        <div class="social-login">
          <button class="social-btn facebook-btn">
            <i class="fab fa-facebook-f"></i>
            <span>Đăng nhập với Facebook</span>
          </button>
          <button class="social-btn google-btn">
            <i class="fab fa-google"></i>
            <span>Đăng nhập với Google</span>
          </button>
        </div>
        
        <div class="divider">
          <span>hoặc đăng nhập với email</span>
        </div>
        
        <form class="auth-form" action="xu-ly-dang-nhap.php" method="POST">
          <div class="form-group">
            <label for="email">Email hoặc số điện thoại</label>
            <div class="input-with-icon">
              <input type="text" id="email" name="email" placeholder="Nhập email hoặc số điện thoại" 
                value="<?php echo isset($_SESSION['login_email']) ? $_SESSION['login_email'] : ''; ?>">
              <i class="far fa-envelope"></i>
            </div>
            <div class="form-error" id="email-error"></div>
          </div>
          
          <div class="form-group">
            <label for="password">Mật khẩu</label>
            <div class="input-with-icon">
              <input type="password" id="password" name="password" placeholder="Nhập mật khẩu">
              <i class="far fa-eye-slash toggle-password"></i>
            </div>
            <div class="form-error" id="password-error"></div>
          </div>
          
          <div class="form-options">
            <div class="remember-me">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">Ghi nhớ đăng nhập</label>
            </div>
            <a href="quen-mat-khau.php" class="forgot-password">Quên mật khẩu?</a>
          </div>
          
          <button type="submit" class="btn-auth">Đăng nhập</button>
        </form>
        
        <div class="auth-footer">
          <p>Chưa có tài khoản? <a href="../dangky/register.php" class="create-account">Tạo tài khoản ngay</a></p>
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
      
      if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
          const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
          passwordInput.setAttribute('type', type);
          
          // Toggle eye icon
          this.classList.toggle('fa-eye');
          this.classList.toggle('fa-eye-slash');
        });
      }
      
      // Form validation
      const authForm = document.querySelector('.auth-form');
      const emailInput = document.getElementById('email');
      const emailError = document.getElementById('email-error');
      const passwordError = document.getElementById('password-error');
      
      if (authForm) {
        authForm.addEventListener('submit', function(e) {
          let isValid = true;
          
          // Validate email/phone
          if (!emailInput.value.trim()) {
            emailError.textContent = 'Vui lòng nhập email hoặc số điện thoại';
            isValid = false;
          } else {
            emailError.textContent = '';
          }
          
          // Validate password
          if (!passwordInput.value.trim()) {
            passwordError.textContent = 'Vui lòng nhập mật khẩu';
            isValid = false;
          } else {
            passwordError.textContent = '';
          }
          
          if (!isValid) {
            e.preventDefault();
          }
        });
      }
    });
  </script>
</body>
</html>
