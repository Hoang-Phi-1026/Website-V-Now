// Common JavaScript for all pages

// Mobile menu toggle
document.addEventListener("DOMContentLoaded", () => {
  // Add event listeners for language toggle
  const languageToggle = document.querySelector(".language-toggle")
  if (languageToggle) {
    languageToggle.addEventListener("click", (e) => {
      e.preventDefault()
      // Toggle language functionality would go here
      alert("Chức năng chuyển đổi ngôn ngữ sẽ được triển khai sau")
    })
  }

  // Add smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      const href = this.getAttribute("href")
      if (href !== "#") {
        e.preventDefault()
        document.querySelector(href).scrollIntoView({
          behavior: "smooth",
        })
      }
    })
  })

  // Initialize create event button
  const createEventBtn = document.querySelector(".create-event-btn")
  if (createEventBtn) {
    createEventBtn.addEventListener("click", (e) => {
      window.location.href = "create-event.php"
    })
  }
})

// Helper functions
function formatCurrency(amount) {
  return new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
    minimumFractionDigits: 0,
  }).format(amount)
}

function formatDate(dateString) {
  const options = {
    year: "numeric",
    month: "long",
    day: "numeric",
    weekday: "long",
  }
  return new Date(dateString).toLocaleDateString("vi-VN", options)
}

// Scroll to top button
window.onscroll = () => {
  // Add scroll to top button functionality if needed
}
