<?php
include_once(__DIR__ . "/../models/mnguoidung.php");

class cNguoiDung {
    private $model;
    
    public function __construct() {
        $this->model = new mNguoiDung();
    }
    
    public function dangNhap($email, $password) {
        // Kiểm tra xem email có tồn tại không
        $nguoiDung = $this->model->getNguoiDungByEmail($email);
        
        if (!$nguoiDung) {
            return [
                "success" => false,
                "message" => "Email hoặc mật khẩu không đúng"
            ];
        }
        
        // Kiểm tra mật khẩu
        if (!password_verify($password, $nguoiDung["mat_khau"])) {
            return [
                "success" => false,
                "message" => "Email hoặc mật khẩu không đúng"
            ];
        }
        
        // Kiểm tra trạng thái tài khoản
        if ($nguoiDung["trang_thai"] != "Đã kích hoạt") {
            return [
                "success" => false,
                "message" => "Tài khoản chưa được kích hoạt. Vui lòng kiểm tra email để kích hoạt tài khoản."
            ];
        }
        
        // Đăng nhập thành công, lưu thông tin người dùng vào session
        session_start();
        $_SESSION["user_id"] = $nguoiDung["id"];
        $_SESSION["user_name"] = $nguoiDung["ho_ten"];
        $_SESSION["user_email"] = $nguoiDung["email"];
        $_SESSION["user_role"] = $nguoiDung["vai_tro"];
        
        // Cập nhật thời gian đăng nhập cuối
        $this->model->updateLastLogin($nguoiDung["id"]);
        
        return [
            "success" => true,
            "message" => "Đăng nhập thành công",
            "user" => $nguoiDung
        ];
    }
    
    public function dangKy($hoTen, $email, $soDienThoai, $matKhau) {
        // Kiểm tra xem email đã tồn tại chưa
        $nguoiDung = $this->model->getNguoiDungByEmail($email);
        
        if ($nguoiDung) {
            return [
                "success" => false,
                "message" => "Email đã được sử dụng"
            ];
        }
        
        // Kiểm tra xem số điện thoại đã tồn tại chưa
        $nguoiDung = $this->model->getNguoiDungByPhone($soDienThoai);
        
        if ($nguoiDung) {
            return [
                "success" => false,
                "message" => "Số điện thoại đã được sử dụng"
            ];
        }
        
        // Mã hóa mật khẩu
        $hashedPassword = password_hash($matKhau, PASSWORD_DEFAULT);
        
        // Tạo mã kích hoạt
        $activationCode = bin2hex(random_bytes(16));
        
        // Thêm người dùng mới
        $result = $this->model->themNguoiDung($hoTen, $email, $soDienThoai, $hashedPassword, $activationCode);
        
        if (!$result) {
            return [
                "success" => false,
                "message" => "Đăng ký thất bại. Vui lòng thử lại sau."
            ];
        }
        
        // Gửi email kích hoạt
        $this->guiEmailKichHoat($email, $hoTen, $activationCode);
        
        return [
            "success" => true,
            "message" => "Đăng ký thành công. Vui lòng kiểm tra email để kích hoạt tài khoản."
        ];
    }
    
    public function kichHoatTaiKhoan($email, $code) {
        // Kiểm tra xem email và mã kích hoạt có hợp lệ không
        $nguoiDung = $this->model->getNguoiDungByEmailAndActivationCode($email, $code);
        
        if (!$nguoiDung) {
            return [
                "success" => false,
                "message" => "Liên kết kích hoạt không hợp lệ hoặc đã hết hạn"
            ];
        }
        
        // Kích hoạt tài khoản
        $result = $this->model->kichHoatTaiKhoan($nguoiDung["id"]);
        
        if (!$result) {
            return [
                "success" => false,
                "message" => "Kích hoạt tài khoản thất bại. Vui lòng thử lại sau."
            ];
        }
        
        return [
            "success" => true,
            "message" => "Kích hoạt tài khoản thành công. Bạn có thể đăng nhập ngay bây giờ."
        ];
    }
    
    public function quenMatKhau($email) {
        // Kiểm tra xem email có tồn tại không
        $nguoiDung = $this->model->getNguoiDungByEmail($email);
        
        if (!$nguoiDung) {
            return [
                "success" => false,
                "message" => "Email không tồn tại trong hệ thống"
            ];
        }
        
        // Tạo mã đặt lại mật khẩu
        $resetCode = bin2hex(random_bytes(16));
        $resetExpiry = date('Y-m-d H:i:s', strtotime('+1 hour')); // Hết hạn sau 1 giờ
        
        // Lưu mã đặt lại mật khẩu
        $result = $this->model->taoYeuCauDatLaiMatKhau($nguoiDung["id"], $resetCode, $resetExpiry);
        
        if (!$result) {
            return [
                "success" => false,
                "message" => "Không thể tạo yêu cầu đặt lại mật khẩu. Vui lòng thử lại sau."
            ];
        }
        
        // Gửi email đặt lại mật khẩu
        $this->guiEmailDatLaiMatKhau($email, $nguoiDung["ho_ten"], $resetCode);
        
        return [
            "success" => true,
            "message" => "Yêu cầu đặt lại mật khẩu đã được gửi đến email của bạn."
        ];
    }
    
    public function datLaiMatKhau($email, $code, $newPassword) {
        // Kiểm tra xem email và mã đặt lại mật khẩu có hợp lệ không
        $nguoiDung = $this->model->getNguoiDungByEmailAndResetCode($email, $code);
        
        if (!$nguoiDung) {
            return [
                "success" => false,
                "message" => "Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn"
            ];
        }
        
        // Kiểm tra xem mã đặt lại mật khẩu đã hết hạn chưa
        if (strtotime($nguoiDung["reset_expiry"]) < time()) {
            return [
                "success" => false,
                "message" => "Liên kết đặt lại mật khẩu đã hết hạn"
            ];
        }
        
        // Mã hóa mật khẩu mới
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Cập nhật mật khẩu
        $result = $this->model->capNhatMatKhau($nguoiDung["id"], $hashedPassword);
        
        if (!$result) {
            return [
                "success" => false,
                "message" => "Đặt lại mật khẩu thất bại. Vui lòng thử lại sau."
            ];
        }
        
        // Xóa mã đặt lại mật khẩu
        $this->model->xoaYeuCauDatLaiMatKhau($nguoiDung["id"]);
        
        return [
            "success" => true,
            "message" => "Đặt lại mật khẩu thành công. Bạn có thể đăng nhập với mật khẩu mới."
        ];
    }
    
    public function doiMatKhau($userId, $currentPassword, $newPassword) {
        // Lấy thông tin người dùng
        $nguoiDung = $this->model->getNguoiDungById($userId);
        
        if (!$nguoiDung) {
            return [
                "success" => false,
                "message" => "Người dùng không tồn tại"
            ];
        }
        
        // Kiểm tra mật khẩu hiện tại
        if (!password_verify($currentPassword, $nguoiDung["mat_khau"])) {
            return [
                "success" => false,
                "message" => "Mật khẩu hiện tại không đúng"
            ];
        }
        
        // Mã hóa mật khẩu mới
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        // Cập nhật mật khẩu
        $result = $this->model->capNhatMatKhau($userId, $hashedPassword);
        
        if (!$result) {
            return [
                "success" => false,
                "message" => "Đổi mật khẩu thất bại. Vui lòng thử lại sau."
            ];
        }
        
        return [
            "success" => true,
            "message" => "Đổi mật khẩu thành công."
        ];
    }
    
    public function dangXuat() {
        // Xóa tất cả dữ liệu session
        session_start();
        session_unset();
        session_destroy();
        
        // Xóa cookie ghi nhớ đăng nhập nếu có
        if (isset($_COOKIE["remember_user"])) {
            setcookie("remember_user", "", time() - 3600, "/");
        }
        
        return [
            "success" => true,
            "message" => "Đăng xuất thành công"
        ];
    }
    
    private function guiEmailKichHoat($email, $hoTen, $activationCode) {
        // Tạo liên kết kích hoạt
        $activationLink = "http://" . $_SERVER["HTTP_HOST"] . "/venow/views/dangnhap/kich-hoat.php?email=" . urlencode($email) . "&code=" . $activationCode;
        
        // Nội dung email
        $subject = "Kích hoạt tài khoản VéNow";
        $message = "
        <html>
        <head>
            <title>Kích hoạt tài khoản VéNow</title>
        </head>
        <body>
            <h2>Xin chào $hoTen,</h2>
            <p>Cảm ơn bạn đã đăng ký tài khoản tại VéNow.</p>
            <p>Vui lòng nhấp vào liên kết dưới đây để kích hoạt tài khoản của bạn:</p>
            <p><a href='$activationLink'>Kích hoạt tài khoản</a></p>
            <p>Hoặc bạn có thể sao chép và dán liên kết sau vào trình duyệt:</p>
            <p>$activationLink</p>
            <p>Liên kết này sẽ hết hạn sau 24 giờ.</p>
            <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
            <p>Trân trọng,<br>Đội ngũ VéNow</p>
        </body>
        </html>
        ";
        
        // Headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: VéNow <no-reply@venow.vn>" . "\r\n";
        
        // Gửi email
        mail($email, $subject, $message, $headers);
    }
    
    private function guiEmailDatLaiMatKhau($email, $hoTen, $resetCode) {
        // Tạo liên kết đặt lại mật khẩu
        $resetLink = "http://" . $_SERVER["HTTP_HOST"] . "/venow/views/dangnhap/dat-lai-mat-khau.php?email=" . urlencode($email) . "&code=" . $resetCode;
        
        // Nội dung email
        $subject = "Đặt lại mật khẩu VéNow";
        $message = "
        <html>
        <head>
            <title>Đặt lại mật khẩu VéNow</title>
        </head>
        <body>
            <h2>Xin chào $hoTen,</h2>
            <p>Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
            <p>Vui lòng nhấp vào liên kết dưới đây để đặt lại mật khẩu:</p>
            <p><a href='$resetLink'>Đặt lại mật khẩu</a></p>
            <p>Hoặc bạn có thể sao chép và dán liên kết sau vào trình duyệt:</p>
            <p>$resetLink</p>
            <p>Liên kết này sẽ hết hạn sau 1 giờ.</p>
            <p>Nếu bạn không thực hiện yêu cầu này, vui lòng bỏ qua email này.</p>
            <p>Trân trọng,<br>Đội ngũ VéNow</p>
        </body>
        </html>
        ";
        
        // Headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: VéNow <no-reply@venow.vn>" . "\r\n";
        
        // Gửi email
        mail($email, $subject, $message, $headers);
    }
}
?>
