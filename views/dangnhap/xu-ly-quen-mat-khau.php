<?php
include_once(__DIR__ . "/../../controllers/cnguoidung.php");

session_start();

// Kiểm tra nếu form đã được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    
    // Validate dữ liệu
    if (empty($email)) {
        $_SESSION['reset_message'] = "Vui lòng nhập email";
        $_SESSION['reset_success'] = false;
        header("Location: quen-mat-khau.php");
        exit();
    }
    
    // Kiểm tra định dạng email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['reset_message'] = "Email không hợp lệ";
        $_SESSION['reset_success'] = false;
        header("Location: quen-mat-khau.php");
        exit();
    }
    
    // Xử lý yêu cầu đặt lại mật khẩu
    $nguoiDungController = new cNguoiDung();
    $result = $nguoiDungController->quenMatKhau($email);
    
    // Lưu kết quả vào session
    $_SESSION['reset_message'] = $result["message"];
    $_SESSION['reset_success'] = $result["success"];
    
    // Chuyển hướng trở lại trang quên mật khẩu
    header("Location: quen-mat-khau.php");
    exit();
} else {
    // Nếu không phải là POST request, chuyển hướng về trang quên mật khẩu
    header("Location: quen-mat-khau.php");
    exit();
}
?>
