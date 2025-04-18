<?php
// Include the controller
include_once("../../controllers/SearchController.php");
include_once("../../controllers/csukien.php");
include_once("../../controllers/cnguoidung.php");

// Initialize controllers
$searchController = new SearchController();
$sukienController = new cSuKien();

// Process search and get results
$searchData = $searchController->processSearch();

// Extract data from search results
$keyword = $searchData['keyword'];
$sukien = $searchData['sukien'];
$danhMuc = $searchData['danhMuc'];
$totalEvents = $searchData['totalEvents'];
$totalPages = $searchData['totalPages'];
$page = $searchData['currentPage'];

// Check user login status
session_start();
$isLoggedIn = isset($_SESSION['user']);
$user = $isLoggedIn ? $_SESSION['user'] : null;
?>
<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo !empty($keyword) ? "Tìm kiếm: " . htmlspecialchars($keyword) : "Tất cả sự kiện"; ?> | VéNow</title>
  <link rel="stylesheet" href="../../css/theme.css">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="../../css/home.css">
  <link rel="stylesheet" href="../../css/search-page.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
</head>
<body>
  <?php
  $rootPath = $_SERVER['DOCUMENT_ROOT'] . '/venow/';
  include($rootPath . "layout/header.php");
  ?>

  <main>
    <div class="search-container">
      <div class="search-header">
        <h1><?php echo !empty($keyword) ? "Kết quả tìm kiếm cho \"" . htmlspecialchars($keyword) . "\"" : "Tất cả sự kiện"; ?></h1>
        <p><?php echo !empty($keyword) ? "Các sự kiện phù hợp với từ khóa tìm kiếm" : "Khám phá các sự kiện phù hợp với sở thích của bạn"; ?></p>
      </div>
      
      <!-- Search Form -->
      <form class="search-form" action="index.php" method="GET">
        <div class="search-input-group">
          <input type="text" name="q" placeholder="Nhập từ khóa tìm kiếm..." value="<?php echo htmlspecialchars($keyword); ?>">
          <button type="submit"><i class="fas fa-search"></i> Tìm kiếm</button>
        </div>
        
        <div class="filter-row">
          <div class="filter-group">
            <label for="filter-category"><i class="fas fa-tag"></i> Danh mục</label>
            <select id="filter-category" name="category">
              <option value="">Tất cả danh mục</option>
              <?php foreach ($danhMuc as $category): ?>
              <option value="<?php echo htmlspecialchars($category['slug']); ?>" <?php echo isset($_GET['category']) && $_GET['category'] === $category['slug'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($category['ten_danh_muc']); ?>
              </option>
              <?php endforeach; ?>
            </select>
          </div>
          
          <div class="filter-group">
            <label for="filter-date"><i class="far fa-calendar-alt"></i> Thời gian</label>
            <select id="filter-date" name="date">
              <option value="">Tất cả thời gian</option>
              <option value="today" <?php echo isset($_GET['date']) && $_GET['date'] === 'today' ? 'selected' : ''; ?>>Hôm nay</option>
              <option value="tomorrow" <?php echo isset($_GET['date']) && $_GET['date'] === 'tomorrow' ? 'selected' : ''; ?>>Ngày mai</option>
              <option value="weekend" <?php echo isset($_GET['date']) && $_GET['date'] === 'weekend' ? 'selected' : ''; ?>>Cuối tuần này</option>
              <option value="week" <?php echo isset($_GET['date']) && $_GET['date'] === 'week' ? 'selected' : ''; ?>>Tuần này</option>
              <option value="month" <?php echo isset($_GET['date']) && $_GET['date'] === 'month' ? 'selected' : ''; ?>>Tháng này</option>
              <option value="custom" <?php echo isset($_GET['date']) && $_GET['date'] === 'custom' ? 'selected' : ''; ?>>Tùy chỉnh</option>
            </select>
          </div>
          
          <div class="filter-group">
            <label for="filter-location"><i class="fas fa-map-marker-alt"></i> Địa điểm</label>
            <select id="filter-location" name="location">
              <option value="">Tất cả địa điểm</option>
              <option value="hcm" <?php echo isset($_GET['location']) && $_GET['location'] === 'hcm' ? 'selected' : ''; ?>>TP. Hồ Chí Minh</option>
              <option value="hanoi" <?php echo isset($_GET['location']) && $_GET['location'] === 'hanoi' ? 'selected' : ''; ?>>Hà Nội</option>
              <option value="danang" <?php echo isset($_GET['location']) && $_GET['location'] === 'danang' ? 'selected' : ''; ?>>Đà Nẵng</option>
            </select>
          </div>
          
          <div class="filter-group">
            <label for="filter-price"><i class="fas fa-tag"></i> Giá vé</label>
            <select id="filter-price" name="price">
              <option value="">Tất cả giá</option>
              <option value="free" <?php echo isset($_GET['price']) && $_GET['price'] === 'free' ? 'selected' : ''; ?>>Miễn phí</option>
              <option value="0-100000" <?php echo isset($_GET['price']) && $_GET['price'] === '0-100000' ? 'selected' : ''; ?>>Dưới 100.000 ₫</option>
              <option value="100000-300000" <?php echo isset($_GET['price']) && $_GET['price'] === '100000-300000' ? 'selected' : ''; ?>>100.000 ₫ - 300.000 ₫</option>
              <option value="300000-500000" <?php echo isset($_GET['price']) && $_GET['price'] === '300000-500000' ? 'selected' : ''; ?>>300.000 ₫ - 500.000 ₫</option>
              <option value="500000+" <?php echo isset($_GET['price']) && $_GET['price'] === '500000+' ? 'selected' : ''; ?>>Trên 500.000 ₫</option>
            </select>
          </div>
        </div>
        
        <div class="filter-group" id="custom-date-group" style="display: <?php echo isset($_GET['date']) && $_GET['date'] === 'custom' ? 'block' : 'none'; ?>; margin-bottom: 15px;">
          <label><i class="far fa-calendar-alt"></i> Khoảng thời gian</label>
          <div class="date-inputs">
            <input type="text" name="start_date" id="start-date" placeholder="Từ ngày" value="<?php echo isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
            <input type="text" name="end_date" id="end-date" placeholder="Đến ngày" value="<?php echo isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">
          </div>
        </div>
        
        <div class="filter-actions">
          <button type="button" id="btn-clear-filters" class="btn-filter btn-reset"><i class="fas fa-times"></i> Xóa bộ lọc</button>
          <button type="submit" class="btn-filter btn-apply"><i class="fas fa-filter"></i> Áp dụng</button>
        </div>
      </form>
      
      <!-- Active Filters -->
      <?php if (!empty($_GET['category']) || !empty($_GET['date']) || !empty($_GET['location']) || !empty($_GET['price'])): ?>
      <div class="active-filters">
        <?php if (!empty($_GET['category'])): 
          $categoryName = $searchController->getCategoryName($_GET['category'], $danhMuc);
        ?>
          <div class="filter-tag">
            Danh mục: <?php echo htmlspecialchars($categoryName); ?>
            <i class="fas fa-times" data-filter="category"></i>
          </div>
        <?php endif; ?>
        
        <?php if (!empty($_GET['date'])): ?>
          <div class="filter-tag">
            Thời gian: <?php echo htmlspecialchars($searchController->getDateFilterName($_GET['date'])); ?>
            <i class="fas fa-times" data-filter="date"></i>
          </div>
        <?php endif; ?>
        
        <?php if (!empty($_GET['location'])): ?>
          <div class="filter-tag">
            Địa điểm: <?php echo htmlspecialchars($searchController->getLocationFilterName($_GET['location'])); ?>
            <i class="fas fa-times" data-filter="location"></i>
          </div>
        <?php endif; ?>
        
        <?php if (!empty($_GET['price'])): ?>
          <div class="filter-tag">
            Giá vé: <?php echo htmlspecialchars($searchController->getPriceFilterName($_GET['price'])); ?>
            <i class="fas fa-times" data-filter="price"></i>
          </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      
      <!-- Results Count -->
      <div class="results-count">
        Tìm thấy <?php echo $totalEvents; ?> sự kiện
      </div>
      
      <!-- Search Results -->
      <?php if (empty($sukien)): ?>
      <div class="no-results">
        <p>Không tìm thấy sự kiện nào phù hợp với tìm kiếm của bạn</p>
        <p>Vui lòng thử lại với từ khóa khác hoặc điều chỉnh bộ lọc</p>
        <a href="index.php" class="btn-primary">Xóa bộ lọc</a>
      </div>
      <?php else: ?>
      <div class="event-grid">
        <?php foreach ($sukien as $event): 
          $thongTin = $sukienController->getThongTinHienThi($event);
        ?>
        <div class="event-card" onclick="location.href='../sukien/chi-tiet.php?id=<?php echo $thongTin['ma_su_kien']; ?>'">
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
      </div>
      
      <!-- Pagination -->
      <?php if ($totalPages > 1): ?>
      <div class="pagination">
        <?php if ($page > 1): ?>
          <a href="?q=<?php echo urlencode($keyword); ?>&page=<?php echo $page - 1; ?><?php echo !empty($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?><?php echo !empty($_GET['date']) ? '&date=' . $_GET['date'] : ''; ?><?php echo !empty($_GET['location']) ? '&location=' . $_GET['location'] : ''; ?><?php echo !empty($_GET['price']) ? '&price=' . $_GET['price'] : ''; ?>">
            <i class="fas fa-chevron-left"></i> Trước
          </a>
        <?php else: ?>
          <span class="disabled"><i class="fas fa-chevron-left"></i> Trước</span>
        <?php endif; ?>
        
        <?php
        // Hiển thị tối đa 5 trang
        $startPage = max(1, $page - 2);
        $endPage = min($totalPages, $startPage + 4);
        
        if ($endPage - $startPage < 4) {
          $startPage = max(1, $endPage - 4);
        }
        
        for ($i = $startPage; $i <= $endPage; $i++):
        ?>
          <?php if ($i == $page): ?>
            <span class="active"><?php echo $i; ?></span>
          <?php else: ?>
            <a href="?q=<?php echo urlencode($keyword); ?>&page=<?php echo $i; ?><?php echo !empty($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?><?php echo !empty($_GET['date']) ? '&date=' . $_GET['date'] : ''; ?><?php echo !empty($_GET['location']) ? '&location=' . $_GET['location'] : ''; ?><?php echo !empty($_GET['price']) ? '&price=' . $_GET['price'] : ''; ?>">
              <?php echo $i; ?>
            </a>
          <?php endif; ?>
        <?php endfor; ?>
        
        <?php if ($page < $totalPages): ?>
          <a href="?q=<?php echo urlencode($keyword); ?>&page=<?php echo $page + 1; ?><?php echo !empty($_GET['category']) ? '&category=' . $_GET['category'] : ''; ?><?php echo !empty($_GET['date']) ? '&date=' . $_GET['date'] : ''; ?><?php echo !empty($_GET['location']) ? '&location=' . $_GET['location'] : ''; ?><?php echo !empty($_GET['price']) ? '&price=' . $_GET['price'] : ''; ?>">
            Tiếp <i class="fas fa-chevron-right"></i>
          </a>
        <?php else: ?>
          <span class="disabled">Tiếp <i class="fas fa-chevron-right"></i></span>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      <?php endif; ?>
    </div>
  </main>

  <?php include("../../layout/footer.php"); ?>

  <script src="../../js/theme.js"></script>
  <script src="../../js/main.js"></script>
  <script src="../../js/search-page.js"></script>
</body>
</html>
