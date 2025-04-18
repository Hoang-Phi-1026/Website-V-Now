<?php
include_once("../../controllers/csukien.php");
include_once("../../controllers/cnguoidung.php");

// Khởi tạo controller
$sukienController = new cSuKien();

// Lấy ID sự kiện từ URL hoặc mặc định lấy sự kiện đầu tiên nếu không có ID
$ma_su_kien = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Nếu không có ID hoặc ID không hợp lệ, lấy sự kiện nổi bật đầu tiên
if ($ma_su_kien <= 0) {
    $sukienNoiBat = $sukienController->getSuKienNoiBat(1);
    if (!empty($sukienNoiBat)) {
        $ma_su_kien = $sukienNoiBat[0]['ma_su_kien'];
    } else {
        // Nếu không có sự kiện nổi bật, chuyển hướng về trang chủ
        header("Location: ../../index.php");
        exit();
    }
}

// Lấy thông tin chi tiết sự kiện
$sukien = $sukienController->getChiTietSuKien($ma_su_kien);

// Nếu không tìm thấy sự kiện, chuyển hướng về trang chủ
if (!$sukien) {
    header("Location: ../../index.php");
    exit();
}

// Lấy danh sách hình ảnh sự kiện
$hinhAnh = $sukienController->getHinhAnhSuKien($ma_su_kien);

// Lấy danh sách loại vé
$loaiVe = $sukienController->getLoaiVeSuKien($ma_su_kien);

// Lấy danh sách sự kiện liên quan
$sukienLienQuan = $sukienController->getSuKienLienQuan($ma_su_kien, 3);

// Định dạng ngày giờ
$ngayGio = $sukienController->dinhDangNgayGio($sukien['ngay_bat_dau'], $sukien['ngay_ket_thuc']);

// Khởi động session để kiểm tra đăng nhập
session_start();
$isLoggedIn = isset($_SESSION['user']);
$user = $isLoggedIn ? $_SESSION['user'] : null;
?>
<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($sukien['ten_su_kien']); ?> | VéNow</title>
  <link rel="stylesheet" href="../../css/theme.css">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="../../css/event-detail.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
 <?php
  $rootPath = $_SERVER['DOCUMENT_ROOT'] . '/venow/';
  include($rootPath . "layout/header.php");
?>

  <main class="event-detail-page">
    <div class="event-header">
      <div class="event-header-image">
        <img src="<?php echo htmlspecialchars($sukien['hinh_anh_chinh']); ?>" alt="<?php echo htmlspecialchars($sukien['ten_su_kien']); ?>">
      </div>
      <div class="event-header-overlay">
        <div class="event-header-content">
          <div class="event-breadcrumb">
            <a href="/venow/index.php">Trang chủ</a> &gt; <a href="/venow/views/sukien/danh-muc.php?slug=<?php echo htmlspecialchars($sukien['slug']); ?>"><?php echo htmlspecialchars($sukien['ten_danh_muc']); ?></a> &gt; <span><?php echo htmlspecialchars($sukien['ten_su_kien']); ?></span>
          </div>
          <h1><?php echo htmlspecialchars($sukien['ten_su_kien']); ?></h1>
          <div class="event-meta">
            <div class="event-date-time">
              <i class="far fa-calendar-alt"></i>
              <div>
                <p><?php echo htmlspecialchars($ngayGio['thu_start'] . ', ' . $ngayGio['ngay_start']); ?></p>
                <p><?php echo htmlspecialchars($ngayGio['gio_start'] . ' - ' . $ngayGio['gio_end']); ?> (Giờ Việt Nam)</p>
                <?php if (!$ngayGio['same_day']): ?>
                <p><?php echo htmlspecialchars('Đến ' . $ngayGio['thu_end'] . ', ' . $ngayGio['ngay_end']); ?></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="event-location">
              <i class="fas fa-map-marker-alt"></i>
              <div>
                <p><?php echo htmlspecialchars($sukien['ten_dia_diem']); ?></p>
                <p><?php echo htmlspecialchars($sukien['dia_chi'] . ', ' . $sukien['thanh_pho'] . ', ' . $sukien['tinh']); ?></p>
              </div>
            </div>
          </div>
          <div class="event-actions">
            <button class="btn-share"><i class="fas fa-share-alt"></i> Chia sẻ</button>
            <button class="btn-save"><i class="far fa-bookmark"></i> Lưu</button>
          </div>
        </div>
      </div>
    </div>

    <div class="event-content">
      <div class="event-main">
        <section class="event-description">
          <h2>Thông tin sự kiện</h2>
          <div class="description-content">
            <?php if (!empty($sukien['mo_ta'])): ?>
              <p><?php echo nl2br(htmlspecialchars($sukien['mo_ta'])); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($sukien['mo_ta_ngan'])): ?>
              <p><?php echo nl2br(htmlspecialchars($sukien['mo_ta_ngan'])); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($sukien['noi_dung'])): ?>
              <?php echo $sukien['noi_dung']; ?>
            <?php endif; ?>
            
            <?php if (!empty($hinhAnh)): ?>
            <div class="event-images">
              <?php foreach ($hinhAnh as $hinh): ?>
              <img src="<?php echo htmlspecialchars($hinh['duong_dan']); ?>" alt="<?php echo htmlspecialchars($hinh['tieu_de'] ?? $sukien['ten_su_kien']); ?>">
              <?php endforeach; ?>
            </div>
            <?php endif; ?>
          </div>
        </section>

        <section class="event-organizer">
          <h2>Nhà tổ chức</h2>
          <div class="organizer-info">
            <img src="<?php echo htmlspecialchars($sukien['logo_to_chuc'] ?? '../../images/organizer-logo.png'); ?>" alt="<?php echo htmlspecialchars($sukien['ten_to_chuc']); ?>">
            <div>
              <h3><?php echo htmlspecialchars($sukien['ten_to_chuc']); ?></h3>
              <p>Nhà tổ chức sự kiện <?php echo htmlspecialchars($sukien['ten_su_kien']); ?></p>
              <a href="#" class="btn-follow">Theo dõi</a>
            </div>
          </div>
        </section>

        <section class="event-location-map">
          <h2>Địa điểm</h2>
          <div class="location-details">
            <div class="map-container">
              <?php if (!empty($sukien['toa_do_lat']) && !empty($sukien['toa_do_lng'])): ?>
              <iframe
                width="100%"
                height="250"
                frameborder="0"
                style="border:0"
                src="https://www.google.com/maps/embed/v1/place?key=YOUR_API_KEY&q=<?php echo $sukien['toa_do_lat']; ?>,<?php echo $sukien['toa_do_lng']; ?>"
                allowfullscreen
              ></iframe>
              <?php else: ?>
              <img src="../../images/map.jpg" alt="Bản đồ địa điểm">
              <?php endif; ?>
              <a href="https://maps.google.com/?q=<?php echo urlencode($sukien['dia_chi'] . ', ' . $sukien['thanh_pho'] . ', ' . $sukien['tinh']); ?>" target="_blank" class="btn-view-map">Xem bản đồ lớn</a>
            </div>
            <div class="location-info">
              <h3><?php echo htmlspecialchars($sukien['ten_dia_diem']); ?></h3>
              <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($sukien['dia_chi'] . ', ' . $sukien['thanh_pho'] . ', ' . $sukien['tinh']); ?></p>
              <a href="https://maps.google.com/maps?daddr=<?php echo urlencode($sukien['dia_chi'] . ', ' . $sukien['thanh_pho'] . ', ' . $sukien['tinh']); ?>" target="_blank" class="btn-directions">Chỉ đường</a>
            </div>
          </div>
        </section>
      </div>

      <div class="event-sidebar">
        <div class="ticket-box">
          <h2>Vé sự kiện</h2>
          <div class="ticket-types">
            <?php if (!empty($loaiVe)): ?>
              <?php foreach ($loaiVe as $ve): ?>
              <div class="ticket-type">
                <div class="ticket-type-header">
                  <h3><?php echo htmlspecialchars($ve['ten_loai_ve']); ?></h3>
                  <p class="ticket-price"><?php echo $sukienController->dinhDangGiaTien($ve['gia_ve']); ?></p>
                </div>
                <div class="ticket-type-details">
                  <p>Bao gồm:</p>
                  <ul>
                    <?php if (!empty($ve['mo_ta'])): ?>
                      <?php 
                      $moTaLines = explode("\n", $ve['mo_ta']);
                      foreach ($moTaLines as $line): 
                        if (trim($line) !== ''):
                      ?>
                      <li><?php echo htmlspecialchars(trim($line)); ?></li>
                      <?php 
                        endif;
                      endforeach; 
                      ?>
                    <?php else: ?>
                      <li>Vé tham dự sự kiện</li>
                    <?php endif; ?>
                  </ul>
                  <div class="ticket-quantity">
                    <label for="ticket-<?php echo $ve['ma_loai_ve']; ?>">Số lượng:</label>
                    <div class="quantity-control">
                      <button class="quantity-decrease">-</button>
                      <input type="number" id="ticket-<?php echo $ve['ma_loai_ve']; ?>" value="0" min="0" max="<?php echo min(5, $ve['so_ve_con_lai']); ?>" data-price="<?php echo $ve['gia_ve']; ?>">
                      <button class="quantity-increase">+</button>
                    </div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p>Hiện tại chưa có thông tin vé cho sự kiện này.</p>
            <?php endif; ?>
          </div>
          <div class="ticket-summary">
            <div class="summary-row">
              <span>Tổng tiền:</span>
              <span class="total-price">0 ₫</span>
            </div>
          </div>
          <button class="btn-buy-ticket">Mua vé ngay</button>
          <p class="ticket-note">* Giá vé đã bao gồm thuế VAT và phí dịch vụ</p>
        </div>

        <div class="event-share">
          <h3>Chia sẻ sự kiện</h3>
          <div class="share-buttons">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-facebook"><i class="fab fa-facebook-f"></i> Facebook</a>
            <a href="https://twitter.com/intent/tweet?text=<?php echo urlencode($sukien['ten_su_kien']); ?>&url=<?php echo urlencode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" target="_blank" class="share-twitter"><i class="fab fa-twitter"></i> Twitter</a>
            <a href="mailto:?subject=<?php echo urlencode($sukien['ten_su_kien']); ?>&body=<?php echo urlencode('Xem sự kiện này: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="share-email"><i class="fas fa-envelope"></i> Email</a>
          </div>
        </div>

        <div class="related-events">
          <h3>Sự kiện liên quan</h3>
          <div class="related-event-list">
            <?php if (!empty($sukienLienQuan)): ?>
              <?php foreach ($sukienLienQuan as $lienQuan): 
                $thongTin = $sukienController->getThongTinHienThi($lienQuan);
              ?>
              <div class="related-event-item" onclick="location.href='chi-tiet.php?id=<?php echo $thongTin['ma_su_kien']; ?>'">
                <img src="<?php echo htmlspecialchars($thongTin['hinh_anh']); ?>" alt="<?php echo htmlspecialchars($thongTin['ten_su_kien']); ?>">
                <div>
                  <h4><?php echo htmlspecialchars($thongTin['ten_su_kien']); ?></h4>
                  <p><?php echo htmlspecialchars($thongTin['ngay_gio']['ngay_start']); ?> | <?php echo htmlspecialchars($thongTin['dia_diem']); ?></p>
                </div>
              </div>
              <?php endforeach; ?>
            <?php else: ?>
              <p>Không có sự kiện liên quan.</p>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include("../../layout/footer.php"); ?>

  <script src="../../js/theme.js"></script>
  <script src="../../js/main.js"></script>
  <script src="../../js/event-detail.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Ticket quantity controls
      const decreaseButtons = document.querySelectorAll(".quantity-decrease");
      const increaseButtons = document.querySelectorAll(".quantity-increase");
      const quantityInputs = document.querySelectorAll(".ticket-quantity input");
      const totalPriceElement = document.querySelector(".total-price");
      
      // Format currency function
      function formatCurrency(number) {
        return new Intl.NumberFormat("vi-VN", { style: "currency", currency: "VND" }).format(number);
      }
      
      // Update total price
      function updateTotalPrice() {
        let total = 0;
        quantityInputs.forEach((input) => {
          const quantity = parseInt(input.value) || 0;
          const price = parseFloat(input.getAttribute('data-price')) || 0;
          total += quantity * price;
        });
        
        if (totalPriceElement) {
          totalPriceElement.textContent = formatCurrency(total);
        }
      }
      
      // Decrease quantity
      decreaseButtons.forEach((button) => {
        button.addEventListener("click", function() {
          const input = this.parentElement.querySelector("input");
          const value = parseInt(input.value) || 0;
          if (value > 0) {
            input.value = value - 1;
            updateTotalPrice();
          }
        });
      });
      
      // Increase quantity
      increaseButtons.forEach((button) => {
        button.addEventListener("click", function() {
          const input = this.parentElement.querySelector("input");
          const value = parseInt(input.value) || 0;
          const max = parseInt(input.getAttribute("max")) || 5;
          if (value < max) {
            input.value = value + 1;
            updateTotalPrice();
          }
        });
      });
      
      // Manual input change
      quantityInputs.forEach((input) => {
        input.addEventListener("change", function() {
          const value = parseInt(this.value) || 0;
          const max = parseInt(this.getAttribute("max")) || 5;
          
          if (value < 0) {
            this.value = 0;
          } else if (value > max) {
            this.value = max;
          }
          
          updateTotalPrice();
        });
      });
      
      // Initialize total price
      updateTotalPrice();
      
      // Buy ticket button
      const buyTicketButton = document.querySelector(".btn-buy-ticket");
      if (buyTicketButton) {
        buyTicketButton.addEventListener("click", () => {
          let totalQuantity = 0;
          quantityInputs.forEach((input) => {
            totalQuantity += parseInt(input.value) || 0;
          });
          
          if (totalQuantity === 0) {
            alert("Vui lòng chọn ít nhất 1 vé để tiếp tục");
          } else {
            <?php if ($isLoggedIn): ?>
            alert("Đang chuyển đến trang thanh toán...");
            // Redirect to checkout page would go here
            <?php else: ?>
            alert("Vui lòng đăng nhập để mua vé");
            window.location.href = "/venow/views/dangnhap/login.php";
            <?php endif; ?>
          }
        });
      }
      
      // Share buttons
      const shareButtons = document.querySelectorAll(".share-buttons a");
      shareButtons.forEach((button) => {
        button.addEventListener("click", function(e) {
          if (this.classList.contains("share-email")) {
            return; // Allow email link to work normally
          }
          
          e.preventDefault();
          window.open(this.href, "share-window", "width=600,height=400");
        });
      });
      
      // Save event button
      const saveButton = document.querySelector(".btn-save");
      if (saveButton) {
        saveButton.addEventListener("click", function() {
          <?php if ($isLoggedIn): ?>
          // Toggle saved state
          const isSaved = this.innerHTML.includes("far");
          this.innerHTML = isSaved
            ? '<i class="fas fa-bookmark"></i> Đã lưu'
            : '<i class="far fa-bookmark"></i> Lưu';
          
          alert(isSaved
            ? "Đã lưu sự kiện vào danh sách yêu thích"
            : "Đã xóa sự kiện khỏi danh sách đã lưu");
          
          // In a real app, would send AJAX request to save/unsave
          <?php else: ?>
          alert("Vui lòng đăng nhập để lưu sự kiện");
          window.location.href = "/venow/views/dangnhap/login.php";
          <?php endif; ?>
        });
      }
    });
  </script>
</body>
</html>
