<?php
include_once(__DIR__ . "/mketnoi.php");

class mNguoiDung {
    private $db;
    
    public function __construct() {
        $this->db = new ketnoi();
    }
    
    public function getNguoiDungByEmail($email) {
        $conn = $this->db->connect();
        
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function getNguoiDungByPhone($phone) {
        $conn = $this->db->connect();
        
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE so_dien_thoai = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function getNguoiDungById($id) {
        $conn = $this->db->connect();
        
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function getNguoiDungByEmailAndActivationCode($email, $code) {
        $conn = $this->db->connect();
        
        $stmt = $conn->prepare("SELECT * FROM nguoi_dung WHERE email = ? AND ma_kich_hoat = ?");
        $stmt->bind_param("ss", $email, $code);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function getNguoiDungByEmailAndResetCode($email, $code) {
        $conn = $this->db->connect();
        
        $stmt = $conn->prepare("
            SELECT nd.*, dr.ma_dat_lai, dr.thoi_gian_het_han as reset_expiry
            FROM nguoi_dung nd
            JOIN dat_lai_mat_khau dr ON nd.id = dr.nguoi_dung_id
            WHERE nd.email = ? AND dr.ma_dat_lai = ?
        ");
        $stmt->bind_param("ss", $email, $code);
        $stmt->execute();
        
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    public function themNguoiDung($hoTen, $email, $soDienThoai, $matKhau, $maKichHoat) {
        $conn = $this->db->connect();
        
        $vaiTro = "Người dùng";
        $trangThai = "Chưa kích hoạt";
        $ngayTao = date("Y-m-d H:i:s");
        
        $stmt = $conn->prepare("
            INSERT INTO nguoi_dung (ho_ten, email, so_dien_thoai, mat_khau, vai_tro, trang_thai, ma_kich_hoat, ngay_tao)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssssss", $hoTen, $email, $soDienThoai, $matKhau, $vaiTro, $trangThai, $maKichHoat, $ngayTao);
        
        return $stmt->execute();
    }
    
    public function kichHoatTaiKhoan($id) {
        $conn = $this->db->connect();
        
        $trangThai = "Đã kích hoạt";
        $maKichHoat = null;
        
        $stmt = $conn->prepare("UPDATE nguoi_dung SET trang_thai = ?, ma_kich_hoat = ? WHERE id = ?");
        $stmt->bind_param("ssi", $trangThai, $maKichHoat, $id);
        
        return $stmt->execute();
    }
    
    public function updateLastLogin($id) {
        $conn = $this->db->connect();
        
        $lastLogin = date("Y-m-d H:i:s");
        
        $stmt = $conn->prepare("UPDATE nguoi_dung SET dang_nhap_cuoi = ? WHERE id = ?");
        $stmt->bind_param("si", $lastLogin, $id);
        
        return $stmt->execute();
    }
    
    public function taoYeuCauDatLaiMatKhau($userId, $resetCode, $resetExpiry) {
        $conn = $this->db->connect();
        
        // Xóa yêu cầu đặt lại mật khẩu cũ nếu có
        $this->xoaYeuCauDatLaiMatKhau($userId);
        
        // Tạo yêu cầu mới
        $stmt = $conn->prepare("
            INSERT INTO dat_lai_mat_khau (nguoi_dung_id, ma_dat_lai, thoi_gian_het_han)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iss", $userId, $resetCode, $resetExpiry);
        
        return $stmt->execute();
    }
    
    public function xoaYeuCauDatLaiMatKhau($userId) {
        $conn = $this->db->connect();
        
        $stmt = $conn->prepare("DELETE FROM dat_lai_mat_khau WHERE nguoi_dung_id = ?");
        $stmt->bind_param("i", $userId);
        
        return $stmt->execute();
    }
    
    public function capNhatMatKhau($userId, $newPassword) {
        $conn = $this->db->connect();
        
        $stmt = $conn->prepare("UPDATE nguoi_dung SET mat_khau = ? WHERE id = ?");
        $stmt->bind_param("si", $newPassword, $userId);
        
        return $stmt->execute();
    }
}
?>
