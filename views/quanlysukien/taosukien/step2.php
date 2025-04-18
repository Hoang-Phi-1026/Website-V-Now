<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tạo sự kiện - Thông tin vé | VéNow</title>
  <link rel="stylesheet" href="../../../css/theme.css">
  <link rel="stylesheet" href="../../../css/styles.css">
  <link rel="stylesheet" href="../../../css/create-event.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <?php
    $rootPath = $_SERVER['DOCUMENT_ROOT'] . '/venow/';
    include($rootPath . "layout/header.php");
  ?>

  <main class="create-event-page">
    <div class="create-event-container">
      <div class="create-event-progress">
        <div class="progress-step completed">
          <div class="step-number">1</div>
          <div class="step-label">Thông tin cơ bản</div>
        </div>
        <div class="progress-step active">
          <div class="step-number">2</div>
          <div class="step-label">Thời gian & Vé</div>
        </div>
        <div class="progress-step">
          <div class="step-number">3</div>
          <div class="step-label">Xuất bản</div>
        </div>
      </div>

      <form class="create-event-form">
        <!-- Thời gian sự kiện -->
        <div class="form-section">
          <div class="section-header">
            <h2 class="section-title">Thời Gian</h2>
            <div class="section-actions">
              <select id="month-select" class="month-select">
                <option value="" selected>Chọn tháng</option>
                <option value="1">Tháng 1</option>
                <option value="2">Tháng 2</option>
                <option value="3">Tháng 3</option>
                <option value="4">Tháng 4</option>
                <option value="5">Tháng 5</option>
                <option value="6">Tháng 6</option>
                <option value="7">Tháng 7</option>
                <option value="8">Tháng 8</option>
                <option value="9">Tháng 9</option>
                <option value="10">Tháng 10</option>
                <option value="11">Tháng 11</option>
                <option value="12">Tháng 12</option>
              </select>
            </div>
          </div>

          <div class="collapsible-section expanded">
            <div class="collapsible-header">
              <div class="collapse-icon">
                <i class="fas fa-chevron-down"></i>
              </div>
              <h3>Ngày sự kiện</h3>
              <button type="button" class="btn-close"><i class="fas fa-times"></i></button>
            </div>
            <div class="collapsible-content">
              <div class="form-row">
                <div class="form-group">
                  <label for="event-start-time">Thời gian bắt đầu</label>
                  <input type="text" id="event-start-time" name="event_start_time" placeholder="DD-MM-YYYY HH:MM" value="10-04-2025 12:04">
                  <button type="button" class="date-picker-btn"><i class="far fa-calendar-alt"></i></button>
                </div>
                <div class="form-group">
                  <label for="event-end-time">Thời gian kết thúc</label>
                  <input type="text" id="event-end-time" name="event_end_time" placeholder="DD-MM-YYYY HH:MM" value="30-04-2025 00:06">
                  <button type="button" class="date-picker-btn"><i class="far fa-calendar-alt"></i></button>
                </div>
              </div>
              
              <div class="form-group">
                <label for="ticket-type" class="required-label">Loại vé</label>
                <!-- Ticket type will be added here -->
              </div>
            </div>
          </div>
        </div>

        <!-- Thông tin vé -->
        <div class="form-section">
          <h2 class="section-title">Tạo loại vé mới</h2>
          
          <div class="form-group">
            <label for="ticket-name" class="required-label">Tên vé</label>
            <input type="text" id="ticket-name" name="ticket_name" placeholder="Tên vé" maxlength="50">
            <div class="char-count">0 / 50</div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="ticket-price" class="required-label">Giá vé</label>
              <div class="price-input-group">
                <input type="number" id="ticket-price" name="ticket_price" placeholder="0" min="0">
                <div class="free-ticket-option">
                  <input type="checkbox" id="free-ticket" name="free_ticket">
                  <label for="free-ticket">Miễn phí</label>
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <label for="ticket-quantity" class="required-label">Tổng số lượng vé</label>
              <input type="number" id="ticket-quantity" name="ticket_quantity" placeholder="10" min="1" value="10">
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="min-tickets" class="required-label">Số vé tối thiểu trong một đơn hàng</label>
              <input type="number" id="min-tickets" name="min_tickets" placeholder="1" min="1" value="1">
            </div>
            
            <div class="form-group">
              <label for="max-tickets" class="required-label">Số vé tối đa trong một đơn hàng</label>
              <input type="number" id="max-tickets" name="max_tickets" placeholder="10" min="1" value="10">
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="ticket-sale-start" class="required-label">Thời gian bắt đầu bán vé</label>
              <input type="text" id="ticket-sale-start" name="ticket_sale_start" placeholder="DD-MM-YYYY HH:MM" value="18-04-2025 19:40">
              <button type="button" class="date-picker-btn"><i class="far fa-calendar-alt"></i></button>
            </div>
            
            <div class="form-group">
              <label for="ticket-sale-end" class="required-label">Thời gian kết thúc bán vé</label>
              <input type="text" id="ticket-sale-end" name="ticket_sale_end" placeholder="DD-MM-YYYY HH:MM" value="10-04-2025 12:04">
              <button type="button" class="date-picker-btn"><i class="far fa-calendar-alt"></i></button>
            </div>
          </div>
          
          <div class="form-row">
            <div class="form-group">
              <label for="ticket-description">Thông tin vé</label>
              <textarea id="ticket-description" name="ticket_description" placeholder="Description" maxlength="1000"></textarea>
              <div class="char-count">0 / 1000</div>
            </div>
            
            <div class="form-group">
              <label>Hình ảnh vé</label>
              <div class="upload-box small">
                <div class="upload-area">
                  <i class="fas fa-upload"></i>
                  <p>Thêm</p>
                  <p class="upload-size">1MB</p>
                  <input type="file" id="ticket-image" class="file-input" accept="image/*">
                </div>
              </div>
            </div>
          </div>
          
          <div class="ticket-actions">
            <button type="button" class="btn-add-ticket">+ Thêm loại vé khác</button>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn-secondary" id="btn-back">Quay lại</button>
          <button type="button" class="btn-primary" id="btn-next-step">Tiếp theo</button>
        </div>
      </form>
    </div>
  </main>

  <?php include("../../../layout/footer.php"); ?>

  <script src="../../../js/theme.js"></script>
  <script src="../../../js/main.js"></script>
  <script src="../../../js/create-event.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Character counter for inputs and textareas
      const inputs = document.querySelectorAll('input[maxlength], textarea[maxlength]');
      
      inputs.forEach(input => {
        const charCount = input.nextElementSibling;
        if (charCount && charCount.classList.contains('char-count')) {
          // Initial count
          updateCharCount(input, charCount);
          
          // Update on input
          input.addEventListener('input', function() {
            updateCharCount(input, charCount);
          });
        }
      });
      
      function updateCharCount(input, countElement) {
        const maxLength = input.getAttribute('maxlength');
        const currentLength = input.value.length;
        countElement.textContent = `${currentLength} / ${maxLength}`;
      }
      
      // Free ticket checkbox
      const freeTicketCheckbox = document.getElementById('free-ticket');
      const ticketPriceInput = document.getElementById('ticket-price');
      
      if (freeTicketCheckbox && ticketPriceInput) {
        freeTicketCheckbox.addEventListener('change', function() {
          if (this.checked) {
            ticketPriceInput.value = '0';
            ticketPriceInput.disabled = true;
          } else {
            ticketPriceInput.disabled = false;
          }
        });
      }
      
      // Collapsible sections
      const collapsibleHeaders = document.querySelectorAll('.collapsible-header');
      
      collapsibleHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
          // Don't toggle if clicking the close button
          if (e.target.closest('.btn-close')) return;
          
          const section = this.closest('.collapsible-section');
          section.classList.toggle('expanded');
        });
      });
      
      // Close buttons
      const closeButtons = document.querySelectorAll('.btn-close');
      
      closeButtons.forEach(button => {
        button.addEventListener('click', function() {
          // In a real app, this would remove the section
          // For this demo, we'll just collapse it
          const section = this.closest('.collapsible-section');
          section.classList.remove('expanded');
        });
      });
      
      // Navigation buttons
      const backButton = document.getElementById('btn-back');
      const nextButton = document.getElementById('btn-next-step');
      
      if (backButton) {
        backButton.addEventListener('click', function() {
          window.location.href = 'step1.php';
        });
      }
      
      if (nextButton) {
        nextButton.addEventListener('click', function() {
          // In a real app, this would validate and save the form
          alert('Thông tin vé đã được lưu. Chuyển đến bước xuất bản sự kiện.');
          // window.location.href = 'step3.php';
        });
      }
      
      // Add ticket button
      const addTicketButton = document.querySelector('.btn-add-ticket');
      
      if (addTicketButton) {
        addTicketButton.addEventListener('click', function() {
          alert('Chức năng thêm loại vé mới sẽ được triển khai sau.');
        });
      }
      
      // File input preview
      const fileInputs = document.querySelectorAll('.file-input');
      
      fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
          const file = e.target.files[0];
          if (file) {
            const reader = new FileReader();
            const uploadArea = this.closest('.upload-area');
            
            reader.onload = function(e) {
              // Create preview
              let preview = uploadArea.querySelector('.preview-image');
              if (!preview) {
                preview = document.createElement('div');
                preview.className = 'preview-image';
                uploadArea.appendChild(preview);
              }
              
              // Set background image
              preview.style.backgroundImage = `url(${e.target.result})`;
              
              // Add remove button if not exists
              if (!uploadArea.querySelector('.remove-image')) {
                const removeBtn = document.createElement('button');
                removeBtn.className = 'remove-image';
                removeBtn.innerHTML = '<i class="fas fa-times"></i>';
                removeBtn.addEventListener('click', function(e) {
                  e.preventDefault();
                  preview.remove();
                  removeBtn.remove();
                  input.value = '';
                  uploadArea.classList.remove('has-image');
                });
                uploadArea.appendChild(removeBtn);
              }
              
              uploadArea.classList.add('has-image');
            };
            
            reader.readAsDataURL(file);
          }
        });
      });
    });
  </script>
</body>
</html>
