document.addEventListener("DOMContentLoaded", () => {
  // Toggle password visibility
  const togglePassword = document.querySelector(".toggle-password")
  const passwordInput = document.getElementById("password")

  if (togglePassword && passwordInput) {
    togglePassword.addEventListener("click", function () {
      const type = passwordInput.getAttribute("type") === "password" ? "text" : "password"
      passwordInput.setAttribute("type", type)

      // Toggle eye icon
      this.classList.toggle("fa-eye")
      this.classList.toggle("fa-eye-slash")
    })
  }

  // Password strength meter
  if (passwordInput && document.getElementById("strength-bar")) {
    const strengthBar = document.getElementById("strength-bar")
    const strengthText = document.getElementById("strength-text")

    passwordInput.addEventListener("input", function () {
      const value = this.value
      let strength = 0
      let message = ""

      if (value.length > 0) {
        // Kiểm tra độ dài
        if (value.length >= 8) {
          strength += 25
        }

        // Kiểm tra chữ thường
        if (value.match(/[a-z]+/)) {
          strength += 25
        }

        // Kiểm tra chữ hoa
        if (value.match(/[A-Z]+/)) {
          strength += 25
        }

        // Kiểm tra số và ký tự đặc biệt
        if (value.match(/[0-9]/) || value.match(/[^a-zA-Z0-9]/)) {
          strength += 25
        }

        // Hiển thị thông báo
        if (strength <= 25) {
          message = "Mật khẩu yếu"
          strengthBar.style.backgroundColor = "#f44336"
        } else if (strength <= 50) {
          message = "Mật khẩu trung bình"
          strengthBar.style.backgroundColor = "#ff9800"
        } else if (strength <= 75) {
          message = "Mật khẩu khá mạnh"
          strengthBar.style.backgroundColor = "#4caf50"
        } else {
          message = "Mật khẩu mạnh"
          strengthBar.style.backgroundColor = "#2e7d32"
        }
      } else {
        message = "Vui lòng nhập mật khẩu"
      }

      strengthBar.style.width = strength + "%"
      strengthText.textContent = message
    })
  }

  // Login form validation
  const loginForm = document.getElementById("login-form")
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => {
      let isValid = true

      const emailInput = document.getElementById("email")
      const passwordInput = document.getElementById("password")
      const emailError = document.getElementById("email-error")
      const passwordError = document.getElementById("password-error")

      // Validate email/phone
      if (!emailInput.value.trim()) {
        emailError.textContent = "Vui lòng nhập email hoặc số điện thoại"
        isValid = false
      } else {
        emailError.textContent = ""
      }

      // Validate password
      if (!passwordInput.value.trim()) {
        passwordError.textContent = "Vui lòng nhập mật khẩu"
        isValid = false
      } else {
        passwordError.textContent = ""
      }

      if (!isValid) {
        e.preventDefault()
      }
    })
  }

  // Register form validation
  const registerForm = document.getElementById("register-form")
  if (registerForm) {
    registerForm.addEventListener("submit", (e) => {
      let isValid = true

      const firstNameInput = document.getElementById("first-name")
      const lastNameInput = document.getElementById("last-name")
      const emailInput = document.getElementById("email")
      const phoneInput = document.getElementById("phone")
      const passwordInput = document.getElementById("password")
      const termsCheckbox = document.getElementById("terms")

      const firstNameError = document.getElementById("first-name-error")
      const lastNameError = document.getElementById("last-name-error")
      const emailError = document.getElementById("email-error")
      const phoneError = document.getElementById("phone-error")
      const passwordError = document.getElementById("password-error")

      // Validate first name
      if (!firstNameInput.value.trim()) {
        firstNameError.textContent = "Vui lòng nhập họ của bạn"
        isValid = false
      } else {
        firstNameError.textContent = ""
      }

      // Validate last name
      if (!lastNameInput.value.trim()) {
        lastNameError.textContent = "Vui lòng nhập tên của bạn"
        isValid = false
      } else {
        lastNameError.textContent = ""
      }

      // Validate email
      if (!emailInput.value.trim()) {
        emailError.textContent = "Vui lòng nhập email"
        isValid = false
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value.trim())) {
        emailError.textContent = "Email không hợp lệ"
        isValid = false
      } else {
        emailError.textContent = ""
      }

      // Validate phone
      if (!phoneInput.value.trim()) {
        phoneError.textContent = "Vui lòng nhập số điện thoại"
        isValid = false
      } else if (!/^[0-9]{10,11}$/.test(phoneInput.value.replace(/\s/g, ""))) {
        phoneError.textContent = "Số điện thoại không hợp lệ"
        isValid = false
      } else {
        phoneError.textContent = ""
      }

      // Validate password
      if (!passwordInput.value.trim()) {
        passwordError.textContent = "Vui lòng nhập mật khẩu"
        isValid = false
      } else if (passwordInput.value.length < 6) {
        passwordError.textContent = "Mật khẩu phải có ít nhất 6 ký tự"
        isValid = false
      } else {
        passwordError.textContent = ""
      }

      // Validate terms
      if (!termsCheckbox.checked) {
        isValid = false
        alert("Bạn phải đồng ý với điều khoản sử dụng")
      }

      if (!isValid) {
        e.preventDefault()
      }
    })
  }
})
