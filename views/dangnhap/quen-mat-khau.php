<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quên mật khẩu | VéNow</title>
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
          <h1>Quên mật khẩu</h1>
          <p>Nhập email của bạn để đặt lại mật khẩu</p>
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
        
        <form class="auth-form" action="xu-ly-quen-mat-khau.php" method="POST">
          <div class="form-group">
            <label for="email">Email</label>
            <div class="input-with-icon">
              <input type="email" id="email" name="email" placeholder="Nhập email đã đăng ký" required>
              <i class="far fa-envelope"></i>
            </div>
            <div class="form-error" id="email-error"></div>
          </div>
          
          <button type="submit" class="btn-auth">Gửi yêu cầu đặt lại mật khẩu</button>
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
      // Form validation
      const authForm = document.querySelector('.auth-form');
      const emailInput = document.getElementById('email');
      const emailError = document.getElementById('email-error');
      
      if (authForm) {
        authForm.addEventListener('submit', function(e) {
          let isValid = true;
          
          // Validate email
          if (!emailInput.value.trim()) {
            emailError.textContent = 'Vui lòng nhập email';
            isValid = false;
          } else if (!isValidEmail(emailInput.value.trim())) {
            emailError.textContent = 'Email không hợp lệ';
            isValid = false;
          } else {
            emailError.textContent = '';
          }
          
          if (!isValid) {
            e.preventDefault();
          }
        });
      }
      
      // Validate email format
      function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
      }
    });
  </script>
</body>
</html>
