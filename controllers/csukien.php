<?php
include_once(__DIR__ . "/../models/msukien.php");

class cSuKien {
    private $model;
    
    public function __construct() {
        $this->model = new mSuKien();
    }
    
    /**
     * Lấy danh sách sự kiện theo điều kiện
     * @param string $where Điều kiện WHERE (nếu có)
     * @param string $order Điều kiện ORDER BY (nếu có)
     * @param int $limit Số lượng sự kiện tối đa
     * @param int $offset Vị trí bắt đầu
     * @return array Danh sách sự kiện
     */
    public function getDanhSachSuKien($where = "", $order = "ngay_bat_dau ASC", $limit = 0, $offset = 0) {
        return $this->model->getDanhSachSuKien($where, $order, $limit, $offset);
    }
    
    /**
     * Lấy danh sách sự kiện nổi bật
     * @param int $limit Số lượng sự kiện tối đa
     * @return array Danh sách sự kiện nổi bật
     */
    public function getSuKienNoiBat($limit = 4) {
        return $this->model->getSuKienNoiBat($limit);
    }
    
    /**
     * Lấy danh sách sự kiện sắp diễn ra
     * @param int $limit Số lượng sự kiện tối đa
     * @return array Danh sách sự kiện sắp diễn ra
     */
    public function getSuKienSapDienRa($limit = 4) {
        return $this->model->getSuKienSapDienRa($limit);
    }
    
    /**
     * Lấy chi tiết sự kiện theo ID
     * @param int $ma_su_kien ID của sự kiện
     * @return array|null Thông tin chi tiết sự kiện hoặc null nếu không tìm thấy
     */
    public function getChiTietSuKien($ma_su_kien) {
        return $this->model->getChiTietSuKien($ma_su_kien);
    }
    
    /**
     * Lấy danh sách hình ảnh của sự kiện
     * @param int $ma_su_kien ID của sự kiện
     * @return array Danh sách hình ảnh
     */
    public function getHinhAnhSuKien($ma_su_kien) {
        return $this->model->getHinhAnhSuKien($ma_su_kien);
    }
    
    /**
     * Lấy danh sách loại vé của sự kiện
     * @param int $ma_su_kien ID của sự kiện
     * @return array Danh sách loại vé
     */
    public function getLoaiVeSuKien($ma_su_kien) {
        return $this->model->getLoaiVeSuKien($ma_su_kien);
    }
    
    /**
     * Lấy danh sách sự kiện liên quan
     * @param int $ma_su_kien ID của sự kiện hiện tại
     * @param int $limit Số lượng sự kiện tối đa
     * @return array Danh sách sự kiện liên quan
     */
    public function getSuKienLienQuan($ma_su_kien, $limit = 3) {
        return $this->model->getSuKienLienQuan($ma_su_kien, $limit);
    }
    
    /**
     * Tìm kiếm sự kiện
     * @param string $keyword Từ khóa tìm kiếm
     * @param int $limit Số lượng sự kiện tối đa
     * @return array Danh sách sự kiện tìm thấy
     */
    public function timKiemSuKien($keyword, $limit = 10) {
        return $this->model->timKiemSuKien($keyword, $limit);
    }
    
    /**
     * Lấy danh sách danh mục sự kiện
     * @return array Danh sách danh mục
     */
    public function getDanhMucSuKien() {
        return $this->model->getDanhMucSuKien();
    }
    
    /**
     * Định dạng ngày giờ sự kiện
     * @param string $ngay_bat_dau Ngày bắt đầu
     * @param string $ngay_ket_thuc Ngày kết thúc
     * @return array Thông tin ngày giờ đã định dạng
     */
    public function dinhDangNgayGio($ngay_bat_dau, $ngay_ket_thuc) {
        return $this->model->dinhDangNgayGio($ngay_bat_dau, $ngay_ket_thuc);
    }
    
    /**
     * Định dạng giá tiền
     * @param float $price Giá tiền
     * @return string Giá tiền đã định dạng
     */
    public function dinhDangGiaTien($price) {
        return $this->model->dinhDangGiaTien($price);
    }
    
    /**
     * Lấy thông tin hiển thị cho sự kiện
     * @param array $sukien Thông tin sự kiện
     * @return array Thông tin hiển thị
     */
    public function getThongTinHienThi($sukien) {
        // Định dạng ngày giờ
        $ngay_gio = $this->dinhDangNgayGio($sukien['ngay_bat_dau'], $sukien['ngay_ket_thuc']);
        
        // Lấy tháng và ngày cho hiển thị
        $start_date = new DateTime($sukien['ngay_bat_dau']);
        $month = $start_date->format('n'); // Tháng không có số 0 ở đầu
        $day = $start_date->format('j'); // Ngày không có số 0 ở đầu
        
        // Định dạng giá vé
        $gia_ve_thap_nhat = $this->dinhDangGiaTien($sukien['gia_ve_thap_nhat']);
        $gia_ve_cao_nhat = $this->dinhDangGiaTien($sukien['gia_ve_cao_nhat']);
        $gia_ve = $gia_ve_thap_nhat;
        
        if ($sukien['gia_ve_thap_nhat'] != $sukien['gia_ve_cao_nhat']) {
            $gia_ve = $gia_ve_thap_nhat . ' - ' . $gia_ve_cao_nhat;
        }
        
        return array(
            'ma_su_kien' => $sukien['ma_su_kien'],
            'ten_su_kien' => $sukien['ten_su_kien'],
            'slug' => $sukien['slug'],
            'hinh_anh' => $sukien['hinh_anh_chinh'],
            'dia_diem' => $sukien['ten_dia_diem'],
            'dia_chi' => $sukien['dia_chi'] . ', ' . $sukien['thanh_pho'],
            'ngay_gio' => $ngay_gio,
            'month' => 'Tháng ' . $month,
            'day' => $day,
            'gia_ve' => $gia_ve
        );
    }
}
?>
