// JavaScript for the event detail page

document.addEventListener("DOMContentLoaded", () => {
  // Ticket quantity controls
  const decreaseButtons = document.querySelectorAll(".quantity-decrease")
  const increaseButtons = document.querySelectorAll(".quantity-increase")
  const quantityInputs = document.querySelectorAll(".ticket-quantity input")
  const totalPriceElement = document.querySelector(".total-price")

  // Ticket prices
  const ticketPrices = {
    "standard-quantity": 200000,
    "vip-quantity": 350000,
  }

  // Format currency function
  function formatCurrency(number) {
    return new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(number)
  }

  // Update total price
  function updateTotalPrice() {
    let total = 0
    quantityInputs.forEach((input) => {
      const quantity = Number.parseInt(input.value) || 0
      const price = ticketPrices[input.id]
      total += quantity * price
    })

    if (totalPriceElement) {
      totalPriceElement.textContent = formatCurrency(total)
    }
  }

  // Decrease quantity
  decreaseButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const input = this.parentElement.querySelector("input")
      const value = Number.parseInt(input.value) || 0
      if (value > 0) {
        input.value = value - 1
        updateTotalPrice()
      }
    })
  })

  // Increase quantity
  increaseButtons.forEach((button) => {
    button.addEventListener("click", function () {
      const input = this.parentElement.querySelector("input")
      const value = Number.parseInt(input.value) || 0
      const max = Number.parseInt(input.getAttribute("max")) || 5
      if (value < max) {
        input.value = value + 1
        updateTotalPrice()
      }
    })
  })

  // Manual input change
  quantityInputs.forEach((input) => {
    input.addEventListener("change", function () {
      const value = Number.parseInt(this.value) || 0
      const max = Number.parseInt(this.getAttribute("max")) || 5

      if (value < 0) {
        this.value = 0
      } else if (value > max) {
        this.value = max
      }

      updateTotalPrice()
    })
  })

  // Initialize total price
  updateTotalPrice()

  // Buy ticket button
  const buyTicketButton = document.querySelector(".btn-buy-ticket")
  if (buyTicketButton) {
    buyTicketButton.addEventListener("click", () => {
      let totalQuantity = 0
      quantityInputs.forEach((input) => {
        totalQuantity += Number.parseInt(input.value) || 0
      })

      if (totalQuantity === 0) {
        alert("Vui lòng chọn ít nhất 1 vé để tiếp tục")
      } else {
        alert("Đang chuyển đến trang thanh toán...")
        // Redirect to checkout page would go here
      }
    })
  }

  // Share buttons
  const shareButtons = document.querySelectorAll(".share-buttons a")
  shareButtons.forEach((button) => {
    button.addEventListener("click", function (e) {
      e.preventDefault()

      const eventTitle = document.querySelector("h1").textContent
      const eventUrl = window.location.href

      if (this.classList.contains("share-facebook")) {
        window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(eventUrl)}`, "_blank")
      } else if (this.classList.contains("share-twitter")) {
        window.open(
          `https://twitter.com/intent/tweet?text=${encodeURIComponent(eventTitle)}&url=${encodeURIComponent(eventUrl)}`,
          "_blank",
        )
      } else if (this.classList.contains("share-email")) {
        window.location.href = `mailto:?subject=${encodeURIComponent(eventTitle)}&body=${encodeURIComponent("Xem sự kiện này: " + eventUrl)}`
      }
    })
  })

  // Event actions
  const btnShare = document.querySelector(".btn-share")
  const btnSave = document.querySelector(".btn-save")

  if (btnShare) {
    btnShare.addEventListener("click", () => {
      if (navigator.share) {
        navigator
          .share({
            title: document.querySelector("h1").textContent,
            url: window.location.href,
          })
          .catch(console.error)
      } else {
        alert("Chức năng chia sẻ không được hỗ trợ trên trình duyệt này")
      }
    })
  }

  if (btnSave) {
    btnSave.addEventListener("click", function () {
      this.innerHTML = this.innerHTML.includes("far")
        ? '<i class="fas fa-bookmark"></i> Đã lưu'
        : '<i class="far fa-bookmark"></i> Lưu'

      alert(
        this.innerHTML.includes("far")
          ? "Đã xóa sự kiện khỏi danh sách đã lưu"
          : "Đã lưu sự kiện vào danh sách yêu thích",
      )
    })
  }
})
