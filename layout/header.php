<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>VéNow - Nền tảng mua bán vé sự kiện trực tuyến</title>
  <link rel="stylesheet" href="css/theme.css">
  <link rel="stylesheet" href="css/styles.css">
  <link rel="stylesheet" href="css/home.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<header>
    <div class="header-container">
      <div class="logo">
        <a href="/venow/index.php">
          <img src="images/logo.png" alt="VéNow">
        </a>
      </div>
      <form class="search-bar" action="/venow/views/search/index.php" method="GET">
        <input type="text" id="search-input" name="q" placeholder="Tìm kiếm sự kiện, địa điểm...">
        <button type="submit" id="search-button"><i class="fas fa-search"></i></button>
      </form>
      <nav class="main-nav">
        <ul>
          <li><a href="/venow/views/quanlysukien/taosukien/index.php"class="create-event-btn">Tạo sự kiện</a></li>
          <li><a href="/venow/views/dangnhap/login.php">Đăng nhập</a></li>
          <li><a href="#" class="language-toggle">VI <i class="fas fa-chevron-down"></i></a></li>
          <li><div id="theme-toggle" class="theme-toggle" title="Chuyển đổi chế độ"><i class="fas fa-moon"></i></div></li>
        </ul>
      </nav>
    </div>
  </header>
</body>
</html>
<script src="/venow/js/theme.js"></script>
<script src="/venow/js/main.js"></script>
<script src="/venow/js/search.js"></script>
