<?php
include_once(__DIR__ . "/../../controllers/cnguoidung.php");

session_start();

// Kiểm tra nếu form đã được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $code = isset($_POST["code"]) ? trim($_POST["code"]) : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";
    $confirmPassword = isset($_POST["confirm_password"]) ? $_POST["confirm_password"] : "";
    
    // Validate dữ liệu
    $errors = [];
    
    if (empty($email) || empty($code)) {
        $errors[] = "Liên kết đặt lại mật khẩu không hợp lệ";
    }
    
    if (empty($password)) {
        $errors[] = "Vui lòng nhập mật khẩu mới";
    } elseif (strlen($password) < 8) {
        $errors[] = "Mật khẩu phải có ít nhất 8 ký tự";
    }
    
    if (empty($confirmPassword)) {
        $errors[] = "Vui lòng xác nhận mật khẩu";
    } elseif ($password !== $confirmPassword) {
        $errors[] = "Mật khẩu xác nhận không khớp";
    }
    
    // Nếu có lỗi
    if (!empty($errors)) {
        $_SESSION['reset_message'] = $errors[0];
        $_SESSION['reset_success'] = false;
        header("Location: dat-lai-mat-khau.php?email=" . urlencode($email) . "&code=" . urlencode($code));
        exit();
    }
    
    // Xử lý đặt lại mật khẩu
    $nguoiDungController = new cNguoiDung();
    $result = $nguoiDungController->datLaiMatKhau($email, $code, $password);
    
    if ($result["success"]) {
        // Đặt lại mật khẩu thành công
        $_SESSION['login_message'] = $result["message"];
        $_SESSION['login_success'] = true;
        header("Location: login.php");
        exit();
    } else {
        // Đặt lại mật khẩu thất bại
        $_SESSION['reset_message'] = $result["message"];
        $_SESSION['reset_success'] = false;
        header("Location: dat-lai-mat-khau.php?email=" . urlencode($email) . "&code=" . urlencode($code));
        exit();
    }
} else {
    // Nếu không phải là POST request, chuyển hướng về trang đặt lại mật khẩu
    header("Location: quen-mat-khau.php");
    exit();
}
?>
