<?php
include_once("../controllers/csukien.php");

// Lấy từ khóa tìm kiếm
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

// Nếu không có từ khóa, trả về mảng rỗng
if (empty($keyword)) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

// Khởi tạo controller
$sukienController = new cSuKien();

// Tìm kiếm sự kiện
$results = $sukienController->timKiemSuKien($keyword);

// Chuẩn bị dữ liệu trả về
$data = [];
foreach ($results as $sukien) {
    $data[] = $sukienController->getThongTinHienThi($sukien);
}

// Trả về kết quả dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($data);
?>
