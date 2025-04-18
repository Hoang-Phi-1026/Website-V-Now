<?php
include_once(__DIR__ . "/../../controllers/cnguoidung.php");

// Xử lý đăng xuất
$nguoiDungController = new cNguoiDung();
$result = $nguoiDungController->dangXuat();

// Chuyển hướng đến trang chủ
header("Location: /venow/index.php");
exit();
?>
