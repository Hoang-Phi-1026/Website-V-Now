document.addEventListener("DOMContentLoaded", () => {
  // Form validation
  const profileForm = document.querySelector(".profile-main form")
  if (profileForm) {
    profileForm.addEventListener("submit", function (e) {
      e.preventDefault()

      // Simulate form submission
      const saveButton = this.querySelector(".btn-save")
      const originalText = saveButton.textContent

      saveButton.textContent = "Đang lưu..."
      saveButton.disabled = true

      setTimeout(() => {
        saveButton.textContent = "Đã lưu!"

        setTimeout(() => {
          saveButton.textContent = originalText
          saveButton.disabled = false

          // Show success message
          alert("Thông tin cá nhân đã được cập nhật thành công!")
        }, 1500)
      }, 1000)
    })
  }

  // Dropdown menu
  const dropdownToggles = document.querySelectorAll(".dropdown-toggle")
  dropdownToggles.forEach((toggle) => {
    toggle.addEventListener("click", function (e) {
      e.preventDefault()
      const dropdown = this.closest(".dropdown")
      dropdown.classList.toggle("active")
    })
  })

  // Close dropdown when clicking outside
  document.addEventListener("click", (e) => {
    if (!e.target.closest(".dropdown")) {
      document.querySelectorAll(".dropdown.active").forEach((dropdown) => {
        dropdown.classList.remove("active")
      })
    }
  })
})
