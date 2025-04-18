<?php
include_once(__DIR__ . "/../../controllers/cnguoidung.php");

session_start();

// Kiểm tra nếu form đã được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $email = isset($_POST["email"]) ? trim($_POST["email"]) : "";
    $password = isset($_POST["password"]) ? $_POST["password"] : "";
    $remember = isset($_POST["remember"]) ? true : false;
    
    // Khởi tạo mảng lỗi
    $errors = [];
    
    // Validate dữ liệu
    if (empty($email)) {
        $errors[] = "Vui lòng nhập email hoặc số điện thoại";
    }
    
    if (empty($password)) {
        $errors[] = "Vui lòng nhập mật khẩu";
    }
    
    // Nếu không có lỗi, tiến hành đăng nhập
    if (empty($errors)) {
        $nguoiDungController = new cNguoiDung();
        $result = $nguoiDungController->dangNhap($email, $password);
        
        if ($result["success"]) {
            // Đăng nhập thành công
            
            // Nếu người dùng chọn "Ghi nhớ đăng nhập"
            if ($remember) {
                // Tạo cookie lưu thông tin đăng nhập trong 30 ngày
                setcookie("remember_user", $email, time() + (86400 * 30), "/");
            }
            
            // Chuyển hướng đến trang chủ hoặc trang trước đó
            $redirect = isset($_SESSION["redirect_after_login"]) ? $_SESSION["redirect_after_login"] : "/venow/index.php";
            unset($_SESSION["redirect_after_login"]);
            
            header("Location: " . $redirect);
            exit();
        } else {
            // Đăng nhập thất bại
            $errors[] = $result["message"];
            
            // Lưu email để điền lại vào form
            $_SESSION["login_email"] = $email;
            
            // Lưu thông báo lỗi vào session
            $_SESSION["login_errors"] = $errors;
            
            // Chuyển hướng trở lại trang đăng nhập
            header("Location: login.php");
            exit();
        }
    } else {
        // Có lỗi validate
        
        // Lưu email để điền lại vào form
        $_SESSION["login_email"] = $email;
        
        // Lưu thông báo lỗi vào session
        $_SESSION["login_errors"] = $errors;
        
        // Chuyển hướng trở lại trang đăng nhập
        header("Location: login.php");
        exit();
    }
} else {
    // Nếu không phải là POST request, chuyển hướng về trang đăng nhập
    header("Location: login.php");
    exit();
}
?>
