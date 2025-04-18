// JavaScript for search functionality
document.addEventListener("DOMContentLoaded", () => {
  // Xử lý tìm kiếm từ thanh tìm kiếm chính
  const searchInput = document.getElementById("search-input")
  const searchForm = searchInput ? searchInput.closest("form") : null

  if (searchForm) {
    // Form đã có action và method, chỉ cần đảm bảo không bị chặn bởi JavaScript
    searchForm.addEventListener("submit", (e) => {
      const query = searchInput.value.trim()
      if (!query) {
        e.preventDefault() // Ngăn submit nếu không có từ khóa
      }
    })
  }

  // Xử lý gợi ý tìm kiếm (nếu có)
  const searchSuggestions = document.getElementById("search-suggestions")
  if (searchInput && searchSuggestions) {
    let debounceTimer

    searchInput.addEventListener("input", function () {
      clearTimeout(debounceTimer)

      const query = this.value.trim()
      if (query.length < 2) {
        searchSuggestions.innerHTML = ""
        searchSuggestions.style.display = "none"
        return
      }

      debounceTimer = setTimeout(() => {
        // Gửi yêu cầu AJAX để lấy gợi ý
        fetch(`/venow/api/search-suggestions.php?q=${encodeURIComponent(query)}`)
          .then((response) => response.json())
          .then((data) => {
            if (data.length > 0) {
              searchSuggestions.innerHTML = ""

              data.forEach((item) => {
                const suggestionItem = document.createElement("div")
                suggestionItem.className = "suggestion-item"
                suggestionItem.innerHTML = `
                  <div class="suggestion-icon"><i class="fas fa-search"></i></div>
                  <div class="suggestion-text">${item.text}</div>
                `

                suggestionItem.addEventListener("click", () => {
                  window.location.href = `/venow/views/search/index.php?q=${encodeURIComponent(item.text)}`
                })

                searchSuggestions.appendChild(suggestionItem)
              })

              searchSuggestions.style.display = "block"
            } else {
              searchSuggestions.innerHTML = ""
              searchSuggestions.style.display = "none"
            }
          })
          .catch((error) => {
            console.error("Lỗi khi lấy gợi ý tìm kiếm:", error)
          })
      }, 300)
    })

    // Ẩn gợi ý khi click ra ngoài
    document.addEventListener("click", (e) => {
      if (!searchInput.contains(e.target) && !searchSuggestions.contains(e.target)) {
        searchSuggestions.style.display = "none"
      }
    })
  }
})
