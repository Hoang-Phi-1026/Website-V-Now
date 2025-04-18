<?php
include_once("../controllers/csukien.php");

// Lấy từ khóa tìm kiếm
$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';

// Nếu không có từ khóa hoặc từ khóa quá ngắn, trả về mảng rỗng
if (empty($keyword) || strlen($keyword) < 2) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

// Khởi tạo controller
$sukienController = new cSuKien();

// Tìm kiếm gợi ý sự kiện (giới hạn 5 kết quả)
$results = $sukienController->timKiemSuKien($keyword, 5);

// Chuẩn bị dữ liệu trả về
$suggestions = [];
foreach ($results as $sukien) {
    $suggestions[] = [
        'id' => $sukien['ma_su_kien'],
        'text' => $sukien['ten_su_kien'],
        'type' => 'event'
    ];
}

// Thêm gợi ý danh mục nếu có từ khóa phù hợp
$categories = $sukienController->getDanhMucSuKien();
foreach ($categories as $category) {
    if (stripos($category['ten_danh_muc'], $keyword) !== false) {
        $suggestions[] = [
            'id' => $category['ma_danh_muc'],
            'text' => $category['ten_danh_muc'],
            'type' => 'category'
        ];
    }
}

// Thêm gợi ý địa điểm phổ biến
$locations = ['Hồ Chí Minh', 'Hà Nội', 'Đà Nẵng'];
foreach ($locations as $location) {
    if (stripos($location, $keyword) !== false) {
        $suggestions[] = [
            'id' => strtolower(str_replace(' ', '-', $location)),
            'text' => $location,
            'type' => 'location'
        ];
    }
}

// Giới hạn tổng số gợi ý trả về là 10
$suggestions = array_slice($suggestions, 0, 10);

// Trả về kết quả dưới dạng JSON
header('Content-Type: application/json');
echo json_encode($suggestions);
?>
