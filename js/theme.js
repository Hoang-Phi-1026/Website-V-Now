// Theme switcher functionality
document.addEventListener("DOMContentLoaded", () => {
  // Check for saved theme preference in localStorage or use default light theme
  const savedTheme = localStorage.getItem("theme") || "light"
  document.documentElement.setAttribute("data-theme", savedTheme)

  // Update theme toggle button icon based on current theme
  updateThemeToggleIcon(savedTheme)

  // Add event listener to theme toggle button
  const themeToggle = document.getElementById("theme-toggle")
  if (themeToggle) {
    themeToggle.addEventListener("click", () => {
      // Get current theme
      const currentTheme = document.documentElement.getAttribute("data-theme")

      // Toggle theme
      const newTheme = currentTheme === "light" ? "dark" : "light"

      // Set new theme
      document.documentElement.setAttribute("data-theme", newTheme)

      // Save theme preference to localStorage
      localStorage.setItem("theme", newTheme)

      // Update toggle button icon
      updateThemeToggleIcon(newTheme)
    })
  }
})

// Function to update theme toggle icon
function updateThemeToggleIcon(theme) {
  const themeToggle = document.getElementById("theme-toggle")
  if (themeToggle) {
    if (theme === "dark") {
      themeToggle.innerHTML = '<i class="fas fa-sun"></i>'
      themeToggle.setAttribute("title", "Chuyển sang chế độ sáng")
    } else {
      themeToggle.innerHTML = '<i class="fas fa-moon"></i>'
      themeToggle.setAttribute("title", "Chuyển sang chế độ tối")
    }
  }
}
