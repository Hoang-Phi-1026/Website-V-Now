<?php
include_once("../../controllers/csukien.php");
include_once("../../controllers/cnguoidung.php");

// Khởi tạo controller
$sukienController = new cSuKien();

// Lấy slug danh mục từ URL
$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

// Nếu không có slug, chuyển hướng về trang chủ
if (empty($slug)) {
    header("Location: ../../index.php");
    exit();
}

// Lấy danh sách danh mục
$danhMuc = $sukienController->getDanhMucSuKien();

// Tìm danh mục hiện tại
$currentCategory = null;
foreach ($danhMuc as $category) {
    if ($category['slug'] === $slug) {
        $currentCategory = $category;
        break;
    }
}

// Nếu không tìm thấy danh mục, chuyển hướng về trang chủ
if (!$currentCategory) {
    header("Location: ../../index.php");
    exit();
}

// Xử lý các tham số lọc
$whereClause = "sk.ma_danh_muc = {$currentCategory['ma_danh_muc']} AND sk.trang_thai = 'Đã duyệt'";

// Lọc theo thời gian
if (isset($_GET['date']) && !empty($_GET['date'])) {
    $today = date('Y-m-d');
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    $weekend = date('Y-m-d', strtotime('next Saturday'));
    $weekEnd = date('Y-m-d', strtotime('next Sunday'));
    $monthEnd = date('Y-m-t'); // Ngày cuối cùng của tháng hiện tại
    
    switch ($_GET['date']) {
        case 'today':
            $whereClause .= " AND DATE(sk.ngay_bat_dau) = '$today'";
            break;
        case 'tomorrow':
            $whereClause .= " AND DATE(sk.ngay_bat_dau) = '$tomorrow'";
            break;
        case 'weekend':
            $whereClause .= " AND (DATE(sk.ngay_bat_dau) = '$weekend' OR DATE(sk.ngay_bat_dau) = '$weekEnd')";
            break;
        case 'week':
            $whereClause .= " AND DATE(sk.ngay_bat_dau) BETWEEN '$today' AND DATE_ADD('$today', INTERVAL 7 DAY)";
            break;
        case 'month':
            $whereClause .= " AND DATE(sk.ngay_bat_dau) BETWEEN '$today' AND '$monthEnd'";
            break;
    }
}

// Lọc theo địa điểm
if (isset($_GET['location']) && !empty($_GET['location'])) {
    switch ($_GET['location']) {
        case 'hcm':
            $whereClause .= " AND dd.thanh_pho LIKE '%Hồ Chí Minh%'";
            break;
        case 'hanoi':
            $whereClause .= " AND dd.thanh_pho LIKE '%Hà Nội%'";
            break;
        case 'danang':
            $whereClause .= " AND dd.thanh_pho LIKE '%Đà Nẵng%'";
            break;
    }
}

// Lọc theo giá vé
if (isset($_GET['price']) && !empty($_GET['price'])) {
    switch ($_GET['price']) {
        case 'free':
            $whereClause .= " AND sk.gia_ve_thap_nhat = 0";
            break;
        case '0-100000':
            $whereClause .= " AND sk.gia_ve_thap_nhat BETWEEN 0 AND 100000";
            break;
        case '100000-300000':
            $whereClause .= " AND sk.gia_ve_thap_nhat BETWEEN 100000 AND 300000";
            break;
        case '300000-500000':
            $whereClause .= " AND sk.gia_ve_thap_nhat BETWEEN 300000 AND 500000";
            break;
        case '500000+':
            $whereClause .= " AND sk.gia_ve_thap_nhat > 500000";
            break;
    }
}

// Phân trang
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$limit = 12; // Số sự kiện trên mỗi trang
$offset = ($page - 1) * $limit;

// Lấy tổng số sự kiện theo điều kiện lọc
$totalEvents = count($sukienController->getDanhSachSuKien($whereClause));
$totalPages = ceil($totalEvents / $limit);

// Lấy danh sách sự kiện theo danh mục và điều kiện lọc
$sukien = $sukienController->getDanhSachSuKien($whereClause, "sk.ngay_bat_dau ASC", $limit, $offset);

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
  <title><?php echo htmlspecialchars($currentCategory['ten_danh_muc']); ?> | VéNow</title>
  <link rel="stylesheet" href="../../css/theme.css">
  <link rel="stylesheet" href="../../css/styles.css">
  <link rel="stylesheet" href="../../css/home.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <style>
    .category-banner {
      background-color: var(--bg-secondary);
      padding: 40px 0;
      text-align: center;
      margin-bottom: 30px;
    }
    
    .category-banner h1 {
      font-size: 32px;
      color: var(--text-primary);
      margin-bottom: 10px;
    }
    
    .category-banner p {
      color: var(--text-light);
      max-width: 800px;
      margin: 0 auto;
    }
    
    .category-filters {
      background-color: var(--bg-card);
      border-radius: 8px;
      padding: 20px;
      margin-bottom: 30px;
      box-shadow: 0 2px 10px var(--shadow-color);
    }
    
    .filter-row {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-bottom: 15px;
    }
    
    .filter-group {
      flex: 1;
      min-width: 200px;
    }
    
    .filter-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: var(--text-primary);
    }
    
    .filter-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid var(--input-border);
      border-radius: 4px;
      background-color: var(--input-bg);
      color: var(--text-primary);
    }
    
    .filter-actions {
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }
    
    .btn-filter {
      padding: 10px 20px;
      border-radius: 4px;
      font-weight: 500;
      cursor: pointer;
    }
    
    .btn-reset {
      background-color: var(--bg-secondary);
      color: var(--text-primary);
      border: 1px solid var(--border-color);
    }
    
    .btn-apply {
      background-color: var(--btn-primary-bg);
      color: var(--btn-primary-text);
      border: none;
    }
    
    .no-events {
      background-color: var(--bg-card);
      border-radius: 8px;
      padding: 30px;
      text-align: center;
      box-shadow: 0 2px 10px var(--shadow-color);
      margin-bottom: 30px;
    }
    
    .no-events p {
      margin-bottom: 15px;
      color: var(--text-secondary);
    }
    
    .no-events p:first-child {
      font-size: 18px;
      font-weight: 500;
      color: var(--text-primary);
    }
    
    .pagination {
      display: flex;
      justify-content: center;
      margin: 30px 0;
    }
    
    .pagination a, .pagination span {
      display: inline-block;
      padding: 8px 16px;
      margin: 0 5px;
      border-radius: 4px;
      color: var(--text-primary);
      background-color: var(--bg-card);
      border: 1px solid var(--border-color);
      text-decoration: none;
      transition: all 0.3s;
    }
    
    .pagination a:hover {
      background-color: var(--bg-secondary);
    }
    
    .pagination .active {
      background-color: var(--accent-color);
      color: var(--bg-primary);
      border-color: var(--accent-color);
    }
    
    .pagination .disabled {
      color: var(--text-light);
      cursor: not-allowed;
    }
    
    .results-count {
      text-align: center;
      margin-bottom: 20px;
      color: var(--text-light);
    }
    
    .active-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-bottom: 20px;
    }
    
    .filter-tag {
      display: inline-flex;
      align-items: center;
      padding: 5px 10px;
      background-color: var(--bg-secondary);
      border-radius: 20px;
      font-size: 14px;
      color: var(--text-primary);
    }
    
    .filter-tag i {
      margin-left: 5px;
      cursor: pointer;
    }
    
    @media (max-width: 768px) {
      .filter-row {
        flex-direction: column;
        gap: 10px;
      }
      
      .filter-group {
        width: 100%;
      }
      
      .pagination a, .pagination span {
        padding: 6px 12px;
        margin: 0 3px;
      }
    }
  </style>
</head>
<body>
  <?php
  $rootPath = $_SERVER['DOCUMENT_ROOT'] . '/venow/';
  include($rootPath . "layout/header.php");
?>

  <main>
    <div class="category-banner">
      <h1><?php echo htmlspecialchars($currentCategory['ten_danh_muc']); ?></h1>
      <p><?php echo htmlspecialchars($currentCategory['mo_ta'] ?? 'Khám phá các sự kiện ' . $currentCategory['ten_danh_muc'] . ' hấp dẫn'); ?></p>
    </div>
    
    <div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
      <div class="category-filters">
        <form id="filter-form">
          <div class="filter-row">
            <div class="filter-group">
              <label for="filter-date">Thời gian</label>
              <select id="filter-date" name="date">
                <option value="">Tất cả</option>
                <option value="today" <?php echo isset($_GET['date']) && $_GET['date'] === 'today' ? 'selected' : ''; ?>>Hôm nay</option>
                <option value="tomorrow" <?php echo isset($_GET['date']) && $_GET['date'] === 'tomorrow' ? 'selected' : ''; ?>>Ngày mai</option>
                <option value="weekend" <?php echo isset($_GET['date']) && $_GET['date'] === 'weekend' ? 'selected' : ''; ?>>Cuối tuần này</option>
                <option value="week" <?php echo isset($_GET['date']) && $_GET['date'] === 'week' ? 'selected' : ''; ?>>Tuần này</option>
                <option value="month" <?php echo isset($_GET['date']) && $_GET['date'] === 'month' ? 'selected' : ''; ?>>Tháng này</option>
              </select>
            </div>
            
            <div class="filter-group">
              <label for="filter-location">Địa điểm</label>
              <select id="filter-location" name="location">
                <option value="">Tất cả</option>
                <option value="hcm" <?php echo isset($_GET['location']) && $_GET['location'] === 'hcm' ? 'selected' : ''; ?>>TP. Hồ Chí Minh</option>
                <option value="hanoi" <?php echo isset($_GET['location']) && $_GET['location'] === 'hanoi' ? 'selected' : ''; ?>>Hà Nội</option>
                <option value="danang" <?php echo isset($_GET['location']) && $_GET['location'] === 'danang' ? 'selected' : ''; ?>>Đà Nẵng</option>
              </select>
            </div>
            
            <div class="filter-group">
              <label for="filter-price">Giá vé</label>
              <select id="filter-price" name="price">
                <option value="">Tất cả</option>
                <option value="free" <?php echo isset($_GET['price']) && $_GET['price'] === 'free' ? 'selected' : ''; ?>>Miễn phí</option>
                <option value="0-100000" <?php echo isset($_GET['price']) && $_GET['price'] === '0-100000' ? 'selected' : ''; ?>>Dưới 100.000 ₫</option>
                <option value="100000-300000" <?php echo isset($_GET['price']) && $_GET['price'] === '100000-300000' ? 'selected' : ''; ?>>100.000 ₫ - 300.000 ₫</option>
                <option value="300000-500000" <?php echo isset($_GET['price']) && $_GET['price'] === '300000-500000' ? 'selected' : ''; ?>>300.000 ₫ - 500.000 ₫</option>
                <option value="500000+" <?php echo isset($_GET['price']) && $_GET['price'] === '500000+' ? 'selected' : ''; ?>>Trên 500.000 ₫</option>
              </select>
            </div>
          </div>
          
          <div class="filter-actions">
            <button type="button" id="btn-clear-filters" class="btn-filter btn-reset">Xóa bộ lọc</button>
            <button type="submit" class="btn-filter btn-apply">Áp dụng</button>
          </div>
        </form>
      </div>
      
      <?php if (!empty($_GET['date']) || !empty($_GET['location']) || !empty($_GET['price'])): ?>
      <div class="active-filters">
        <?php if (!empty($_GET['date'])): ?>
          <div class="filter-tag">
            Thời gian: 
            <?php 
              switch($_GET['date']) {
                case 'today': echo 'Hôm nay'; break;
                case 'tomorrow': echo 'Ngày mai'; break;
                case 'weekend': echo 'Cuối tuần này'; break;
                case 'week': echo 'Tuần này'; break;
                case 'month': echo 'Tháng này'; break;
              }
            ?>
            <i class="fas fa-times" data-filter="date"></i>
          </div>
        <?php endif; ?>
        
        <?php if (!empty($_GET['location'])): ?>
          <div class="filter-tag">
            Địa điểm: 
            <?php 
              switch($_GET['location']) {
                case 'hcm': echo 'TP. Hồ Chí Minh'; break;
                case 'hanoi': echo 'Hà Nội'; break;
                case 'danang': echo 'Đà Nẵng'; break;
              }
            ?>
            <i class="fas fa-times" data-filter="location"></i>
          </div>
        <?php endif; ?>
        
        <?php if (!empty($_GET['price'])): ?>
          <div class="filter-tag">
            Giá vé: 
            <?php 
              switch($_GET['price']) {
                case 'free': echo 'Miễn phí'; break;
                case '0-100000': echo 'Dưới 100.000 ₫'; break;
                case '100000-300000': echo '100.000 ₫ - 300.000 ₫'; break;
                case '300000-500000': echo '300.000 ₫ - 500.000 ₫'; break;
                case '500000+': echo 'Trên 500.000 ₫'; break;
              }
            ?>
            <i class="fas fa-times" data-filter="price"></i>
          </div>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      
      <div class="results-count">
        Tìm thấy <?php echo $totalEvents; ?> sự kiện
      </div>
      
      <?php if (empty($sukien)): ?>
      <div class="no-events">
        <p>Không tìm thấy sự kiện nào phù hợp với bộ lọc</p>
        <p>Vui lòng thử lại với bộ lọc khác hoặc khám phá các danh mục khác.</p>
        <a href="danh-muc.php?slug=<?php echo $slug; ?>" class="btn-primary">Xóa bộ lọc</a>
      </div>
      <?php else: ?>
      <div class="event-grid">
        <?php foreach ($sukien as $event): 
          $thongTin = $sukienController->getThongTinHienThi($event);
        ?>
        <div class="event-card" onclick="location.href='chi-tiet.php?id=<?php echo $thongTin['ma_su_kien']; ?>'">
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
      
      <?php if ($totalPages > 1): ?>
      <div class="pagination">
        <?php if ($page > 1): ?>
          <a href="?slug=<?php echo $slug; ?>&page=<?php echo $page - 1; ?><?php echo !empty($_GET['date']) ? '&date=' . $_GET['date'] : ''; ?><?php echo !empty($_GET['location']) ? '&location=' . $_GET['location'] : ''; ?><?php echo !empty($_GET['price']) ? '&price=' . $_GET['price'] : ''; ?>">
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
            <a href="?slug=<?php echo $slug; ?>&page=<?php echo $i; ?><?php echo !empty($_GET['date']) ? '&date=' . $_GET['date'] : ''; ?><?php echo !empty($_GET['location']) ? '&location=' . $_GET['location'] : ''; ?><?php echo !empty($_GET['price']) ? '&price=' . $_GET['price'] : ''; ?>">
              <?php echo $i; ?>
            </a>
          <?php endif; ?>
        <?php endfor; ?>
        
        <?php if ($page < $totalPages): ?>
          <a href="?slug=<?php echo $slug; ?>&page=<?php echo $page + 1; ?><?php echo !empty($_GET['date']) ? '&date=' . $_GET['date'] : ''; ?><?php echo !empty($_GET['location']) ? '&location=' . $_GET['location'] : ''; ?><?php echo !empty($_GET['price']) ? '&price=' . $_GET['price'] : ''; ?>">
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
  <script>
    document.addEventListener("DOMContentLoaded", () => {
      // Xử lý form lọc
      const filterForm = document.getElementById("filter-form");
      if (filterForm) {
        filterForm.addEventListener("submit", function(e) {
          e.preventDefault();
          
          const formData = new FormData(this);
          const params = new URLSearchParams();
          
          for (const [key, value] of formData.entries()) {
            if (value) {
              params.append(key, value);
            }
          }
          
          // Thêm slug danh mục vào params
          params.append('slug', '<?php echo $slug; ?>');
          
          // Chuyển hướng với các tham số lọc
          window.location.href = `danh-muc.php?${params.toString()}`;
        });
      }
      
      // Xử lý nút xóa bộ lọc
      const clearFiltersBtn = document.getElementById("btn-clear-filters");
      if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener("click", function() {
          window.location.href = `danh-muc.php?slug=<?php echo $slug; ?>`;
        });
      }
      
      // Xử lý xóa từng bộ lọc riêng lẻ
      const filterTags = document.querySelectorAll(".filter-tag i");
      if (filterTags) {
        filterTags.forEach(tag => {
          tag.addEventListener("click", function() {
            const filterName = this.getAttribute("data-filter");
            
            // Lấy các tham số hiện tại
            const currentParams = new URLSearchParams(window.location.search);
            
            // Xóa tham số cần xóa
            currentParams.delete(filterName);
            
            // Giữ lại tham số slug
            const slug = currentParams.get('slug');
            
            // Tạo URL mới
            let newUrl = `danh-muc.php?slug=${slug}`;
            
            // Thêm các tham số khác (nếu có)
            currentParams.forEach((value, key) => {
              if (key !== 'slug') {
                newUrl += `&${key}=${value}`;
              }
            });
            
            // Chuyển hướng đến URL mới
            window.location.href = newUrl;
          });
        });
      }
      
      // Xử lý tìm kiếm
      const searchInput = document.getElementById("search-input");
      const searchButton = document.getElementById("search-button");
      
      if (searchButton && searchInput) {
        searchButton.addEventListener("click", function() {
          const query = searchInput.value.trim();
          if (query) {
            window.location.href = `../../api/search.php?q=${encodeURIComponent(query)}`;
          }
        });
        
        searchInput.addEventListener("keypress", function(e) {
          if (e.key === "Enter") {
            const query = this.value.trim();
            if (query) {
              window.location.href = `../../api/search.php?q=${encodeURIComponent(query)}`;
            }
          }
        });
      }
    });
  </script>
</body>
</html>
