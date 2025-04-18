<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tạo sự kiện | VéNow</title>

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
        <div class="progress-step active">
          <div class="step-number">1</div>
          <div class="step-label">Thông tin cơ bản</div>
        </div>
        <div class="progress-step">
          <div class="step-number">2</div>
          <div class="step-label">Thời gian & Vé</div>
        </div>
        <div class="progress-step">
          <div class="step-number">3</div>
          <div class="step-label">Xuất bản</div>
        </div>
      </div>
      
      <form class="create-event-form">
        <!-- Upload hình ảnh -->
        <div class="form-section">
          <h2 class="section-title">Upload hình ảnh</h2>
          <div class="upload-images">
            <div class="upload-box">
              <div class="upload-area">
                <i class="fas fa-upload"></i>
                <p>Thêm logo sự kiện</p>
                <p class="upload-size">(720x540)</p>
                <input type="file" id="event-logo" class="file-input" accept="image/*">
              </div>
            </div>
            <div class="upload-box large">
              <div class="upload-area">
                <i class="fas fa-upload"></i>
                <p>Thêm ảnh nền sự kiện</p>
                <p class="upload-size">(1280x720)</p>
                <input type="file" id="event-banner" class="file-input" accept="image/*">
              </div>
            </div>
          </div>
        </div>

        <!-- Tên sự kiện -->
        <div class="form-section">
          <h2 class="section-title">Tên sự kiện</h2>
          <div class="form-group">
            <input type="text" id="event-name" name="event_name" placeholder="Tên sự kiện" maxlength="100">
            <div class="char-count">0 / 100</div>
          </div>
        </div>

        <!-- Địa chỉ sự kiện -->
        <div class="form-section">
          <h2 class="section-title">Địa chỉ sự kiện</h2>
          <div class="form-group radio-group">
            <label class="radio-container">
              <input type="radio" name="event_type" value="offline" checked>
              <span class="radio-label">Sự kiện Offline</span>
            </label>
            <label class="radio-container">
              <input type="radio" name="event_type" value="online">
              <span class="radio-label">Sự kiện Online</span>
            </label>
          </div>

          <div class="form-group">
            <input type="text" id="event-location" name="event_location" placeholder="Tên địa điểm" maxlength="80">
            <div class="char-count">0 / 80</div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <select id="event-city" name="event_city">
                <option value="" disabled selected>Tỉnh/Thành</option>
                <option value="hcm">TP. Hồ Chí Minh</option>
                <option value="hanoi">Hà Nội</option>
                <option value="danang">Đà Nẵng</option>
                <option value="other">Khác</option>
              </select>
            </div>
            <div class="form-group">
              <select id="event-district" name="event_district">
                <option value="" disabled selected>Quận/Huyện</option>
              </select>
            </div>
          </div>

          <div class="form-row">
            <div class="form-group">
              <select id="event-ward" name="event_ward">
                <option value="" disabled selected>Phường/Xã</option>
              </select>
            </div>
          </div>

          <div class="form-group">
            <input type="text" id="event-address" name="event_address" placeholder="Số nhà, đường" maxlength="80">
            <div class="char-count">0 / 80</div>
          </div>
        </div>

        <!-- Thể loại sự kiện -->
        <div class="form-section">
          <h2 class="section-title">Thể loại sự kiện</h2>
          <div class="form-group">
            <select id="event-category" name="event_category">
              <option value="" disabled selected>Vui lòng chọn</option>
              <option value="music">Âm nhạc</option>
              <option value="art">Nghệ thuật</option>
              <option value="workshop">Hội thảo & Workshop</option>
              <option value="cinema">Điện ảnh</option>
              <option value="sport">Thể thao</option>
              <option value="community">Cộng đồng</option>
              <option value="food">Ẩm thực</option>
              <option value="other">Khác</option>
            </select>
          </div>
        </div>

        <!-- Thông tin ban tổ chức -->
        <div class="form-section">
          <h2 class="section-title">Thông tin ban tổ chức</h2>
          <div class="organizer-info">
            <div class="upload-box small">
              <div class="upload-area">
                <i class="fas fa-upload"></i>
                <p>Thêm logo ban tổ chức</p>
                <p class="upload-size">(270x270)</p>
                <input type="file" id="organizer-logo" class="file-input" accept="image/*">
              </div>
            </div>
            <div class="form-group">
              <input type="text" id="organizer-name" name="organizer_name" placeholder="Tên ban tổ chức" maxlength="80">
              <div class="char-count">0 / 80</div>
            </div>
            <div class="form-group">
              <textarea id="organizer-info" name="organizer_info" placeholder="Thông tin ban tổ chức" maxlength="500"></textarea>
              <div class="char-count">0 / 500</div>
            </div>
          </div>
        </div>

        <div class="form-actions">
          <button type="button" class="btn-secondary">Lưu nháp</button>
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
    // Character counter for inputs and textareas
    document.addEventListener('DOMContentLoaded', function() {
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
      
      // Next step button
      const nextButton = document.getElementById("btn-next-step");
      if (nextButton) {
        nextButton.addEventListener("click", () => {
          // Validate form fields
          const requiredFields = document.querySelectorAll("input[required], select[required]");
          let isValid = true;

          requiredFields.forEach((field) => {
            if (!field.value.trim()) {
              isValid = false;
              field.style.borderColor = "#f15a24";

              // Add error message if it doesn't exist
              let errorMessage = field.parentElement.querySelector(".error-message");
              if (!errorMessage) {
                errorMessage = document.createElement("p");
                errorMessage.className = "error-message";
                errorMessage.style.color = "#f15a24";
                errorMessage.style.fontSize = "14px";
                errorMessage.style.marginTop = "5px";
                errorMessage.textContent = "Trường này là bắt buộc";
                field.parentElement.appendChild(errorMessage);
              }
            } else {
              field.style.borderColor = "#ddd";

              // Remove error message if it exists
              const errorMessage = field.parentElement.querySelector(".error-message");
              if (errorMessage) {
                errorMessage.remove();
              }
            }
          });

          if (isValid) {
            // Navigate to step 2
            window.location.href = "step2.php";
          } else {
            alert("Vui lòng điền đầy đủ thông tin bắt buộc");
          }
        });
      }
    });
  </script>
</body>
</html>
