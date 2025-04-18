<?php
// Cập nhật file index.php để thêm tính năng tìm kiếm nhanh
include_once("controllers/csukien.php");
include_once("controllers/cnguoidung.php");

// Khởi tạo controller
$sukienController = new cSuKien();

// Lấy danh sách sự kiện nổi bật
$sukienNoiBat = $sukienController->getSuKienNoiBat(4);

// Lấy danh sách sự kiện sắp diễn ra
$sukienSapDienRa = $sukienController->getSuKienSapDienRa(4);

// Lấy danh sách danh mục
$danhMuc = $sukienController->getDanhMucSuKien();

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
  <title>VéNow - Nền tảng mua bán vé sự kiện trực tuyến</title>
  <link rel="stylesheet" href="css/theme.css">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/home.css">
  <link rel="stylesheet" href="css/search.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  
  <?php
    include("layout/header.php");
  ?>

  <main>
    <section class="hero-banner">
      <div class="banner-slider">
        <div class="banner-slide active">
          <img src="images/banner1.jpg" alt="Banner sự kiện">
          <div class="banner-content">
            <h2>Khám phá các sự kiện hấp dẫn</h2>
            <p>Mua vé ngay hôm nay để không bỏ lỡ những trải nghiệm tuyệt vời</p>
            <a href="views/search/index.php" class="btn-primary">Xem thêm</a>
          </div>
        </div>
        <div class="banner-slide">
          <img src="images/banner2.jpg" alt="Banner sự kiện">
          <div class="banner-content">
            <h2>Sự kiện âm nhạc đỉnh cao</h2>
            <p>Đắm chìm trong không gian âm nhạc cùng các nghệ sĩ hàng đầu</p>
            <a href="views/search/index.php?category=am-nhac" class="btn-primary">Mua vé ngay</a>
          </div>
        </div>
        <div class="banner-controls">
          <button class="prev-slide"><i class="fas fa-chevron-left"></i></button>
          <button class="next-slide"><i class="fas fa-chevron-right"></i></button>
        </div>
      </div>
    </section>

    <section class="event-categories">
      <div class="section-header">
        <h2>Khám phá theo danh mục</h2>
        <a href="views/search/index.php" class="view-all">Xem tất cả</a>
      </div>
      <div class="category-list">
        <?php foreach ($danhMuc as $category): ?>
        <div class="category-item" data-category="<?php echo htmlspecialchars($category['slug']); ?>">
          <div class="category-icon"><i class="<?php echo htmlspecialchars($category['icon']); ?>"></i></div>
          <span><?php echo htmlspecialchars($category['ten_danh_muc']); ?></span>
        </div>
        <?php endforeach; ?>
      </div>
    </section>

    <section class="featured-events">
      <div class="section-header">
        <h2>Sự kiện nổi bật</h2>
        <a href="views/search/index.php" class="view-all">Xem tất cả</a>
      </div>
      <div class="event-grid">
        <?php if (!empty($sukienNoiBat)): ?>
          <?php foreach ($sukienNoiBat as $sukien): 
            $thongTin = $sukienController->getThongTinHienThi($sukien);
          ?>
          <div class="event-card" onclick="location.href='views/sukien/chi-tiet.php?id=<?php echo $thongTin['ma_su_kien']; ?>'">
            <div class="event-image">
              <img src="<?php echo htmlspecialchars($thongTin['hinh_anh']); ?>" alt="<?php echo htmlspecialchars($thongTin['ten_su_kien']); ?>">
              <div class="event-date">
                <span class="month"><?php echo htmlspecialchars($thongTin['month']); ?></span>
                <span class="day"><?php echo htmlspecialchars($thongTin['day']); ?></span>
              </div>
            </div>
            <div class="event-info">
              <h3><?php echo htmlspecialchars($thongTin['ten_su_kien']); ?></h3>
              <p class="event-time"><i class="far fa-clock"></i> <?php echo htmlspecialchars($thongTin['ngay_gio']['display']); ?></p>
              <p class="event-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($thongTin['dia_diem']); ?>, <?php echo htmlspecialchars($thongTin['dia_chi']); ?></p>
              <p class="event-price"><?php echo htmlspecialchars($thongTin['gia_ve']); ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="no-events">
            <p>Hiện tại chưa có sự kiện nổi bật nào.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <section class="upcoming-events">
      <div class="section-header">
        <h2>Sự kiện sắp diễn ra</h2>
        <a href="views/search/index.php?date=week" class="view-all">Xem tất cả</a>
      </div>
      <div class="event-grid">
        <?php if (!empty($sukienSapDienRa)): ?>
          <?php foreach ($sukienSapDienRa as $sukien): 
            $thongTin = $sukienController->getThongTinHienThi($sukien);
          ?>
          <div class="event-card" onclick="location.href='views/sukien/chi-tiet.php?id=<?php echo $thongTin['ma_su_kien']; ?>'">
            <div class="event-image">
              <img src="<?php echo htmlspecialchars($thongTin['hinh_anh']); ?>" alt="<?php echo htmlspecialchars($thongTin['ten_su_kien']); ?>">
              <div class="event-date">
                <span class="month"><?php echo htmlspecialchars($thongTin['month']); ?></span>
                <span class="day"><?php echo htmlspecialchars($thongTin['day']); ?></span>
              </div>
            </div>
            <div class="event-info">
              <h3><?php echo htmlspecialchars($thongTin['ten_su_kien']); ?></h3>
              <p class="event-time"><i class="far fa-clock"></i> <?php echo htmlspecialchars($thongTin['ngay_gio']['display']); ?></p>
              <p class="event-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($thongTin['dia_diem']); ?>, <?php echo htmlspecialchars($thongTin['dia_chi']); ?></p>
              <p class="event-price"><?php echo htmlspecialchars($thongTin['gia_ve']); ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="no-events">
            <p>Hiện tại chưa có sự kiện sắp diễn ra nào.</p>
          </div>
        <?php endif; ?>
      </div>
    </section>

    <section class="special-events">
      <div class="section-header">
        <h2>Sự kiện đặc biệt</h2>
        <a href="views/search/index.php?price=500000+" class="view-all">Xem tất cả</a>
      </div>
      <div class="special-events-slider">
        <div class="special-event-card">
          <img src="images/special1.jpg" alt="Sự kiện đặc biệt">
          <div class="special-event-info">
            <h3>Đêm nhạc Trịnh Công Sơn</h3>
            <p>Đêm nhạc tri ân nhạc sĩ Trịnh Công Sơn với sự tham gia của nhiều ca sĩ nổi tiếng</p>
            <a href="views/search/index.php?q=Trịnh+Công+Sơn" class="btn-secondary">Xem chi tiết</a>
          </div>
        </div>
        <div class="special-event-card">
          <img src="images/special2.jpg" alt="Sự kiện đặc biệt">
          <div class="special-event-info">
            <h3>Lễ hội Ánh sáng 2025</h3>
            <p>Trải nghiệm không gian nghệ thuật ánh sáng độc đáo lần đầu tiên tại Việt Nam</p>
            <a href="views/search/index.php?q=Lễ+hội+Ánh+sáng" class="btn-secondary">Xem chi tiết</a>
          </div>
        </div>
        <div class="special-events-controls">
          <button class="prev-special"><i class="fas fa-chevron-left"></i></button>
          <button class="next-special"><i class="fas fa-chevron-right"></i></button>
        </div>
      </div>
    </section>
  </main>

  <?php include("layout/footer.php"); ?>

  <script src="js/theme.js"></script>
  <script src="js/main.js"></script>
  <script src="js/home.js"></script>
  <script src="js/search.js"></script>
</body>
</html>
