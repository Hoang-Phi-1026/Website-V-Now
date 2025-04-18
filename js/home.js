// JavaScript for the homepage

document.addEventListener("DOMContentLoaded", () => {
  // Banner slider functionality
  const slides = document.querySelectorAll(".banner-slide")
  const prevButton = document.querySelector(".prev-slide")
  const nextButton = document.querySelector(".next-slide")
  let currentSlide = 0
  const slideCount = slides.length

  function showSlide(index) {
    slides.forEach((slide) => {
      slide.classList.remove("active")
    })
    slides[index].classList.add("active")
  }

  function nextSlide() {
    currentSlide = (currentSlide + 1) % slideCount
    showSlide(currentSlide)
  }

  function prevSlide() {
    currentSlide = (currentSlide - 1 + slideCount) % slideCount
    showSlide(currentSlide)
  }

  if (prevButton && nextButton) {
    prevButton.addEventListener("click", prevSlide)
    nextButton.addEventListener("click", nextSlide)
  }

  // Auto slide every 5 seconds
  let slideInterval = setInterval(nextSlide, 5000)

  // Pause auto slide on hover
  const bannerSlider = document.querySelector(".banner-slider")
  if (bannerSlider) {
    bannerSlider.addEventListener("mouseenter", () => {
      clearInterval(slideInterval)
    })

    bannerSlider.addEventListener("mouseleave", () => {
      slideInterval = setInterval(nextSlide, 5000)
    })
  }

  // Special events slider
  const specialPrev = document.querySelector(".prev-special")
  const specialNext = document.querySelector(".next-special")
  const specialCards = document.querySelectorAll(".special-event-card")
  let currentSpecial = 0
  const specialCount = specialCards.length

  function showSpecial(index) {
    specialCards.forEach((card) => {
      card.style.display = "none"
    })
    specialCards[index].style.display = "block"
  }

  function nextSpecial() {
    currentSpecial = (currentSpecial + 1) % specialCount
    showSpecial(currentSpecial)
  }

  function prevSpecial() {
    currentSpecial = (currentSpecial - 1 + specialCount) % specialCount
    showSpecial(currentSpecial)
  }

  if (specialPrev && specialNext && specialCards.length > 0) {
    showSpecial(0) // Show first special event by default
    specialPrev.addEventListener("click", prevSpecial)
    specialNext.addEventListener("click", nextSpecial)
  }

  // Event card click handler
  const eventCards = document.querySelectorAll(".event-card")
  eventCards.forEach((card) => {
    card.addEventListener("click", function () {
      // If the card doesn't have an explicit onclick handler, navigate to event detail
      if (!this.hasAttribute("onclick")) {
        window.location.href = "event-detail.php"
      }
    })
  })

  // Category item click handler
  const categoryItems = document.querySelectorAll(".category-item")
  categoryItems.forEach((item) => {
    item.addEventListener("click", function () {
      // Navigate to category page or filter events
      const categoryName = this.querySelector("span").textContent
      alert(`Đang chuyển đến danh mục: ${categoryName}`)
    })
  })
})
