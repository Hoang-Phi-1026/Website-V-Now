<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Đăng ký | VéNow</title>
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
          <img src="images/logo.png"alt="VéNow Logo">
        </a>
      </div>
      <div class="auth-image">
        <img src="images/auth-image2.jpg" alt="Sự kiện VéNow">
        <div class="auth-image-overlay">
          <h2>Tham gia cộng đồng yêu thích sự kiện</h2>
          <p>Đăng ký để nhận thông báo về các sự kiện mới và ưu đãi đặc biệt</p>
        </div>
      </div>
    </div>
    <div class="auth-right">
      <div class="auth-form-container">
        <div class="theme-toggle" id="theme-toggle" title="Chuyển đổi chế độ" style="position: absolute; top: 20px; right: 20px;">
          <i class="fas fa-moon"></i>
        </div>
        <div class="auth-header">
          <h1>Đăng ký tài khoản</h1>
          <p>Tạo tài khoản để trải nghiệm đầy đủ tính năng của VéNow</p>
        </div>
        
        <div class="social-login">
          <button class="social-btn facebook-btn">
            <i class="fab fa-facebook-f"></i>
            <span>Đăng ký với Facebook</span>
          </button>
          <button class="social-btn google-btn">
            <i class="fab fa-google"></i>
            <span>Đăng ký với Google</span>
          </button>
        </div>
        
        <div class="divider">
          <span>hoặc đăng ký với email</span>
        </div>
        
        <form class="auth-form" action="../dangnhap/login.php">
          <div class="form-row">
            <div class="form-group half">
              <label for="first-name">Họ</label>
              <div class="input-with-icon">
                <input type="text" id="first-name" name="first_name" placeholder="Nhập họ">
                <i class="far fa-user"></i>
              </div>
              <div class="form-error" id="first-name-error"></div>
            </div>
            <div class="form-group half">
              <label for="last-name">Tên</label>
              <div class="input-with-icon">
                <input type="text" id="last-name" name="last_name" placeholder="Nhập tên">
                <i class="far fa-user"></i>
              </div>
              <div class="form-error" id="last-name-error"></div>
            </div>
          </div>
          
          <div class="form-group">
            <label for="email">Email</label>
            <div class="input-with-icon">
              <input type="email" id="email" name="email" placeholder="Nhập email">
              <i class="far fa-envelope"></i>
            </div>
            <div class="form-error" id="email-error"></div>
          </div>
          
          <div class="form-group">
            <label for="phone">Số điện thoại</label>
            <div class="input-with-icon">
              <input type="tel" id="phone" name="phone" placeholder="Nhập số điện thoại">
              <i class="fas fa-phone"></i>
            </div>
            <div class="form-error" id="phone-error"></div>
          </div>
          
          <div class="form-group">
            <label for="password">Mật khẩu</label>
            <div class="input-with-icon">
              <input type="password" id="password" name="password" placeholder="Nhập mật khẩu">
              <i class="far fa-eye-slash toggle-password"></i>
            </div>
            <div class="password-strength">
              <div class="strength-meter">
                <div class="strength-bar" id="strength-bar"></div>
              </div>
              <div class="strength-text" id="strength-text">Vui lòng nhập mật khẩu</div>
            </div>
            <div class="form-error" id="password-error"></div>
          </div>
          
          <div class="form-group terms">
            <input type="checkbox" id="terms" name="terms">
            <label for="terms">Tôi đồng ý với <a href="#">Điều khoản sử dụng</a> và <a href="#">Chính sách bảo mật</a> của VéNow</label>
          </div>
          
          <button type="submit" class="btn-auth">Đăng ký</button>
        </form>
        
        <div class="auth-footer">
          <p>Đã có tài khoản? <a href="../dangnhap/login.php" class="login-link">Đăng nhập</a></p>
        </div>
      </div>
    </div>
  </div>

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
      
      // Password strength meter
      if (passwordInput) {
        const strengthBar = document.getElementById('strength-bar');
        const strengthText = document.getElementById('strength-text');
        
        passwordInput.addEventListener('input', function() {
          const value = this.value;
          let strength = 0;
          let message = '';
          
          if (value.length > 0) {
            // Kiểm tra độ dài
            if (value.length >= 8) {
              strength += 25;
            }
            
            // Kiểm tra chữ thường
            if (value.match(/[a-z]+/)) {
              strength += 25;
            }
            
            // Kiểm tra chữ hoa
            if (value.match(/[A-Z]+/)) {
              strength += 25;
            }
            
            // Kiểm tra số và ký tự đặc biệt
            if (value.match(/[0-9]/) || value.match(/[^a-zA-Z0-9]/)) {
              strength += 25;
            }
            
            // Hiển thị thông báo
            if (strength <= 25) {
              message = 'Mật khẩu yếu';
              strengthBar.style.backgroundColor = '#f44336';
            } else if (strength <= 50) {
              message = 'Mật khẩu trung bình';
              strengthBar.style.backgroundColor = '#ff9800';
            } else if (strength <= 75) {
              message = 'Mật khẩu khá mạnh';
              strengthBar.style.backgroundColor = '#4caf50';
            } else {
              message = 'Mật khẩu mạnh';
              strengthBar.style.backgroundColor = '#2e7d32';
            }
          } else {
            message = 'Vui lòng nhập mật khẩu';
          }
          
          strengthBar.style.width = strength + '%';
          strengthText.textContent = message;
        });
      }
      
      // Form validation
      const authForm = document.querySelector('.auth-form');
      const firstNameInput = document.getElementById('first-name');
      const lastNameInput = document.getElementById('last-name');
      const emailInput = document.getElementById('email');
      const phoneInput = document.getElementById('phone');
      const termsCheckbox = document.getElementById('terms');
      
      const firstNameError = document.getElementById('first-name-error');
      const lastNameError = document.getElementById('last-name-error');
      const emailError = document.getElementById('email-error');
      const phoneError = document.getElementById('phone-error');
      const passwordError = document.getElementById('password-error');
      
      if (authForm) {
        authForm.addEventListener('submit', function(e) {
          let isValid = true;
          
          // Validate first name
          if (!firstNameInput.value.trim()) {
            firstNameError.textContent = 'Vui lòng nhập họ của bạn';
            isValid = false;
          } else {
            firstNameError.textContent = '';
          }
          
          // Validate last name
          if (!lastNameInput.value.trim()) {
            lastNameError.textContent = 'Vui lòng nhập tên của bạn';
            isValid = false;
          } else {
            lastNameError.textContent = '';
          }
          
          // Validate email
          if (!emailInput.value.trim()) {
            emailError.textContent = 'Vui lòng nhập email';
            isValid = false;
          } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
            emailError.textContent = 'Email không hợp lệ';
            isValid = false;
          } else {
            emailError.textContent = '';
          }
          
          // Validate phone
          if (!phoneInput.value.trim()) {
            phoneError.textContent = 'Vui lòng nhập số điện thoại';
            isValid = false;
          } else if (!/^[0-9]{10,11}$/.test(phoneInput.value.replace(/\s/g, ''))) {
            phoneError.textContent = 'Số điện thoại không hợp lệ';
            isValid = false;
          } else {
            phoneError.textContent = '';
          }
          
          // Validate password
          if (!passwordInput.value.trim()) {
            passwordError.textContent = 'Vui lòng nhập mật khẩu';
            isValid = false;
          } else if (passwordInput.value.length < 6) {
            passwordError.textContent = 'Mật khẩu phải có ít nhất 6 ký tự';
            isValid = false;
          } else {
            passwordError.textContent = '';
          }
          
          // Validate terms
          if (!termsCheckbox.checked) {
            isValid = false;
            alert('Bạn phải đồng ý với điều khoản sử dụng');
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
