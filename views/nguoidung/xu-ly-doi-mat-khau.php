<?php
include_once(__DIR__ . "/../../controllers/cnguoidung.php");

session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    header("Location: /venow/views/dangnhap/login.php");
    exit();
}

// Kiểm tra nếu form đã được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $currentPassword = isset($_POST["current_password"]) ? $_POST["current_password"] : "";
    $newPassword = isset($_POST["new_password"]) ? $_POST["new_password"] : "";
    $confirmPassword = isset($_POST["confirm_password"]) ? $_POST["confirm_password"] : "";
    
    // Validate dữ liệu
    $errors = [];
    
    if (empty($currentPassword)) {
        $errors[] = "Vui lòng nhập mật khẩu hiện tại";
    }
    
    if (empty($newPassword)) {
        $errors[] = "Vui lòng nhập mật khẩu mới";
    } elseif (strlen($newPassword) < 8) {
        $errors[] = "Mật khẩu mới phải có ít nhất 8 ký tự";
    }
    
    if (empty($confirmPassword)) {
        $errors[] = "Vui lòng xác nhận mật khẩu mới";
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = "Mật khẩu xác nhận không khớp";
    }
    
    // Nếu có lỗi
    if (!empty($errors)) {
        $_SESSION['password_message'] = $errors[0];
        $_SESSION['password_success'] = false;
        header("Location: doi-mat-khau.php");
        exit();
    }
    
    // Xử lý đổi mật khẩu
    $nguoiDungController = new cNguoiDung();
    $result = $nguoiDungController->doiMatKhau($_SESSION['user_id'], $currentPassword, $newPassword);
    
    // Lưu kết quả vào session
    $_SESSION['password_message'] = $result["message"];
    $_SESSION['password_success'] = $result["success"];
    
    // Chuyển hướng trở lại trang đổi mật khẩu
    header("Location: doi-mat-khau.php");
    exit();
} else {
    // Nếu không phải là POST request, chuyển hướng về trang đổi mật khẩu
    header("Location: doi-mat-khau.php");
    exit();
}
?>
