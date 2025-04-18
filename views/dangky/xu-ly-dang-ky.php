<?php
include_once("../../controllers/cnguoidung.php");

// Kiểm tra nếu form đã được submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $ho = isset($_POST['first_name']) ? trim($_POST['first_name']) : '';
    $ten = isset($_POST['last_name']) ? trim($_POST['last_name']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $sdt = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $terms = isset($_POST['terms']) ? true : false;
    
    // Kiểm tra dữ liệu đầu vào
    $errors = array();
    
    if (empty($ho)) {
        $errors[] = "Vui lòng nhập họ của bạn";
    }
    
    if (empty($ten)) {
        $errors[] = "Vui lòng nhập tên của bạn";
    }
    
    if (empty($email)) {
        $errors[] = "Vui lòng nhập email";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email không hợp lệ";
    }
    
    if (empty($sdt)) {
        $errors[] = "Vui lòng nhập số điện thoại";
    } elseif (!preg_match("/^[0-9]{10,11}$/", str_replace(' ', '', $sdt))) {
        $errors[] = "Số điện thoại không hợp lệ";
    }
    
    if (empty($password)) {
        $errors[] = "Vui lòng nhập mật khẩu";
    } elseif (strlen($password) < 6) {
        $errors[] = "Mật khẩu phải có ít nhất 6 ký tự";
    }
    
    if (!$terms) {
        $errors[] = "Bạn phải đồng ý với điều khoản sử dụng";
    }
    
    // Nếu không có lỗi, tiến hành đăng ký
    if (empty($errors)) {
        $controller = new cNguoiDung();
        $result = $controller->xuLyDangKy($ho, $ten, $email, $password, $sdt);
        
        if ($result['success']) {
            // Đăng ký thành công
            session_start();
            $_SESSION['register_success'] = true;
            $_SESSION['register_message'] = "Đăng ký thành công! Vui lòng đăng nhập.";
            
            // Chuyển hướng đến trang đăng nhập
            header("Location: ../dangnhap/login.php");
            exit();
        } else {
            // Đăng ký thất bại
            $errors[] = $result['message'];
        }
    }
    
    // Nếu có lỗi, lưu vào session để hiển thị
    if (!empty($errors)) {
        session_start();
        $_SESSION['register_errors'] = $errors;
        $_SESSION['register_data'] = array(
            'ho' => $ho,
            'ten' => $ten,
            'email' => $email,
            'sdt' => $sdt
        );
        
        // Chuyển hướng về trang đăng ký
        header("Location: register.php");
        exit();
    }
} else {
    // Nếu không phải là POST request, chuyển hướng về trang đăng ký
    header("Location: register.php");
    exit();
}
?>
