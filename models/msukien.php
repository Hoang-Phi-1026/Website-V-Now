<?php
include_once(__DIR__ . "/mketnoi.php");

class mSuKien {
    private $conn;
    
    public function __construct() {
        $ketnoi = new ketnoi();
        $this->conn = $ketnoi->ketnoi();
    }
    
    /**
     * Lấy danh sách sự kiện
     * @param string $where Điều kiện WHERE (nếu có)
     * @param string $order Điều kiện ORDER BY (nếu có)
     * @param int $limit Số lượng sự kiện tối đa
     * @param int $offset Vị trí bắt đầu
     * @return array Danh sách sự kiện
     */
    public function getDanhSachSuKien($where = "", $order = "ngay_bat_dau ASC", $limit = 0, $offset = 0) {
        $sql = "SELECT sk.*, dm.ten_danh_muc, dm.slug, tc.ten_to_chuc, dd.ten_dia_diem, dd.dia_chi, dd.thanh_pho, dd.tinh 
                FROM su_kien sk
                LEFT JOIN danh_muc_su_kien dm ON sk.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN to_chuc tc ON sk.ma_to_chuc = tc.ma_to_chuc
                LEFT JOIN dia_diem dd ON sk.ma_dia_diem = dd.ma_dia_diem";
        
        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }
        
        if (!empty($order)) {
            $sql .= " ORDER BY " . $order;
        }
        
        if ($limit > 0) {
            $sql .= " LIMIT " . $limit;
            
            if ($offset > 0) {
                $sql .= " OFFSET " . $offset;
            }
        }
        
        $result = $this->conn->query($sql);
        $sukien = array();
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sukien[] = $row;
            }
        }
        
        return $sukien;
    }
    
    /**
     * Lấy sự kiện nổi bật
     * @param int $limit Số lượng sự kiện tối đa
     * @return array Danh sách sự kiện nổi bật
     */
    public function getSuKienNoiBat($limit = 4) {
        return $this->getDanhSachSuKien("featured = TRUE AND sk.trang_thai = 'Đã duyệt'", "ngay_bat_dau ASC", $limit);
    }
    
    /**
     * Lấy sự kiện sắp diễn ra
     * @param int $limit Số lượng sự kiện tối đa
     * @return array Danh sách sự kiện sắp diễn ra
     */
    public function getSuKienSapDienRa($limit = 4) {
        $today = date('Y-m-d H:i:s');
        return $this->getDanhSachSuKien("sk.ngay_bat_dau > '$today' AND sk.trang_thai = 'Đã duyệt'", "sk.ngay_bat_dau ASC", $limit);
    }
    
    /**
     * Lấy chi tiết sự kiện theo ID
     * @param int $ma_su_kien ID của sự kiện
     * @return array|null Thông tin chi tiết sự kiện hoặc null nếu không tìm thấy
     */
    public function getChiTietSuKien($ma_su_kien) {
        $sql = "SELECT sk.*, dm.ten_danh_muc, dm.slug, tc.ten_to_chuc, tc.logo as logo_to_chuc, dd.ten_dia_diem, dd.dia_chi, dd.thanh_pho, dd.tinh, dd.toa_do_lat, dd.toa_do_lng
                FROM su_kien sk
                LEFT JOIN danh_muc_su_kien dm ON sk.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN to_chuc tc ON sk.ma_to_chuc = tc.ma_to_chuc
                LEFT JOIN dia_diem dd ON sk.ma_dia_diem = dd.ma_dia_diem
                WHERE sk.ma_su_kien = ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $ma_su_kien);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    
    /**
     * Lấy danh sách hình ảnh của sự kiện
     * @param int $ma_su_kien ID của sự kiện
     * @return array Danh sách hình ảnh
     */
    public function getHinhAnhSuKien($ma_su_kien) {
        $sql = "SELECT * FROM hinh_anh_su_kien WHERE ma_su_kien = ? ORDER BY thu_tu ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $ma_su_kien);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $hinhanh = array();
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $hinhanh[] = $row;
            }
        }
        
        return $hinhanh;
    }
    
    /**
     * Lấy danh sách loại vé của sự kiện
     * @param int $ma_su_kien ID của sự kiện
     * @return array Danh sách loại vé
     */
    public function getLoaiVeSuKien($ma_su_kien) {
        $sql = "SELECT lv.*, hm.ten_hang_muc 
                FROM loai_ve lv
                LEFT JOIN hang_muc_ve hm ON lv.ma_hang_muc = hm.ma_hang_muc
                WHERE lv.ma_su_kien = ?
                ORDER BY lv.gia_ve ASC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $ma_su_kien);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $loaive = array();
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $loaive[] = $row;
            }
        }
        
        return $loaive;
    }
    
    /**
     * Lấy danh sách sự kiện liên quan
     * @param int $ma_su_kien ID của sự kiện hiện tại
     * @param int $limit Số lượng sự kiện tối đa
     * @return array Danh sách sự kiện liên quan
     */
    public function getSuKienLienQuan($ma_su_kien, $limit = 3) {
        // Lấy thông tin danh mục của sự kiện hiện tại
        $sql = "SELECT ma_danh_muc FROM su_kien WHERE ma_su_kien = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $ma_su_kien);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $ma_danh_muc = $row['ma_danh_muc'];
        
            // Lấy các sự kiện cùng danh mục
            return $this->getDanhSachSuKien("sk.ma_danh_muc = $ma_danh_muc AND sk.ma_su_kien != $ma_su_kien AND sk.trang_thai = 'Đã duyệt'", "RAND()", $limit);
        }
        
        return array();
    }
    
    /**
     * Tìm kiếm sự kiện
     * @param string $keyword Từ khóa tìm kiếm
     * @param int $limit Số lượng sự kiện tối đa
     * @return array Danh sách sự kiện tìm thấy
     */
    public function timKiemSuKien($keyword, $limit = 10) {
        $keyword = "%$keyword%";
        
        $sql = "SELECT sk.*, dm.ten_danh_muc, dm.slug, tc.ten_to_chuc, dd.ten_dia_diem, dd.dia_chi, dd.thanh_pho, dd.tinh 
                FROM su_kien sk
                LEFT JOIN danh_muc_su_kien dm ON sk.ma_danh_muc = dm.ma_danh_muc
                LEFT JOIN to_chuc tc ON sk.ma_to_chuc = tc.ma_to_chuc
                LEFT JOIN dia_diem dd ON sk.ma_dia_diem = dd.ma_dia_diem
                WHERE (sk.ten_su_kien LIKE ? OR sk.mo_ta_ngan LIKE ? OR dd.ten_dia_diem LIKE ? OR dd.thanh_pho LIKE ?)
                AND sk.trang_thai = 'Đã duyệt'
                ORDER BY sk.ngay_bat_dau ASC
                LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssssi", $keyword, $keyword, $keyword, $keyword, $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $sukien = array();
        
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $sukien[] = $row;
            }
        }
        
        return $sukien;
    }
    
    /**
     * Lấy danh sách danh mục sự kiện
     * @return array Danh sách danh mục
     */
    public function getDanhMucSuKien() {
        $sql = "SELECT * FROM danh_muc_su_kien ORDER BY thu_tu ASC";
        $result = $this->conn->query($sql);
        
        $danhmuc = array();
        
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $danhmuc[] = $row;
            }
        }
        
        return $danhmuc;
    }
    
    /**
     * Định dạng ngày giờ sự kiện
     * @param string $ngay_bat_dau Ngày bắt đầu
     * @param string $ngay_ket_thuc Ngày kết thúc
     * @return array Thông tin ngày giờ đã định dạng
     */
    public function dinhDangNgayGio($ngay_bat_dau, $ngay_ket_thuc) {
        $start = new DateTime($ngay_bat_dau);
        $end = new DateTime($ngay_ket_thuc);
        
        $thu_start = $this->getThuTiengViet($start->format('N'));
        $thu_end = $this->getThuTiengViet($end->format('N'));
        
        $ngay_start = $start->format('d/m/Y');
        $ngay_end = $end->format('d/m/Y');
        
        $gio_start = $start->format('H:i');
        $gio_end = $end->format('H:i');
        
        $same_day = $start->format('Y-m-d') === $end->format('Y-m-d');
        
        $result = array(
            'thu_start' => $thu_start,
            'thu_end' => $thu_end,
            'ngay_start' => $ngay_start,
            'ngay_end' => $ngay_end,
            'gio_start' => $gio_start,
            'gio_end' => $gio_end,
            'same_day' => $same_day,
            'display' => $same_day ? 
                "$thu_start, $ngay_start, $gio_start - $gio_end" : 
                "$thu_start, $ngay_start, $gio_start - $thu_end, $ngay_end, $gio_end"
        );
        
        return $result;
    }
    
    /**
     * Lấy tên thứ trong tuần bằng tiếng Việt
     * @param int $thu Số thứ tự (1-7)
     * @return string Tên thứ bằng tiếng Việt
     */
    private function getThuTiengViet($thu) {
        $thu_array = array(
            1 => 'Thứ Hai',
            2 => 'Thứ Ba',
            3 => 'Thứ Tư',
            4 => 'Thứ Năm',
            5 => 'Thứ Sáu',
            6 => 'Thứ Bảy',
            7 => 'Chủ Nhật'
        );
        
        return $thu_array[$thu] ?? '';
    }
    
    /**
     * Định dạng giá tiền
     * @param float $price Giá tiền
     * @return string Giá tiền đã định dạng
     */
    public function dinhDangGiaTien($price) {
        return number_format($price, 0, ',', '.') . ' ₫';
    }
}
?>
