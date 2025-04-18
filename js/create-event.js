// JavaScript for the create event page

document.addEventListener("DOMContentLoaded", () => {
  // Step navigation
  const steps = document.querySelectorAll(".step")
  const nextButton = document.getElementById("btn-next-step")

  if (nextButton) {
    nextButton.addEventListener("click", () => {
      // Validate form fields
      const requiredFields = document.querySelectorAll("input[required], select[required]")
      let isValid = true

      requiredFields.forEach((field) => {
        if (!field.value.trim()) {
          isValid = false
          field.style.borderColor = "#f15a24"

          // Add error message if it doesn't exist
          let errorMessage = field.parentElement.querySelector(".error-message")
          if (!errorMessage) {
            errorMessage = document.createElement("p")
            errorMessage.className = "error-message"
            errorMessage.style.color = "#f15a24"
            errorMessage.style.fontSize = "14px"
            errorMessage.style.marginTop = "5px"
            errorMessage.textContent = "Trường này là bắt buộc"
            field.parentElement.appendChild(errorMessage)
          }
        } else {
          field.style.borderColor = "#ddd"

          // Remove error message if it exists
          const errorMessage = field.parentElement.querySelector(".error-message")
          if (errorMessage) {
            errorMessage.remove()
          }
        }
      })

      if (isValid) {
        // In a real application, this would navigate to the next step
        // For this demo, we'll just show an alert
        alert("Đã lưu thông tin cơ bản. Chuyển đến bước tiếp theo: Chi tiết sự kiện")

        // Update active step (for demo purposes)
        steps.forEach((step, index) => {
          if (index === 0) {
            step.classList.remove("active")
          } else if (index === 1) {
            step.classList.add("active")
          }
        })
      } else {
        alert("Vui lòng điền đầy đủ thông tin bắt buộc")
      }
    })
  }

  // Event type radio buttons
  const eventTypeRadios = document.querySelectorAll('input[name="event-type"]')
  const locationSection = document.querySelector(".location-section")

  eventTypeRadios.forEach((radio) => {
    radio.addEventListener("change", function () {
      if (this.value === "online") {
        locationSection.style.opacity = "0.5"
        locationSection.style.pointerEvents = "none"

        // Disable required fields in location section
        const locationRequiredFields = locationSection.querySelectorAll("input[required], select[required]")
        locationRequiredFields.forEach((field) => {
          field.removeAttribute("required")
          field.dataset.wasRequired = "true"
        })
      } else {
        locationSection.style.opacity = "1"
        locationSection.style.pointerEvents = "auto"

        // Re-enable required fields in location section
        const locationFields = locationSection.querySelectorAll(
          'input[data-was-required="true"], select[data-was-required="true"]',
        )
        locationFields.forEach((field) => {
          field.setAttribute("required", "")
        })
      }
    })
  })

  // Multiple days checkbox
  const multipleDaysCheckbox = document.getElementById("event-multiple-days")
  const endDateField = document.getElementById("event-end-date")
  const endTimeField = document.getElementById("event-end-time")

  if (multipleDaysCheckbox && endDateField) {
    // Initialize end date field state
    endDateField.disabled = !multipleDaysCheckbox.checked
    if (endTimeField) {
      endTimeField.disabled = false // End time is always enabled
    }

    multipleDaysCheckbox.addEventListener("change", function () {
      endDateField.disabled = !this.checked

      if (!this.checked) {
        // If unchecked, set end date to same as start date
        const startDateField = document.getElementById("event-start-date")
        if (startDateField && startDateField.value) {
          endDateField.value = startDateField.value
        }
      }
    })
  }

  // Start date change handler
  const startDateField = document.getElementById("event-start-date")
  if (startDateField && endDateField) {
    startDateField.addEventListener("change", function () {
      // Ensure end date is not before start date
      if (endDateField.value && endDateField.value < this.value) {
        endDateField.value = this.value
      }

      // Set min attribute on end date
      endDateField.setAttribute("min", this.value)
    })
  }

  // City selection change handler
  const citySelect = document.getElementById("event-city")
  const districtSelect = document.getElementById("event-district")

  if (citySelect && districtSelect) {
    citySelect.addEventListener("change", function () {
      // Clear district selection
      districtSelect.innerHTML = '<option value="" disabled selected>Chọn quận/huyện</option>'

      // Add districts based on selected city
      if (this.value === "hcm") {
        const hcmDistricts = [
          "Quận 1",
          "Quận 2",
          "Quận 3",
          "Quận 4",
          "Quận 5",
          "Quận 6",
          "Quận 7",
          "Quận 8",
          "Quận 9",
          "Quận 10",
          "Quận 11",
          "Quận 12",
          "Quận Bình Thạnh",
          "Quận Tân Bình",
          "Quận Phú Nhuận",
          "Quận Gò Vấp",
        ]

        hcmDistricts.forEach((district) => {
          const option = document.createElement("option")
          option.value = district.toLowerCase().replace(/\s+/g, "")
          option.textContent = district
          districtSelect.appendChild(option)
        })
      } else if (this.value === "hanoi") {
        const hanoiDistricts = [
          "Quận Ba Đình",
          "Quận Hoàn Kiếm",
          "Quận Hai Bà Trưng",
          "Quận Đống Đa",
          "Quận Tây Hồ",
          "Quận Cầu Giấy",
          "Quận Thanh Xuân",
          "Quận Hoàng Mai",
        ]

        hanoiDistricts.forEach((district) => {
          const option = document.createElement("option")
          option.value = district.toLowerCase().replace(/\s+/g, "")
          option.textContent = district
          districtSelect.appendChild(option)
        })
      } else if (this.value === "danang") {
        const danangDistricts = [
          "Quận Hải Châu",
          "Quận Thanh Khê",
          "Quận Sơn Trà",
          "Quận Ngũ Hành Sơn",
          "Quận Liên Chiểu",
          "Quận Cẩm Lệ",
        ]

        danangDistricts.forEach((district) => {
          const option = document.createElement("option")
          option.value = district.toLowerCase().replace(/\s+/g, "")
          option.textContent = district
          districtSelect.appendChild(option)
        })
      }
    })
  }

  // Map search button
  const mapSearchButton = document.querySelector(".btn-map-search")
  if (mapSearchButton) {
    mapSearchButton.addEventListener("click", () => {
      const venueField = document.getElementById("event-venue")
      const addressField = document.getElementById("event-address")
      const cityField = document.getElementById("event-city")

      let searchQuery = ""

      if (venueField && venueField.value) {
        searchQuery += venueField.value + ", "
      }

      if (addressField && addressField.value) {
        searchQuery += addressField.value + ", "
      }

      if (cityField && cityField.value) {
        if (cityField.value === "hcm") {
          searchQuery += "Hồ Chí Minh"
        } else if (cityField.value === "hanoi") {
          searchQuery += "Hà Nội"
        } else if (cityField.value === "danang") {
          searchQuery += "Đà Nẵng"
        }
      }

      if (searchQuery) {
        alert(`Đang tìm kiếm địa điểm: ${searchQuery}`)
        // In a real application, this would open a map interface
      } else {
        alert("Vui lòng nhập thông tin địa điểm để tìm kiếm")
      }
    })
  }
})
