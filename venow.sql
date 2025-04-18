-- Tạo cơ sở dữ liệu
DROP DATABASE IF EXISTS venow;
CREATE DATABASE venow CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE venow;

-- Bảng vai trò
CREATE TABLE vai_tro (
    ma_vai_tro INT AUTO_INCREMENT PRIMARY KEY,
    ten_vai_tro VARCHAR(50) NOT NULL,
    mo_ta TEXT,
    quyen_han JSON COMMENT 'Lưu trữ các quyền hạn dưới dạng JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng người dùng
CREATE TABLE nguoi_dung (
    ma_nguoi_dung INT AUTO_INCREMENT PRIMARY KEY,
    ma_vai_tro INT NOT NULL,
    ho VARCHAR(50) NOT NULL,
    ten VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    sdt VARCHAR(15),
    gioi_tinh ENUM('Nam', 'Nữ', 'Khác'),
    ngay_sinh DATE,
    dia_chi TEXT,
    avatar VARCHAR(255),
    bio TEXT,
    trang_thai ENUM('Hoạt động', 'Bị khóa', 'Chưa xác thực') DEFAULT 'Chưa xác thực',
    email_verified_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    last_login TIMESTAMP NULL,
    provider VARCHAR(50) COMMENT 'Đăng nhập bằng mạng xã hội',
    provider_id VARCHAR(100) COMMENT 'ID từ mạng xã hội',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_vai_tro) REFERENCES vai_tro(ma_vai_tro)
);

-- Bảng tổ chức
CREATE TABLE to_chuc (
    ma_to_chuc INT AUTO_INCREMENT PRIMARY KEY,
    ma_nguoi_dung INT NOT NULL COMMENT 'Người đại diện tổ chức',
    ten_to_chuc VARCHAR(100) NOT NULL,
    logo VARCHAR(255),
    mo_ta TEXT,
    email_to_chuc VARCHAR(100),
    so_dien_thoai VARCHAR(15),
    website VARCHAR(255),
    dia_chi TEXT,
    ma_so_thue VARCHAR(20),
    giay_phep_kinh_doanh VARCHAR(50),
    tai_khoan_ngan_hang TEXT,
    trang_thai ENUM('Hoạt động', 'Tạm ngưng', 'Chờ duyệt') DEFAULT 'Chờ duyệt',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung)
);

-- Bảng danh mục sự kiện
CREATE TABLE danh_muc_su_kien (
    ma_danh_muc INT AUTO_INCREMENT PRIMARY KEY,
    ten_danh_muc VARCHAR(100) NOT NULL,
    mo_ta TEXT,
    icon VARCHAR(255),
    slug VARCHAR(100) UNIQUE,
    parent_id INT NULL,
    thu_tu INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (parent_id) REFERENCES danh_muc_su_kien(ma_danh_muc) ON DELETE SET NULL
);

-- Bảng loại sự kiện
CREATE TABLE loai_su_kien (
    ma_loai_su_kien INT AUTO_INCREMENT PRIMARY KEY,
    ten_loai_su_kien VARCHAR(50) NOT NULL,
    mo_ta TEXT,
    icon VARCHAR(255),
    slug VARCHAR(100) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng địa điểm
CREATE TABLE dia_diem (
    ma_dia_diem INT AUTO_INCREMENT PRIMARY KEY,
    ten_dia_diem VARCHAR(255) NOT NULL,
    dia_chi TEXT NOT NULL,
    thanh_pho VARCHAR(100) NOT NULL,
    tinh VARCHAR(100) NOT NULL,
    quoc_gia VARCHAR(100) DEFAULT 'Việt Nam',
    toa_do_lat DECIMAL(10, 8) COMMENT 'Vĩ độ',
    toa_do_lng DECIMAL(11, 8) COMMENT 'Kinh độ',
    mo_ta TEXT,
    hinh_anh VARCHAR(255),
    suc_chua INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng sự kiện
CREATE TABLE su_kien (
    ma_su_kien INT AUTO_INCREMENT PRIMARY KEY,
    ma_loai_su_kien INT NOT NULL,
    ma_danh_muc INT NOT NULL,
    ma_to_chuc INT NOT NULL,
    ma_dia_diem INT NOT NULL,
    ten_su_kien VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE,
    hinh_anh_chinh VARCHAR(255) COMMENT 'Ảnh đại diện chính',
    mo_ta_ngan VARCHAR(500),
    mo_ta TEXT,
    noi_dung LONGTEXT COMMENT 'Nội dung chi tiết sự kiện',
    ngay_bat_dau DATETIME NOT NULL,
    ngay_ket_thuc DATETIME NOT NULL,
    thoi_gian_mo_cong VARCHAR(100) COMMENT 'Thời gian mở cổng',
    han_dang_ky DATETIME COMMENT 'Hạn chót đăng ký tham gia',
    dia_diem_cu_the VARCHAR(255),
    so_luong_ve_toi_da INT,
    gia_ve_thap_nhat DECIMAL(10, 2),
    gia_ve_cao_nhat DECIMAL(10, 2),
    do_tuoi_toi_thieu INT COMMENT 'Độ tuổi tối thiểu được tham gia',
    keywords VARCHAR(255) COMMENT 'Từ khóa tìm kiếm',
    featured BOOLEAN DEFAULT FALSE COMMENT 'Sự kiện nổi bật',
    is_online BOOLEAN DEFAULT FALSE COMMENT 'Sự kiện trực tuyến',
    link_online VARCHAR(255) COMMENT 'Link tham gia nếu là sự kiện online',
    ngay_tao_su_kien DATETIME DEFAULT CURRENT_TIMESTAMP,
    trang_thai ENUM('Chờ duyệt', 'Đã duyệt', 'Đã hủy', 'Đã kết thúc', 'Đã bán hết vé', 'Tạm ngưng') DEFAULT 'Chờ duyệt',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_loai_su_kien) REFERENCES loai_su_kien(ma_loai_su_kien),
    FOREIGN KEY (ma_danh_muc) REFERENCES danh_muc_su_kien(ma_danh_muc),
    FOREIGN KEY (ma_to_chuc) REFERENCES to_chuc(ma_to_chuc),
    FOREIGN KEY (ma_dia_diem) REFERENCES dia_diem(ma_dia_diem)
);

-- Bảng hình ảnh sự kiện
CREATE TABLE hinh_anh_su_kien (
    ma_hinh_anh INT AUTO_INCREMENT PRIMARY KEY,
    ma_su_kien INT NOT NULL,
    duong_dan VARCHAR(255) NOT NULL,
    tieu_de VARCHAR(255),
    mo_ta TEXT,
    thu_tu INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien) ON DELETE CASCADE
);

-- Bảng tags
CREATE TABLE tags (
    ma_tag INT AUTO_INCREMENT PRIMARY KEY,
    ten_tag VARCHAR(50) NOT NULL,
    slug VARCHAR(50) UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng liên kết sự kiện và tags
CREATE TABLE su_kien_tags (
    ma_su_kien INT NOT NULL,
    ma_tag INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (ma_su_kien, ma_tag),
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien) ON DELETE CASCADE,
    FOREIGN KEY (ma_tag) REFERENCES tags(ma_tag) ON DELETE CASCADE
);

-- Bảng yêu cầu duyệt sự kiện
CREATE TABLE yeu_cau_duyet_sk (
    ma_yeu_cau INT AUTO_INCREMENT PRIMARY KEY,
    ma_su_kien INT NOT NULL,
    ma_to_chuc INT NOT NULL,
    ma_nguoi_duyet INT NULL COMMENT 'Admin duyệt yêu cầu',
    ten_su_kien VARCHAR(255) NOT NULL,
    trang_thai_yeu_cau ENUM('Chờ duyệt', 'Đã duyệt', 'Từ chối') DEFAULT 'Chờ duyệt',
    ly_do_tu_choi TEXT,
    ngay_tao_yeu_cau DATETIME DEFAULT CURRENT_TIMESTAMP,
    ngay_duyet DATETIME,
    ghi_chu TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien),
    FOREIGN KEY (ma_to_chuc) REFERENCES to_chuc(ma_to_chuc),
    FOREIGN KEY (ma_nguoi_duyet) REFERENCES nguoi_dung(ma_nguoi_dung)
);

-- Bảng khu vực chỗ ngồi
CREATE TABLE khu_vuc_cho_ngoi (
    ma_khu_vuc INT AUTO_INCREMENT PRIMARY KEY,
    ma_su_kien INT NOT NULL,
    ten_khu_vuc VARCHAR(50) NOT NULL,
    mo_ta TEXT,
    so_hang INT COMMENT 'Số hàng ghế trong khu vực',
    so_cot INT COMMENT 'Số cột ghế trong khu vực',
    tong_so_cho INT NOT NULL,
    so_do_khu_vuc JSON COMMENT 'Lưu trữ sơ đồ khu vực dưới dạng JSON',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien) ON DELETE CASCADE
);

-- Bảng hạng mục vé
CREATE TABLE hang_muc_ve (
    ma_hang_muc INT AUTO_INCREMENT PRIMARY KEY,
    ten_hang_muc VARCHAR(50) NOT NULL COMMENT 'Early Bird, Standard, VIP, etc.',
    mo_ta TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng loại vé
CREATE TABLE loai_ve (
    ma_loai_ve INT AUTO_INCREMENT PRIMARY KEY,
    ma_su_kien INT NOT NULL,
    ma_khu_vuc INT NULL COMMENT 'Khu vực chỗ ngồi nếu có',
    ma_hang_muc INT NOT NULL,
    ten_loai_ve VARCHAR(100) NOT NULL,
    mo_ta TEXT,
    gia_ve DECIMAL(10, 2) NOT NULL,
    gia_ve_goc DECIMAL(10, 2) COMMENT 'Giá gốc trước khuyến mãi',
    phi_dich_vu DECIMAL(10, 2) DEFAULT 0 COMMENT 'Phí dịch vụ',
    tong_so_ve INT NOT NULL,
    so_ve_da_ban INT DEFAULT 0,
    so_ve_con_lai INT GENERATED ALWAYS AS (tong_so_ve - so_ve_da_ban) STORED,
    so_ve_toi_da_moi_don INT DEFAULT 10 COMMENT 'Số vé tối đa mỗi đơn hàng',
    thoi_gian_bat_dau_ban DATETIME,
    thoi_gian_ket_thuc_ban DATETIME,
    cho_ngoi_co_dinh BOOLEAN DEFAULT FALSE COMMENT 'Vé có chỗ ngồi cố định hay không',
    trang_thai ENUM('Đang bán', 'Tạm ngưng', 'Đã bán hết', 'Chưa mở bán') DEFAULT 'Chưa mở bán',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien) ON DELETE CASCADE,
    FOREIGN KEY (ma_khu_vuc) REFERENCES khu_vuc_cho_ngoi(ma_khu_vuc) ON DELETE SET NULL,
    FOREIGN KEY (ma_hang_muc) REFERENCES hang_muc_ve(ma_hang_muc)
);

-- Bảng gói vé (combo)
CREATE TABLE goi_ve (
    ma_goi_ve INT AUTO_INCREMENT PRIMARY KEY,
    ma_su_kien INT NOT NULL,
    ten_goi_ve VARCHAR(100) NOT NULL,
    mo_ta TEXT,
    gia_goi DECIMAL(10, 2) NOT NULL,
    gia_goc DECIMAL(10, 2) COMMENT 'Tổng giá gốc các vé riêng lẻ',
    so_luong_toi_da INT DEFAULT 100,
    so_luong_da_ban INT DEFAULT 0,
    thoi_gian_bat_dau_ban DATETIME,
    thoi_gian_ket_thuc_ban DATETIME,
    trang_thai ENUM('Đang bán', 'Tạm ngưng', 'Đã bán hết', 'Chưa mở bán') DEFAULT 'Chưa mở bán',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien) ON DELETE CASCADE
);

-- Bảng chi tiết gói vé
CREATE TABLE chi_tiet_goi_ve (
    ma_goi_ve INT NOT NULL,
    ma_loai_ve INT NOT NULL,
    so_luong INT NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (ma_goi_ve, ma_loai_ve),
    FOREIGN KEY (ma_goi_ve) REFERENCES goi_ve(ma_goi_ve) ON DELETE CASCADE,
    FOREIGN KEY (ma_loai_ve) REFERENCES loai_ve(ma_loai_ve) ON DELETE CASCADE
);

-- Bảng chỗ ngồi
CREATE TABLE cho_ngoi (
    ma_cho_ngoi INT AUTO_INCREMENT PRIMARY KEY,
    ma_khu_vuc INT NOT NULL,
    ma_loai_ve INT NOT NULL,
    ten_cho_ngoi VARCHAR(50) NOT NULL,
    hang VARCHAR(10) COMMENT 'Hàng ghế (A, B, C...)',
    cot VARCHAR(10) COMMENT 'Số ghế trong hàng (1, 2, 3...)',
    vi_tri_x INT COMMENT 'Tọa độ X trên sơ đồ',
    vi_tri_y INT COMMENT 'Tọa độ Y trên sơ đồ',
    trang_thai ENUM('Trống', 'Đã đặt', 'Tạm khóa', 'Không khả dụng') DEFAULT 'Trống',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_khu_vuc) REFERENCES khu_vuc_cho_ngoi(ma_khu_vuc) ON DELETE CASCADE,
    FOREIGN KEY (ma_loai_ve) REFERENCES loai_ve(ma_loai_ve) ON DELETE CASCADE
);

-- Bảng mã giảm giá
CREATE TABLE ma_giam_gia (
    ma_giam_gia INT AUTO_INCREMENT PRIMARY KEY,
    ma_to_chuc INT NULL COMMENT 'Tổ chức tạo mã, NULL nếu là mã hệ thống',
    ma_su_kien INT NULL COMMENT 'Áp dụng cho sự kiện cụ thể, NULL nếu áp dụng cho tất cả',
    code VARCHAR(50) NOT NULL UNIQUE,
    loai_giam_gia ENUM('Phần trăm', 'Số tiền cố định') NOT NULL,
    gia_tri DECIMAL(10, 2) NOT NULL COMMENT 'Giá trị giảm (% hoặc số tiền)',
    gia_tri_toi_da DECIMAL(10, 2) NULL COMMENT 'Giá trị giảm tối đa (cho giảm theo %)',
    so_luong INT COMMENT 'Số lượng mã có thể sử dụng, NULL nếu không giới hạn',
    so_luong_da_dung INT DEFAULT 0,
    ngay_bat_dau DATETIME,
    ngay_ket_thuc DATETIME,
    gia_tri_don_hang_toi_thieu DECIMAL(10, 2) DEFAULT 0,
    so_luong_ve_toi_thieu INT DEFAULT 1,
    chi_ap_dung_cho_loai_ve VARCHAR(255) COMMENT 'Danh sách ID loại vé được áp dụng, NULL nếu áp dụng cho tất cả',
    mo_ta TEXT,
    trang_thai ENUM('Hoạt động', 'Tạm ngưng', 'Hết hạn') DEFAULT 'Hoạt động',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_to_chuc) REFERENCES to_chuc(ma_to_chuc) ON DELETE SET NULL,
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien) ON DELETE SET NULL
);

-- Bảng đơn hàng
CREATE TABLE don_hang (
    ma_don_hang INT AUTO_INCREMENT PRIMARY KEY,
    ma_nguoi_dung INT NOT NULL,
    ma_su_kien INT NOT NULL,
    ma_giam_gia INT NULL,
    ma_don_hang_ref VARCHAR(50) UNIQUE COMMENT 'Mã đơn hàng tham chiếu hiển thị cho người dùng',
    tong_tien DECIMAL(10, 2) NOT NULL,
    tien_giam_gia DECIMAL(10, 2) DEFAULT 0,
    phi_dich_vu DECIMAL(10, 2) DEFAULT 0,
    tong_thanh_toan DECIMAL(10, 2) NOT NULL,
    diem_tich_luy_su_dung INT DEFAULT 0,
    diem_tich_luy_nhan_duoc INT DEFAULT 0,
    ghi_chu TEXT,
    thong_tin_nguoi_nhan JSON COMMENT 'Thông tin người nhận vé',
    ngay_dat DATETIME DEFAULT CURRENT_TIMESTAMP,
    han_thanh_toan DATETIME,
    trang_thai ENUM('Chờ thanh toán', 'Đã thanh toán', 'Đã hủy', 'Hết hạn') DEFAULT 'Chờ thanh toán',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung),
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien),
    FOREIGN KEY (ma_giam_gia) REFERENCES ma_giam_gia(ma_giam_gia) ON DELETE SET NULL
);

-- Bảng chi tiết đơn hàng
CREATE TABLE chi_tiet_don_hang (
    ma_chi_tiet INT AUTO_INCREMENT PRIMARY KEY,
    ma_don_hang INT NOT NULL,
    ma_loai_ve INT NULL,
    ma_goi_ve INT NULL,
    so_luong INT NOT NULL,
    don_gia DECIMAL(10, 2) NOT NULL,
    thanh_tien DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_don_hang) REFERENCES don_hang(ma_don_hang) ON DELETE CASCADE,
    FOREIGN KEY (ma_loai_ve) REFERENCES loai_ve(ma_loai_ve) ON DELETE SET NULL,
    FOREIGN KEY (ma_goi_ve) REFERENCES goi_ve(ma_goi_ve) ON DELETE SET NULL,
    CHECK (ma_loai_ve IS NOT NULL OR ma_goi_ve IS NOT NULL)
);

-- Bảng vé
CREATE TABLE ve (
    ma_ve INT AUTO_INCREMENT PRIMARY KEY,
    ma_don_hang INT NOT NULL,
    ma_chi_tiet_don_hang INT NOT NULL,
    ma_loai_ve INT NOT NULL,
    ma_cho_ngoi INT NULL COMMENT 'NULL nếu không có chỗ ngồi cố định',
    ma_ve_ref VARCHAR(50) UNIQUE COMMENT 'Mã vé tham chiếu hiển thị cho người dùng',
    qr_code VARCHAR(255) COMMENT 'Đường dẫn đến mã QR',
    barcode VARCHAR(255) COMMENT 'Đường dẫn đến mã vạch',
    thong_tin_nguoi_dung JSON COMMENT 'Thông tin người sử dụng vé',
    ngay_phat_hanh DATETIME,
    ngay_su_dung DATETIME,
    trang_thai_ve ENUM('Chờ thanh toán', 'Đã phát hành', 'Đã sử dụng', 'Đã hủy', 'Hết hạn') DEFAULT 'Chờ thanh toán',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_don_hang) REFERENCES don_hang(ma_don_hang) ON DELETE CASCADE,
    FOREIGN KEY (ma_chi_tiet_don_hang) REFERENCES chi_tiet_don_hang(ma_chi_tiet) ON DELETE CASCADE,
    FOREIGN KEY (ma_loai_ve) REFERENCES loai_ve(ma_loai_ve) ON DELETE CASCADE,
    FOREIGN KEY (ma_cho_ngoi) REFERENCES cho_ngoi(ma_cho_ngoi) ON DELETE SET NULL
);

-- Bảng phương thức thanh toán
CREATE TABLE phuong_thuc_thanh_toan (
    ma_phuong_thuc INT AUTO_INCREMENT PRIMARY KEY,
    ten_phuong_thuc VARCHAR(100) NOT NULL,
    ma_code VARCHAR(50) NOT NULL UNIQUE,
    mo_ta TEXT,
    logo VARCHAR(255),
    phi_giao_dich DECIMAL(5, 2) DEFAULT 0 COMMENT 'Phí giao dịch (%)',
    phi_co_dinh DECIMAL(10, 2) DEFAULT 0 COMMENT 'Phí cố định mỗi giao dịch',
    trang_thai BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng thanh toán
CREATE TABLE thanh_toan (
    ma_thanh_toan INT AUTO_INCREMENT PRIMARY KEY,
    ma_don_hang INT NOT NULL,
    ma_nguoi_dung INT NOT NULL,
    ma_phuong_thuc INT NOT NULL,
    ma_giao_dich VARCHAR(100) COMMENT 'Mã giao dịch từ cổng thanh toán',
    so_tien DECIMAL(10, 2) NOT NULL,
    phi_giao_dich DECIMAL(10, 2) DEFAULT 0,
    tong_thanh_toan DECIMAL(10, 2) NOT NULL,
    thong_tin_thanh_toan JSON COMMENT 'Thông tin chi tiết giao dịch',
    trang_thai ENUM('Chờ xử lý', 'Thành công', 'Thất bại', 'Hoàn tiền', 'Hủy bỏ') DEFAULT 'Chờ xử lý',
    ngay_giao_dich DATETIME DEFAULT CURRENT_TIMESTAMP,
    ngay_cap_nhat DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_don_hang) REFERENCES don_hang(ma_don_hang),
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung),
    FOREIGN KEY (ma_phuong_thuc) REFERENCES phuong_thuc_thanh_toan(ma_phuong_thuc)
);

-- Bảng lịch sử thanh toán
CREATE TABLE lich_su_thanh_toan (
    ma_lich_su_thanh_toan INT AUTO_INCREMENT PRIMARY KEY,
    ma_thanh_toan INT NOT NULL,
    hanh_dong VARCHAR(50) NOT NULL COMMENT 'Thanh toán, Hoàn tiền, Hủy bỏ, v.v.',
    so_tien DECIMAL(10, 2) NOT NULL,
    trang_thai VARCHAR(50) NOT NULL,
    thong_tin_bo_sung JSON,
    ngay_giao_dich DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_thanh_toan) REFERENCES thanh_toan(ma_thanh_toan) ON DELETE CASCADE
);

-- Bảng điểm tích lũy
CREATE TABLE diem_tich_luy (
    ma_diem_tich_luy INT AUTO_INCREMENT PRIMARY KEY,
    ma_nguoi_dung INT NOT NULL,
    tong_diem_tich_luy INT DEFAULT 0,
    diem_da_su_dung INT DEFAULT 0,
    diem_kha_dung INT GENERATED ALWAYS AS (tong_diem_tich_luy - diem_da_su_dung) STORED,
    cap_do VARCHAR(50) COMMENT 'Cấp độ thành viên dựa trên điểm',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung) ON DELETE CASCADE
);

-- Bảng lịch sử điểm tích lũy
CREATE TABLE lich_su_diem_tich_luy (
    ma_lich_su_tich_luy INT AUTO_INCREMENT PRIMARY KEY,
    ma_nguoi_dung INT NOT NULL,
    ma_don_hang INT NULL,
    loai_giao_dich ENUM('Cộng điểm khi đặt vé', 'Cộng điểm khi giới thiệu', 'Trừ điểm khi sử dụng', 'Điều chỉnh điểm') NOT NULL,
    so_diem INT NOT NULL,
    diem_truoc_giao_dich INT NOT NULL,
    diem_sau_giao_dich INT NOT NULL,
    mo_ta TEXT,
    ngay_giao_dich DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung),
    FOREIGN KEY (ma_don_hang) REFERENCES don_hang(ma_don_hang) ON DELETE SET NULL
);

-- Bảng cấu hình điểm tích lũy
CREATE TABLE cau_hinh_diem_tich_luy (
    ma_cau_hinh INT AUTO_INCREMENT PRIMARY KEY,
    ty_le_diem DECIMAL(10, 2) DEFAULT 0.01 COMMENT 'Tỷ lệ quy đổi điểm (VD: 0.01 = 1 điểm cho mỗi 100,000đ)',
    gia_tri_diem DECIMAL(10, 2) DEFAULT 1000 COMMENT 'Giá trị của 1 điểm (VNĐ)',
    so_tien_toi_thieu DECIMAL(10, 2) DEFAULT 100000 COMMENT 'Số tiền tối thiểu để tích điểm',
    so_diem_toi_thieu_su_dung INT DEFAULT 10 COMMENT 'Số điểm tối thiểu để sử dụng',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng review đánh giá
CREATE TABLE review_danh_gia (
    ma_danh_gia INT AUTO_INCREMENT PRIMARY KEY,
    ma_su_kien INT NOT NULL,
    ma_nguoi_dung INT NOT NULL,
    ma_don_hang INT NULL COMMENT 'Đơn hàng liên quan đến đánh giá',
    diem_danh_gia TINYINT NOT NULL CHECK (diem_danh_gia BETWEEN 1 AND 5),
    tieu_de VARCHAR(255),
    noi_dung_danh_gia TEXT,
    hinh_anh JSON COMMENT 'Danh sách hình ảnh đính kèm',
    trang_thai ENUM('Chờ duyệt', 'Đã duyệt', 'Từ chối') DEFAULT 'Chờ duyệt',
    ngay_danh_gia DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien),
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung),
    FOREIGN KEY (ma_don_hang) REFERENCES don_hang(ma_don_hang) ON DELETE SET NULL
);

-- Bảng phản hồi đánh giá
CREATE TABLE phan_hoi_danh_gia (
    ma_phan_hoi INT AUTO_INCREMENT PRIMARY KEY,
    ma_danh_gia INT NOT NULL,
    ma_nguoi_dung INT NOT NULL,
    noi_dung TEXT NOT NULL,
    trang_thai ENUM('Hiển thị', 'Ẩn') DEFAULT 'Hiển thị',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_danh_gia) REFERENCES review_danh_gia(ma_danh_gia) ON DELETE CASCADE,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung)
);

-- Bảng thông báo
CREATE TABLE thong_bao (
    ma_thong_bao INT AUTO_INCREMENT PRIMARY KEY,
    ma_nguoi_dung INT NOT NULL,
    tieu_de VARCHAR(255) NOT NULL,
    noi_dung TEXT NOT NULL,
    loai_thong_bao VARCHAR(50) COMMENT 'Đặt vé, Thanh toán, Sự kiện, Hệ thống, v.v.',
    link VARCHAR(255) COMMENT 'Link liên quan đến thông báo',
    da_doc BOOLEAN DEFAULT FALSE,
    trang_thai ENUM('Hiển thị', 'Ẩn') DEFAULT 'Hiển thị',
    ngay_tao_thong_bao DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung) ON DELETE CASCADE
);

-- Bảng báo cáo & thống kê
CREATE TABLE bao_cao_thong_ke (
    ma_bao_cao INT AUTO_INCREMENT PRIMARY KEY,
    ma_su_kien INT NOT NULL,
    ma_to_chuc INT NOT NULL,
    tong_so_ve_ban_ra INT DEFAULT 0,
    tong_doanh_thu DECIMAL(15, 2) DEFAULT 0,
    phi_dich_vu DECIMAL(15, 2) DEFAULT 0,
    doanh_thu_thuc_te DECIMAL(15, 2) DEFAULT 0,
    so_luong_don_hang INT DEFAULT 0,
    so_luong_don_huy INT DEFAULT 0,
    ty_le_chuyen_doi DECIMAL(5, 2) DEFAULT 0 COMMENT 'Tỷ lệ chuyển đổi (%)',
    thoi_gian_bat_dau DATE,
    thoi_gian_ket_thuc DATE,
    ngay_tao_bao_cao DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien),
    FOREIGN KEY (ma_to_chuc) REFERENCES to_chuc(ma_to_chuc)
);

-- Bảng kiểm duyệt sự kiện
CREATE TABLE kiem_duyet_su_kien (
    ma_bao_cao INT AUTO_INCREMENT PRIMARY KEY,
    ma_su_kien INT NOT NULL,
    ma_nguoi_dung INT NOT NULL COMMENT 'Người báo cáo',
    ma_nguoi_xu_ly INT NULL COMMENT 'Admin xử lý báo cáo',
    ten_su_kien VARCHAR(255) NOT NULL,
    noi_dung_bao_cao TEXT NOT NULL,
    ly_do_bao_cao VARCHAR(255) NOT NULL,
    hinh_anh_minh_chung JSON,
    ngay_tao DATETIME DEFAULT CURRENT_TIMESTAMP,
    ngay_xu_ly DATETIME,
    ket_qua_xu_ly TEXT,
    trang_thai ENUM('Chờ xử lý', 'Đang xử lý', 'Đã xử lý', 'Từ chối') DEFAULT 'Chờ xử lý',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien),
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung),
    FOREIGN KEY (ma_nguoi_xu_ly) REFERENCES nguoi_dung(ma_nguoi_dung)
);

-- Bảng yêu cầu hỗ trợ
CREATE TABLE yeu_cau_ho_tro (
    ma_yeu_cau INT AUTO_INCREMENT PRIMARY KEY,
    ma_nguoi_dung INT NOT NULL,
    ma_don_hang INT NULL,
    ma_su_kien INT NULL,
    ma_nguoi_xu_ly INT NULL COMMENT 'Admin xử lý yêu cầu',
    tieu_de VARCHAR(255) NOT NULL,
    noi_dung_yeu_cau TEXT NOT NULL,
    file_dinh_kem JSON,
    loai_yeu_cau VARCHAR(50) COMMENT 'Đặt vé, Thanh toán, Hoàn tiền, Kỹ thuật, v.v.',
    muc_do_uu_tien ENUM('Thấp', 'Trung bình', 'Cao', 'Khẩn cấp') DEFAULT 'Trung bình',
    trang_thai_yeu_cau ENUM('Chờ xử lý', 'Đang xử lý', 'Đã xử lý', 'Đã đóng') DEFAULT 'Chờ xử lý',
    ngay_tao_yeu_cau DATETIME DEFAULT CURRENT_TIMESTAMP,
    ngay_xu_ly_yeu_cau DATETIME,
    ket_qua_xu_ly TEXT,
    danh_gia_chat_luong TINYINT CHECK (danh_gia_chat_luong BETWEEN 1 AND 5),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung),
    FOREIGN KEY (ma_don_hang) REFERENCES don_hang(ma_don_hang) ON DELETE SET NULL,
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien) ON DELETE SET NULL,
    FOREIGN KEY (ma_nguoi_xu_ly) REFERENCES nguoi_dung(ma_nguoi_dung)
);

-- Bảng phản hồi yêu cầu hỗ trợ
CREATE TABLE phan_hoi_ho_tro (
    ma_phan_hoi INT AUTO_INCREMENT PRIMARY KEY,
    ma_yeu_cau INT NOT NULL,
    ma_nguoi_dung INT NOT NULL,
    noi_dung TEXT NOT NULL,
    file_dinh_kem JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_yeu_cau) REFERENCES yeu_cau_ho_tro(ma_yeu_cau) ON DELETE CASCADE,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung)
);

-- Bảng lịch sử hoạt động
CREATE TABLE lich_su_hoat_dong (
    ma_lich_su INT AUTO_INCREMENT PRIMARY KEY,
    ma_nguoi_dung INT NOT NULL,
    hanh_dong VARCHAR(255) NOT NULL,
    mo_ta TEXT,
    doi_tuong VARCHAR(50) COMMENT 'Đối tượng tác động: Sự kiện, Đơn hàng, Vé, v.v.',
    ma_doi_tuong INT COMMENT 'ID của đối tượng tác động',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung)
);

-- Bảng cấu hình hệ thống
CREATE TABLE cau_hinh_he_thong (
    ma_cau_hinh INT AUTO_INCREMENT PRIMARY KEY,
    ten_cau_hinh VARCHAR(100) NOT NULL UNIQUE,
    gia_tri TEXT,
    mo_ta TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Bảng sự kiện yêu thích
CREATE TABLE su_kien_yeu_thich (
    ma_nguoi_dung INT NOT NULL,
    ma_su_kien INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (ma_nguoi_dung, ma_su_kien),
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung) ON DELETE CASCADE,
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien) ON DELETE CASCADE
);

-- Bảng lượt xem sự kiện
CREATE TABLE luot_xem_su_kien (
    ma_luot_xem INT AUTO_INCREMENT PRIMARY KEY,
    ma_su_kien INT NOT NULL,
    ma_nguoi_dung INT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    thoi_gian_xem DATETIME DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien) ON DELETE CASCADE,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung) ON DELETE SET NULL
);

-- Bảng quét vé
CREATE TABLE quet_ve (
    ma_quet INT AUTO_INCREMENT PRIMARY KEY,
    ma_ve INT NOT NULL,
    ma_nguoi_dung INT NOT NULL COMMENT 'Người quét vé',
    thoi_gian_quet DATETIME DEFAULT CURRENT_TIMESTAMP,
    dia_diem_quet VARCHAR(255),
    trang_thai ENUM('Hợp lệ', 'Không hợp lệ', 'Đã sử dụng', 'Hết hạn') NOT NULL,
    ghi_chu TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_ve) REFERENCES ve(ma_ve) ON DELETE CASCADE,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung)
);

-- Bảng nhắc nhở sự kiện
CREATE TABLE nhac_nho_su_kien (
    ma_nhac_nho INT AUTO_INCREMENT PRIMARY KEY,
    ma_nguoi_dung INT NOT NULL,
    ma_su_kien INT NOT NULL,
    thoi_gian_nhac DATETIME NOT NULL,
    da_gui BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_nguoi_dung) ON DELETE CASCADE,
    FOREIGN KEY (ma_su_kien) REFERENCES su_kien(ma_su_kien) ON DELETE CASCADE
);

-- Thêm dữ liệu mẫu

-- Thêm dữ liệu vào bảng vai_tro
INSERT INTO vai_tro (ten_vai_tro, mo_ta, quyen_han) VALUES
('Admin', 'Quản trị viên hệ thống', '{"all": true}'),
('Tổ chức', 'Tổ chức sự kiện', '{"events": {"create": true, "edit": true, "delete": true}, "tickets": {"create": true, "edit": true, "delete": true}}'),
('Khách hàng', 'Người dùng thông thường', '{"events": {"view": true}, "tickets": {"buy": true}}');

-- Thêm dữ liệu vào bảng nguoi_dung
INSERT INTO nguoi_dung (ma_vai_tro, ho, ten, email, password, sdt, gioi_tinh, ngay_sinh, dia_chi, avatar, trang_thai, email_verified_at) VALUES
(1, 'Nguyễn', 'Admin', 'admin@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234567', 'Nam', '1990-01-01', 'Hà Nội', 'avatars/admin.jpg', 'Hoạt động', NOW()),
(2, 'Trần', 'Tổ Chức', 'tochuc1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0912345678', 'Nam', '1985-05-15', 'Hồ Chí Minh', 'avatars/tochuc1.jpg', 'Hoạt động', NOW()),
(2, 'Lê', 'Tổ Chức', 'tochuc2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0923456789', 'Nữ', '1988-08-20', 'Đà Nẵng', 'avatars/tochuc2.jpg', 'Hoạt động', NOW()),
(3, 'Phạm', 'Khách Hàng', 'khachhang1@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0934567890', 'Nam', '1995-03-10', 'Hà Nội', 'avatars/khachhang1.jpg', 'Hoạt động', NOW()),
(3, 'Hoàng', 'Khách Hàng', 'khachhang2@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0945678901', 'Nữ', '1992-07-25', 'Hồ Chí Minh', 'avatars/khachhang2.jpg', 'Hoạt động', NOW()),
(3, 'Vũ', 'Khách Hàng', 'khachhang3@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0956789012', 'Nam', '1998-11-05', 'Đà Nẵng', 'avatars/khachhang3.jpg', 'Hoạt động', NOW());

-- Thêm dữ liệu vào bảng to_chuc
INSERT INTO to_chuc (ma_nguoi_dung, ten_to_chuc, logo, mo_ta, email_to_chuc, so_dien_thoai, website, dia_chi, trang_thai) VALUES
(2, 'Công ty Sự kiện ABC', 'logos/abc.png', 'Chuyên tổ chức các sự kiện văn hóa, nghệ thuật', 'abc@example.com', '0901234567', 'https://abc-events.com', 'Số 123 Đường Lê Lợi, Quận 1, TP.HCM', 'Hoạt động'),
(3, 'Nhà hát Thành phố', 'logos/nhahattphcm.png', 'Nhà hát lớn nhất thành phố với nhiều chương trình nghệ thuật đặc sắc', 'nhahattphcm@example.com', '0912345678', 'https://nhahattphcm.com', 'Số 7 Công trường Lam Sơn, Quận 1, TP.HCM', 'Hoạt động'),
(3, 'Trung tâm Văn hóa XYZ', 'logos/xyz.png', 'Trung tâm văn hóa với nhiều hoạt động nghệ thuật đa dạng', 'xyz@example.com', '0923456789', 'https://xyz-culture.com', 'Số 50 Đường Điện Biên Phủ, Quận Ba Đình, Hà Nội', 'Hoạt động');

-- Thêm dữ liệu vào bảng danh_muc_su_kien
INSERT INTO danh_muc_su_kien (ten_danh_muc, mo_ta, icon, slug, thu_tu) VALUES
('Âm nhạc', 'Các sự kiện âm nhạc, concert, festival', 'icons/music.svg', 'am-nhac', 1),
('Nghệ thuật', 'Các sự kiện nghệ thuật, triển lãm, biểu diễn', 'icons/art.svg', 'nghe-thuat', 2),
('Hội thảo', 'Các sự kiện hội thảo, workshop, talkshow', 'icons/workshop.svg', 'hoi-thao', 3),
('Thể thao', 'Các sự kiện thể thao, giải đấu', 'icons/sport.svg', 'the-thao', 4),
('Giáo dục', 'Các sự kiện giáo dục, đào tạo', 'icons/education.svg', 'giao-duc', 5),
('Giải trí', 'Các sự kiện giải trí, gameshow', 'icons/entertainment.svg', 'giai-tri', 6);

-- Thêm dữ liệu vào bảng loai_su_kien
INSERT INTO loai_su_kien (ten_loai_su_kien, mo_ta, icon, slug) VALUES
('Kịch', 'Các vở kịch, diễn kịch', 'icons/theater.svg', 'kich'),
('Hòa nhạc', 'Các buổi hòa nhạc, concert', 'icons/concert.svg', 'hoa-nhac'),
('Triển lãm', 'Các cuộc triển lãm nghệ thuật, văn hóa', 'icons/exhibition.svg', 'trien-lam'),
('Hội thảo', 'Các buổi hội thảo, tọa đàm', 'icons/seminar.svg', 'hoi-thao'),
('Múa rối', 'Các buổi biểu diễn múa rối', 'icons/puppet.svg', 'mua-roi'),
('Festival', 'Các lễ hội âm nhạc, văn hóa', 'icons/festival.svg', 'festival'),
('Workshop', 'Các buổi workshop, đào tạo', 'icons/workshop.svg', 'workshop'),
('Gameshow', 'Các chương trình gameshow, trò chơi', 'icons/gameshow.svg', 'gameshow');

-- Thêm dữ liệu vào bảng dia_diem
INSERT INTO dia_diem (ten_dia_diem, dia_chi, thanh_pho, tinh, quoc_gia, toa_do_lat, toa_do_lng, mo_ta, hinh_anh, suc_chua) VALUES
('Nhà hát Lớn Hà Nội', '1 Tràng Tiền, Hoàn Kiếm', 'Hà Nội', 'Hà Nội', 'Việt Nam', 21.0245, 105.8551, 'Nhà hát lớn với kiến trúc Pháp cổ', 'venues/nha-hat-lon-ha-noi.jpg', 600),
('Nhà hát Thành phố Hồ Chí Minh', '7 Công trường Lam Sơn, Bến Nghé, Quận 1', 'Hồ Chí Minh', 'Hồ Chí Minh', 'Việt Nam', 10.7768, 106.7031, 'Nhà hát lớn nhất thành phố', 'venues/nha-hat-tphcm.jpg', 800),
('Bảo tàng Mỹ thuật TP.HCM', '97A Phó Đức Chính, Quận 1', 'Hồ Chí Minh', 'Hồ Chí Minh', 'Việt Nam', 10.7697, 106.7014, 'Bảo tàng nghệ thuật lớn', 'venues/bao-tang-my-thuat.jpg', 500),
('Trung tâm Hội nghị Quốc gia', 'Đại lộ Thăng Long, Mễ Trì, Nam Từ Liêm', 'Hà Nội', 'Hà Nội', 'Việt Nam', 21.0042, 105.7825, 'Trung tâm hội nghị hiện đại', 'venues/trung-tam-hoi-nghi-quoc-gia.jpg', 1500),
('Nhà hát Múa rối Thăng Long', '57B Đinh Tiên Hoàng, Hoàn Kiếm', 'Hà Nội', 'Hà Nội', 'Việt Nam', 21.0313, 105.8516, 'Nhà hát múa rối nước truyền thống', 'venues/nha-hat-mua-roi.jpg', 300),
('Công viên 23/9', 'Phạm Ngũ Lão, Quận 1', 'Hồ Chí Minh', 'Hồ Chí Minh', 'Việt Nam', 10.7677, 106.6953, 'Công viên rộng lớn trung tâm thành phố', 'venues/cong-vien-23-9.jpg', 5000);

-- Thêm dữ liệu vào bảng tags
INSERT INTO tags (ten_tag, slug) VALUES
('Âm nhạc', 'am-nhac'),
('Nghệ thuật', 'nghe-thuat'),
('Giải trí', 'giai-tri'),
('Văn hóa', 'van-hoa'),
('Giáo dục', 'giao-duc'),
('Truyền thống', 'truyen-thong'),
('Hiện đại', 'hien-dai'),
('Quốc tế', 'quoc-te'),
('Gia đình', 'gia-dinh'),
('Trẻ em', 'tre-em');

-- Thêm dữ liệu vào bảng su_kien
INSERT INTO su_kien (ma_loai_su_kien, ma_danh_muc, ma_to_chuc, ma_dia_diem, ten_su_kien, slug, hinh_anh_chinh, mo_ta_ngan, mo_ta, ngay_bat_dau, ngay_ket_thuc, thoi_gian_mo_cong, han_dang_ky, dia_diem_cu_the, so_luong_ve_toi_da, gia_ve_thap_nhat, gia_ve_cao_nhat, featured, trang_thai) VALUES
(1, 2, 1, 1, 'Kịch "Người đàn bà điên"', 'kich-nguoi-dan-ba-dien', 'events/kich1.jpg', 'Vở kịch nổi tiếng về cuộc đời của một người phụ nữ', 'Vở kịch "Người đàn bà điên" là một tác phẩm nghệ thuật sâu sắc, khai thác những góc khuất trong tâm hồn con người. Với dàn diễn viên tài năng và kịch bản đặc sắc, vở kịch hứa hẹn mang đến cho khán giả những trải nghiệm cảm xúc mạnh mẽ.', '2023-06-15 19:30:00', '2023-06-20 22:00:00', '18:30', '2023-06-14 23:59:59', 'Phòng biểu diễn chính', 150, 200000, 500000, TRUE, 'Đã duyệt'),
(2, 1, 2, 2, 'Hòa nhạc Mùa thu', 'hoa-nhac-mua-thu', 'events/hoanhac1.jpg', 'Đêm nhạc cổ điển với các tác phẩm nổi tiếng thế giới', 'Đêm hòa nhạc Mùa thu quy tụ những nghệ sĩ tài năng với các tác phẩm âm nhạc cổ điển nổi tiếng thế giới. Khán giả sẽ được đắm chìm trong không gian âm nhạc đỉnh cao với chất lượng âm thanh hoàn hảo.', '2023-07-10 20:00:00', '2023-07-10 22:30:00', '19:00', '2023-07-09 23:59:59', 'Sảnh chính', 200, 300000, 700000, TRUE, 'Đã duyệt'),
(3, 2, 3, 3, 'Triển lãm Nghệ thuật Đương đại', 'trien-lam-nghe-thuat-duong-dai', 'events/trienlam1.jpg', 'Triển lãm các tác phẩm nghệ thuật đương đại của các nghệ sĩ trẻ', 'Triển lãm Nghệ thuật Đương đại giới thiệu hơn 100 tác phẩm của các nghệ sĩ trẻ tài năng trong nước và quốc tế. Triển lãm mang đến góc nhìn mới mẻ về nghệ thuật đương đại, khám phá những chủ đề xã hội, văn hóa và môi trường thông qua nhiều phương tiện nghệ thuật đa dạng.', '2023-08-01 09:00:00', '2023-08-15 18:00:00', '08:30', '2023-07-31 23:59:59', 'Khu triển lãm chính', 200, 100000, 100000, FALSE, 'Đã duyệt'),
(4, 3, 1, 4, 'Hội thảo Khởi nghiệp 2023', 'hoi-thao-khoi-nghiep-2023', 'events/hoithao1.jpg', 'Hội thảo chia sẻ kinh nghiệm khởi nghiệp từ các doanh nhân thành công', 'Hội thảo Khởi nghiệp 2023 quy tụ các doanh nhân thành công, nhà đầu tư và chuyên gia trong lĩnh vực khởi nghiệp. Đây là cơ hội để các bạn trẻ học hỏi kinh nghiệm, kết nối và tìm kiếm cơ hội đầu tư cho dự án của mình.', '2023-09-05 08:30:00', '2023-09-07 17:00:00', '08:00', '2023-09-04 23:59:59', 'Phòng hội thảo A', 100, 500000, 500000, FALSE, 'Chờ duyệt'),
(5, 2, 2, 5, 'Múa rối nước "Huyền thoại Việt"', 'mua-roi-nuoc-huyen-thoai-viet', 'events/roi1.jpg', 'Chương trình múa rối nước truyền thống Việt Nam', 'Múa rối nước "Huyền thoại Việt" tái hiện những câu chuyện dân gian, lịch sử và đời sống văn hóa Việt Nam qua nghệ thuật múa rối nước truyền thống. Chương trình mang đến trải nghiệm văn hóa độc đáo cho khán giả trong và ngoài nước.', '2023-10-10 18:00:00', '2023-10-15 20:00:00', '17:30', '2023-10-09 23:59:59', 'Sân khấu ngoài trời', 120, 150000, 350000, TRUE, 'Đã duyệt'),
(6, 1, 3, 6, 'Festival Âm nhạc Quốc tế', 'festival-am-nhac-quoc-te', 'events/festival1.jpg', 'Lễ hội âm nhạc với sự tham gia của các nghệ sĩ trong và ngoài nước', 'Festival Âm nhạc Quốc tế là sự kiện âm nhạc lớn nhất trong năm, quy tụ các nghệ sĩ nổi tiếng trong và ngoài nước. Khán giả sẽ được thưởng thức nhiều thể loại âm nhạc đa dạng từ pop, rock, jazz đến âm nhạc dân tộc trong không gian ngoài trời sôi động.', '2023-11-20 16:00:00', '2023-11-25 23:00:00', '15:00', '2023-11-19 23:59:59', 'Khu vực sân khấu chính', 800, 400000, 1500000, TRUE, 'Chờ duyệt');

-- Thêm dữ liệu vào bảng su_kien_tags
INSERT INTO su_kien_tags (ma_su_kien, ma_tag) VALUES
(1, 2), (1, 3), (1, 4),
(2, 1), (2, 4), (2, 8),
(3, 2), (3, 7), (3, 8),
(4, 5), (4, 7),
(5, 2), (5, 4), (5, 6), (5, 9), (5, 10),
(6, 1), (6, 3), (6, 8);

-- Thêm dữ liệu vào bảng hinh_anh_su_kien
INSERT INTO hinh_anh_su_kien (ma_su_kien, duong_dan, tieu_de, mo_ta, thu_tu) VALUES
(1, 'events/kich1_1.jpg', 'Cảnh diễn chính', 'Cảnh diễn chính của vở kịch', 1),
(1, 'events/kich1_2.jpg', 'Diễn viên chính', 'Diễn viên chính của vở kịch', 2),
(2, 'events/hoanhac1_1.jpg', 'Dàn nhạc giao hưởng', 'Dàn nhạc giao hưởng biểu diễn', 1),
(2, 'events/hoanhac1_2.jpg', 'Nghệ sĩ violin', 'Nghệ sĩ violin solo', 2),
(3, 'events/trienlam1_1.jpg', 'Tác phẩm 1', 'Tác phẩm nghệ thuật đương đại 1', 1),
(3, 'events/trienlam1_2.jpg', 'Tác phẩm 2', 'Tác phẩm nghệ thuật đương đại 2', 2),
(5, 'events/roi1_1.jpg', 'Cảnh múa rối 1', 'Cảnh múa rối nước truyền thống', 1),
(5, 'events/roi1_2.jpg', 'Cảnh múa rối 2', 'Nghệ nhân điều khiển rối nước', 2),
(6, 'events/festival1_1.jpg', 'Sân khấu chính', 'Sân khấu chính của festival', 1),
(6, 'events/festival1_2.jpg', 'Khán giả', 'Khán giả tham gia festival', 2);

-- Thêm dữ liệu vào bảng yeu_cau_duyet_sk
INSERT INTO yeu_cau_duyet_sk (ma_su_kien, ma_to_chuc, ma_nguoi_duyet, ten_su_kien, trang_thai_yeu_cau, ngay_tao_yeu_cau, ngay_duyet, ghi_chu) VALUES
(1, 1, 1, 'Kịch "Người đàn bà điên"', 'Đã duyệt', '2023-04-25 09:30:00', '2023-04-27 14:20:00', 'Đã kiểm tra nội dung, phù hợp với quy định'),
(2, 2, 1, 'Hòa nhạc Mùa thu', 'Đã duyệt', '2023-05-10 11:15:00', '2023-05-12 10:30:00', 'Sự kiện đã được xác nhận'),
(3, 3, 1, 'Triển lãm Nghệ thuật Đương đại', 'Đã duyệt', '2023-05-25 14:20:00', '2023-05-26 09:45:00', 'Đã xác nhận địa điểm và thời gian'),
(4, 1, NULL, 'Hội thảo Khởi nghiệp 2023', 'Chờ duyệt', '2023-06-25 10:45:00', NULL, 'Đang chờ xác nhận từ ban tổ chức'),
(5, 2, 1, 'Múa rối nước "Huyền thoại Việt"', 'Đã duyệt', '2023-07-25 13:10:00', '2023-07-27 11:20:00', 'Đã xác nhận với đoàn nghệ thuật'),
(6, 3, NULL, 'Festival Âm nhạc Quốc tế', 'Chờ duyệt', '2023-08-25 16:05:00', NULL, 'Đang chờ xác nhận từ các nghệ sĩ quốc tế');

-- Thêm dữ liệu vào bảng hang_muc_ve
INSERT INTO hang_muc_ve (ten_hang_muc, mo_ta) VALUES
('Early Bird', 'Vé bán sớm với giá ưu đãi'),
('Standard', 'Vé tiêu chuẩn'),
('VIP', 'Vé VIP với nhiều đặc quyền'),
('Premium', 'Vé cao cấp với vị trí tốt nhất'),
('Student', 'Vé dành cho học sinh, sinh viên');

-- Thêm dữ liệu vào bảng khu_vuc_cho_ngoi
INSERT INTO khu_vuc_cho_ngoi (ma_su_kien, ten_khu_vuc, mo_ta, so_hang, so_cot, tong_so_cho, so_do_khu_vuc) VALUES
(1, 'Khu A', 'Khu vực phía trước sân khấu', 10, 10, 100, '{"layout": "theater", "rows": 10, "columns": 10}'),
(1, 'Khu VIP', 'Khu vực VIP với tầm nhìn tốt nhất', 5, 10, 50, '{"layout": "theater", "rows": 5, "columns": 10}'),
(2, 'Khu B', 'Khu vực chính giữa', 15, 10, 150, '{"layout": "theater", "rows": 15, "columns": 10}'),
(2, 'Khu VIP', 'Khu vực VIP phía trước', 5, 10, 50, '{"layout": "theater", "rows": 5, "columns": 10}'),
(5, 'Khu C', 'Khu vực thường', 8, 10, 80, '{"layout": "theater", "rows": 8, "columns": 10}'),
(5, 'Khu VIP', 'Khu vực VIP', 4, 10, 40, '{"layout": "theater", "rows": 4, "columns": 10}');

-- Thêm dữ liệu vào bảng loai_ve
INSERT INTO loai_ve (ma_su_kien, ma_khu_vuc, ma_hang_muc, ten_loai_ve, mo_ta, gia_ve, gia_ve_goc, phi_dich_vu, tong_so_ve, so_ve_da_ban, thoi_gian_bat_dau_ban, thoi_gian_ket_thuc_ban, cho_ngoi_co_dinh, trang_thai) VALUES
(1, 1, 2, 'Vé Thường', 'Vé thường cho khu vực A', 200000, 200000, 10000, 100, 20, '2023-05-01 00:00:00', '2023-06-14 23:59:59', TRUE, 'Đang bán'),
(1, 2, 3, 'Vé VIP', 'Vé VIP với vị trí tốt nhất', 500000, 500000, 20000, 50, 10, '2023-05-01 00:00:00', '2023-06-14 23:59:59', TRUE, 'Đang bán'),
(2, 3, 2, 'Vé Thường', 'Vé thường cho khu vực B', 300000, 300000, 15000, 150, 30, '2023-06-01 00:00:00', '2023-07-09 23:59:59', TRUE, 'Đang bán'),
(2, 4, 3, 'Vé VIP', 'Vé VIP với vị trí tốt nhất', 700000, 700000, 25000, 50, 15, '2023-06-01 00:00:00', '2023-07-09 23:59:59', TRUE, 'Đang bán'),
(3, NULL, 2, 'Vé vào cửa', 'Vé vào cửa triển lãm', 100000, 100000, 5000, 200, 50, '2023-07-01 00:00:00', '2023-07-31 23:59:59', FALSE, 'Đang bán'),
(4, NULL, 2, 'Vé tham dự', 'Vé tham dự hội thảo', 500000, 500000, 20000, 100, 0, '2023-08-01 00:00:00', '2023-09-04 23:59:59', FALSE, 'Chưa mở bán'),
(5, 5, 2, 'Vé Thường', 'Vé thường cho khu vực C', 150000, 150000, 10000, 80, 20, '2023-09-01 00:00:00', '2023-10-09 23:59:59', TRUE, 'Đang bán'),
(5, 6, 3, 'Vé VIP', 'Vé VIP với vị trí tốt nhất', 350000, 350000, 15000, 40, 10, '2023-09-01 00:00:00', '2023-10-09 23:59:59', TRUE, 'Đang bán'),
(6, NULL, 1, 'Vé Early Bird', 'Vé bán sớm với giá ưu đãi', 400000, 500000, 20000, 200, 0, '2023-10-01 00:00:00', '2023-10-31 23:59:59', FALSE, 'Chưa mở bán'),
(6, NULL, 3, 'Vé VIP', 'Vé VIP trọn gói tất cả các ngày', 1500000, 1500000, 50000, 100, 0, '2023-10-01 00:00:00', '2023-11-19 23:59:59', FALSE, 'Chưa mở bán');

-- Thêm dữ liệu vào bảng goi_ve
INSERT INTO goi_ve (ma_su_kien, ten_goi_ve, mo_ta, gia_goi, gia_goc, so_luong_toi_da, so_luong_da_ban, thoi_gian_bat_dau_ban, thoi_gian_ket_thuc_ban, trang_thai) VALUES
(1, 'Gói Gia đình', 'Gói vé dành cho gia đình (4 vé thường)', 700000, 800000, 20, 5, '2023-05-01 00:00:00', '2023-06-14 23:59:59', 'Đang bán'),
(2, 'Gói Đôi VIP', 'Gói vé dành cho cặp đôi (2 vé VIP)', 1300000, 1400000, 15, 8, '2023-06-01 00:00:00', '2023-07-09 23:59:59', 'Đang bán'),
(5, 'Gói Gia đình', 'Gói vé dành cho gia đình (4 vé thường)', 500000, 600000, 15, 5, '2023-09-01 00:00:00', '2023-10-09 23:59:59', 'Đang bán'),
(6, 'Gói Festival 3 ngày', 'Vé tham dự festival trong 3 ngày', 1000000, 1200000, 50, 0, '2023-10-01 00:00:00', '2023-11-19 23:59:59', 'Chưa mở bán');

-- Thêm dữ liệu vào bảng chi_tiet_goi_ve
INSERT INTO chi_tiet_goi_ve (ma_goi_ve, ma_loai_ve, so_luong) VALUES
(1, 1, 4),
(2, 4, 2),
(3, 7, 4),
(4, 9, 3);

-- Thay đổi cách tạo dữ liệu cho bảng cho_ngoi để đảm bảo có đúng các ID cần thiết
-- Xóa đoạn INSERT cũ cho bảng cho_ngoi và thay thế bằng đoạn sau:

-- Thêm chỗ ngồi cho sự kiện 1, khu vực 1 (Khu A)
INSERT INTO cho_ngoi (ma_cho_ngoi, ma_khu_vuc, ma_loai_ve, ten_cho_ngoi, hang, cot, vi_tri_x, vi_tri_y, trang_thai) VALUES
(1, 1, 1, 'A01', 'A', '1', 0, 0, 'Đã đặt'),
(2, 1, 1, 'A02', 'A', '2', 30, 0, 'Trống'),
(3, 1, 1, 'A03', 'A', '3', 60, 0, 'Trống'),
(4, 1, 1, 'A04', 'A', '4', 90, 0, 'Trống'),
(5, 1, 1, 'A05', 'A', '5', 120, 0, 'Trống'),
(6, 1, 1, 'A06', 'A', '6', 150, 0, 'Trống'),
(7, 1, 1, 'A07', 'A', '7', 180, 0, 'Trống'),
(8, 1, 1, 'A08', 'A', '8', 210, 0, 'Trống'),
(9, 1, 1, 'A09', 'A', '9', 240, 0, 'Trống'),
(10, 1, 1, 'A10', 'A', '10', 270, 0, 'Trống');

-- Thêm chỗ ngồi cho sự kiện 1, khu vực 2 (Khu VIP)
INSERT INTO cho_ngoi (ma_cho_ngoi, ma_khu_vuc, ma_loai_ve, ten_cho_ngoi, hang, cot, vi_tri_x, vi_tri_y, trang_thai) VALUES
(11, 2, 2, 'V01', 'A', '1', 0, 0, 'Đã đặt'),
(12, 2, 2, 'V02', 'A', '2', 30, 0, 'Trống'),
(13, 2, 2, 'V03', 'A', '3', 60, 0, 'Trống'),
(14, 2, 2, 'V04', 'A', '4', 90, 0, 'Trống'),
(15, 2, 2, 'V05', 'A', '5', 120, 0, 'Trống');

-- Thêm chỗ ngồi cho sự kiện 2, khu vực 3 (Khu B)
INSERT INTO cho_ngoi (ma_cho_ngoi, ma_khu_vuc, ma_loai_ve, ten_cho_ngoi, hang, cot, vi_tri_x, vi_tri_y, trang_thai) VALUES
(21, 3, 3, 'B01', 'A', '1', 0, 0, 'Đã đặt'),
(22, 3, 3, 'B02', 'A', '2', 30, 0, 'Trống'),
(23, 3, 3, 'B03', 'A', '3', 60, 0, 'Trống'),
(24, 3, 3, 'B04', 'A', '4', 90, 0, 'Trống'),
(25, 3, 3, 'B05', 'A', '5', 120, 0, 'Trống'),
(26, 3, 3, 'B06', 'A', '6', 150, 0, 'Trống'),
(27, 3, 3, 'B07', 'A', '7', 180, 0, 'Trống'),
(28, 3, 3, 'B08', 'A', '8', 210, 0, 'Trống'),
(29, 3, 3, 'B09', 'A', '9', 240, 0, 'Trống'),
(30, 3, 3, 'B10', 'A', '10', 270, 0, 'Trống');

-- Thêm chỗ ngồi cho sự kiện 2, khu vực 4 (Khu VIP)
INSERT INTO cho_ngoi (ma_cho_ngoi, ma_khu_vuc, ma_loai_ve, ten_cho_ngoi, hang, cot, vi_tri_x, vi_tri_y, trang_thai) VALUES
(31, 4, 4, 'VB01', 'A', '1', 0, 0, 'Đã đặt'),
(32, 4, 4, 'VB02', 'A', '2', 30, 0, 'Trống'),
(33, 4, 4, 'VB03', 'A', '3', 60, 0, 'Trống'),
(34, 4, 4, 'VB04', 'A', '4', 90, 0, 'Trống'),
(35, 4, 4, 'VB05', 'A', '5', 120, 0, 'Trống');

-- Thêm chỗ ngồi cho sự kiện 5, khu vực 5 (Khu C)
INSERT INTO cho_ngoi (ma_cho_ngoi, ma_khu_vuc, ma_loai_ve, ten_cho_ngoi, hang, cot, vi_tri_x, vi_tri_y, trang_thai) VALUES
(41, 5, 7, 'C01', 'A', '1', 0, 0, 'Đã đặt'),
(42, 5, 7, 'C02', 'A', '2', 30, 0, 'Trống'),
(43, 5, 7, 'C03', 'A', '3', 60, 0, 'Trống'),
(44, 5, 7, 'C04', 'A', '4', 90, 0, 'Trống');

-- Thêm chỗ ngồi cho sự kiện 5, khu vực 6 (Khu VIP)
INSERT INTO cho_ngoi (ma_cho_ngoi, ma_khu_vuc, ma_loai_ve, ten_cho_ngoi, hang, cot, vi_tri_x, vi_tri_y, trang_thai) VALUES
(45, 6, 8, 'VC01', 'A', '1', 0, 0, 'Đã đặt'),
(46, 6, 8, 'VC02', 'A', '2', 30, 0, 'Trống'),
(47, 6, 8, 'VC03', 'A', '3', 60, 0, 'Trống'),
(48, 6, 8, 'VC04', 'A', '4', 90, 0, 'Trống');

-- Xóa đoạn SELECT cũ để tạo chỗ ngồi
-- Xóa các dòng sau:
-- SELECT 1, 1, CONCAT('A', LPAD(num, 2, '0')), CHAR(65 + (num-1) DIV 10), (num-1) % 10 + 1, (num-1) % 10 * 30, (num-1) DIV 10 * 30, 'Trống'
-- FROM (
--   SELECT 1 AS num UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5
--   UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9 UNION SELECT 10
-- ) AS numbers;
-- và các đoạn SELECT tương tự khác

-- Thêm dữ liệu vào bảng ma_giam_gia
INSERT INTO ma_giam_gia (ma_to_chuc, ma_su_kien, code, loai_giam_gia, gia_tri, gia_tri_toi_da, so_luong, so_luong_da_dung, ngay_bat_dau, ngay_ket_thuc, gia_tri_don_hang_toi_thieu, mo_ta, trang_thai) VALUES
(1, 1, 'WELCOME10', 'Phần trăm', 10, 50000, 100, 20, '2023-05-01 00:00:00', '2023-06-14 23:59:59', 200000, 'Giảm 10% tối đa 50,000đ cho đơn hàng từ 200,000đ', 'Hoạt động'),
(2, 2, 'MUSIC20', 'Phần trăm', 20, 100000, 50, 15, '2023-06-01 00:00:00', '2023-07-09 23:59:59', 300000, 'Giảm 20% tối đa 100,000đ cho đơn hàng từ 300,000đ', 'Hoạt động'),
(NULL, NULL, 'NEWYEAR50K', 'Số tiền cố định', 50000, NULL, 200, 0, '2023-12-25 00:00:00', '2024-01-10 23:59:59', 500000, 'Giảm 50,000đ cho đơn hàng từ 500,000đ', 'Hoạt động'),
(3, 5, 'PUPPET30', 'Phần trăm', 30, 100000, 30, 5, '2023-09-01 00:00:00', '2023-10-09 23:59:59', 300000, 'Giảm 30% tối đa 100,000đ cho đơn hàng từ 300,000đ', 'Hoạt động');

-- Thêm dữ liệu vào bảng phuong_thuc_thanh_toan
INSERT INTO phuong_thuc_thanh_toan (ten_phuong_thuc, ma_code, mo_ta, logo, phi_giao_dich, phi_co_dinh, trang_thai) VALUES
('Thẻ tín dụng/ghi nợ', 'credit_card', 'Thanh toán bằng thẻ Visa, Mastercard, JCB', 'payments/credit_card.png', 2.5, 0, TRUE),
('Chuyển khoản ngân hàng', 'bank_transfer', 'Chuyển khoản qua tài khoản ngân hàng', 'payments/bank_transfer.png', 0, 0, TRUE),
('Ví điện tử MoMo', 'momo', 'Thanh toán qua ví điện tử MoMo', 'payments/momo.png', 1.5, 0, TRUE),
('Ví điện tử ZaloPay', 'zalopay', 'Thanh toán qua ví điện tử ZaloPay', 'payments/zalopay.png', 1.5, 0, TRUE),
('VNPay', 'vnpay', 'Thanh toán qua cổng VNPay', 'payments/vnpay.png', 1.8, 0, TRUE);

-- Thêm dữ liệu vào bảng don_hang
INSERT INTO don_hang (ma_nguoi_dung, ma_su_kien, ma_giam_gia, ma_don_hang_ref, tong_tien, tien_giam_gia, phi_dich_vu, tong_thanh_toan, diem_tich_luy_nhan_duoc, ngay_dat, han_thanh_toan, trang_thai) VALUES
(4, 1, 1, 'ORD-20230520-001', 200000, 20000, 10000, 190000, 20, '2023-05-20 10:15:00', '2023-05-20 11:15:00', 'Đã thanh toán'),
(5, 1, NULL, 'ORD-20230521-001', 500000, 0, 20000, 520000, 50, '2023-05-21 14:30:00', '2023-05-21 15:30:00', 'Đã thanh toán'),
(6, 2, 2, 'ORD-20230615-001', 300000, 60000, 15000, 255000, 30, '2023-06-15 09:45:00', '2023-06-15 10:45:00', 'Đã thanh toán'),
(4, 2, NULL, 'ORD-20230616-001', 700000, 0, 25000, 725000, 70, '2023-06-16 11:20:00', '2023-06-16 12:20:00', 'Đã thanh toán'),
(5, 5, 4, 'ORD-20230901-001', 150000, 45000, 10000, 115000, 15, '2023-09-01 13:10:00', '2023-09-01 14:10:00', 'Đã thanh toán'),
(6, 5, NULL, 'ORD-20230902-001', 350000, 0, 15000, 365000, 35, '2023-09-02 16:40:00', '2023-09-02 17:40:00', 'Đã thanh toán');

-- Thêm dữ liệu vào bảng chi_tiet_don_hang
INSERT INTO chi_tiet_don_hang (ma_don_hang, ma_loai_ve, ma_goi_ve, so_luong, don_gia, thanh_tien) VALUES
(1, 1, NULL, 1, 200000, 200000),
(2, 2, NULL, 1, 500000, 500000),
(3, 3, NULL, 1, 300000, 300000),
(4, 4, NULL, 1, 700000, 700000),
(5, 7, NULL, 1, 150000, 150000),
(6, 8, NULL, 1, 350000, 350000);

-- Cập nhật trạng thái chỗ ngồi đã đặt
UPDATE cho_ngoi SET trang_thai = 'Đã đặt' WHERE ma_cho_ngoi IN (1, 11, 21, 31, 41, 45);

-- Thêm dữ liệu vào bảng ve
INSERT INTO ve (ma_don_hang, ma_chi_tiet_don_hang, ma_loai_ve, ma_cho_ngoi, ma_ve_ref, qr_code, barcode, ngay_phat_hanh, trang_thai_ve) VALUES
(1, 1, 1, 1, 'TIX-20230520-001', 'qrcodes/TIX-20230520-001.png', 'barcodes/TIX-20230520-001.png', '2023-05-20 10:20:00', 'Đã phát hành'),
(2, 2, 2, 11, 'TIX-20230520-002', 'qrcodes/TIX-20230520-002.png', 'barcodes/TIX-20230520-002.png', '2023-05-20 10:20:00', 'Đã phát hành'),
(3, 3, 3, 21, 'TIX-20230615-001', 'qrcodes/TIX-20230615-001.png', 'barcodes/TIX-20230615-001.png', '2023-06-15 09:50:00', 'Đã phát hành'),
(4, 4, 4, 31, 'TIX-20230616-001', 'qrcodes/TIX-20230616-001.png', 'barcodes/TIX-20230616-001.png', '2023-06-16 11:25:00', 'Đã phát hành'),
(5, 5, 7, 41, 'TIX-20230901-001', 'qrcodes/TIX-20230901-001.png', 'barcodes/TIX-20230901-001.png', '2023-09-01 13:15:00', 'Đã phát hành'),
(6, 6, 8, 45, 'TIX-20230902-001', 'qrcodes/TIX-20230902-001.png', 'barcodes/TIX-20230902-001.png', '2023-09-02 17:00:00', 'Đã phát hành');

-- Thêm dữ liệu vào bảng thanh_toan
INSERT INTO thanh_toan (ma_don_hang, ma_nguoi_dung, ma_phuong_thuc, ma_giao_dich, so_tien, phi_giao_dich, tong_thanh_toan, trang_thai, ngay_giao_dich) VALUES
(1, 4, 1, 'PAY-20230520-001', 190000, 4750, 194750, 'Thành công', '2023-05-20 10:18:00'),
(2, 5, 3, 'PAY-20230521-001', 520000, 7800, 527800, 'Thành công', '2023-05-21 14:35:00'),
(3, 6, 2, 'PAY-20230615-001', 255000, 0, 255000, 'Thành công', '2023-06-15 09:48:00'),
(4, 4, 5, 'PAY-20230616-001', 725000, 13050, 738050, 'Thành công', '2023-06-16 11:22:00'),
(5, 5, 4, 'PAY-20230901-001', 115000, 1725, 116725, 'Thành công', '2023-09-01 13:12:00'),
(6, 6, 1, 'PAY-20230902-001', 365000, 9125, 374125, 'Thành công', '2023-09-02 16:45:00');

-- Thêm dữ liệu vào bảng lich_su_thanh_toan
INSERT INTO lich_su_thanh_toan (ma_thanh_toan, hanh_dong, so_tien, trang_thai, ngay_giao_dich) VALUES
(1, 'Thanh toán', 194750, 'Thành công', '2023-05-20 10:18:00'),
(2, 'Thanh toán', 527800, 'Thành công', '2023-05-21 14:35:00'),
(3, 'Thanh toán', 255000, 'Thành công', '2023-06-15 09:48:00'),
(4, 'Thanh toán', 738050, 'Thành công', '2023-06-16 11:22:00'),
(5, 'Thanh toán', 116725, 'Thành công', '2023-09-01 13:12:00'),
(6, 'Thanh toán', 374125, 'Thành công', '2023-09-02 16:45:00');

-- Thêm dữ liệu vào bảng diem_tich_luy
INSERT INTO diem_tich_luy (ma_nguoi_dung, tong_diem_tich_luy, diem_da_su_dung, cap_do) VALUES
(4, 90, 0, 'Thành viên'),
(5, 65, 0, 'Thành viên'),
(6, 65, 0, 'Thành viên');

-- Thêm dữ liệu vào bảng lich_su_diem_tich_luy
INSERT INTO lich_su_diem_tich_luy (ma_nguoi_dung, ma_don_hang, loai_giao_dich, so_diem, diem_truoc_giao_dich, diem_sau_giao_dich, mo_ta, ngay_giao_dich) VALUES
(4, 1, 'Cộng điểm khi đặt vé', 20, 0, 20, 'Tích điểm từ đơn hàng ORD-20230520-001', '2023-05-20 10:20:00'),
(5, 2, 'Cộng điểm khi đặt vé', 50, 0, 50, 'Tích điểm từ đơn hàng ORD-20230521-001', '2023-05-21 14:35:00'),
(6, 3, 'Cộng điểm khi đặt vé', 30, 0, 30, 'Tích điểm từ đơn hàng ORD-20230615-001', '2023-06-15 09:50:00'),
(4, 4, 'Cộng điểm khi đặt vé', 70, 20, 90, 'Tích điểm từ đơn hàng ORD-20230616-001', '2023-06-16 11:25:00'),
(5, 5, 'Cộng điểm khi đặt vé', 15, 50, 65, 'Tích điểm từ đơn hàng ORD-20230901-001', '2023-09-01 13:15:00'),
(6, 6, 'Cộng điểm khi đặt vé', 35, 30, 65, 'Tích điểm từ đơn hàng ORD-20230902-001', '2023-09-02 17:00:00');

-- Thêm dữ liệu vào bảng cau_hinh_diem_tich_luy
INSERT INTO cau_hinh_diem_tich_luy (ty_le_diem, gia_tri_diem, so_tien_toi_thieu, so_diem_toi_thieu_su_dung) VALUES
(0.01, 1000, 100000, 10);

-- Thêm dữ liệu vào bảng review_danh_gia
INSERT INTO review_danh_gia (ma_su_kien, ma_nguoi_dung, ma_don_hang, diem_danh_gia, tieu_de, noi_dung_danh_gia, trang_thai, ngay_danh_gia) VALUES
(1, 4, 1, 5, 'Vở kịch tuyệt vời', 'Diễn viên diễn xuất rất tốt, cảm xúc chân thật. Tôi rất thích và sẽ giới thiệu cho bạn bè.', 'Đã duyệt', '2023-06-21 09:30:00'),
(2, 5, 2, 4, 'Âm nhạc hay nhưng âm thanh hơi nhỏ', 'Buổi hòa nhạc rất hay, nhưng hệ thống âm thanh ở một số vị trí không được tốt lắm.', 'Đã duyệt', '2023-07-12 14:20:00'),
(5, 6, 6, 5, 'Trải nghiệm văn hóa tuyệt vời', 'Múa rối nước là một nghệ thuật độc đáo của Việt Nam, rất đáng xem, đặc biệt là với trẻ em.', 'Đã duyệt', '2023-10-16 10:15:00');

-- Thêm dữ liệu vào bảng phan_hoi_danh_gia
INSERT INTO phan_hoi_danh_gia (ma_danh_gia, ma_nguoi_dung, noi_dung) VALUES
(1, 2, 'Cảm ơn bạn đã đánh giá tích cực. Chúng tôi rất vui khi bạn thích vở kịch.'),
(2, 3, 'Xin lỗi về vấn đề âm thanh. Chúng tôi sẽ cải thiện trong các buổi biểu diễn tiếp theo.'),
(3, 2, 'Cảm ơn bạn đã đánh giá cao chương trình. Hẹn gặp lại bạn trong các sự kiện tới.');

-- Thêm dữ liệu vào bảng thong_bao
INSERT INTO thong_bao (ma_nguoi_dung, tieu_de, noi_dung, loai_thong_bao, link, da_doc) VALUES
(4, 'Đặt vé thành công', 'Bạn đã đặt vé thành công cho sự kiện Kịch "Người đàn bà điên"', 'Đặt vé', '/ve/TIX-20230520-001', TRUE),
(5, 'Đặt vé thành công', 'Bạn đã đặt vé thành công cho sự kiện Hòa nhạc Mùa thu', 'Đặt vé', '/ve/TIX-20230521-001', TRUE),
(6, 'Đặt vé thành công', 'Bạn đã đặt vé thành công cho sự kiện Múa rối nước "Huyền thoại Việt"', 'Đặt vé', '/ve/TIX-20230902-001', FALSE),
(4, 'Sự kiện sắp diễn ra', 'Sự kiện Kịch "Người đàn bà điên" sẽ diễn ra trong 2 ngày nữa', 'Sự kiện', '/su-kien/kich-nguoi-dan-ba-dien', TRUE),
(5, 'Khảo sát sau sự kiện', 'Vui lòng đánh giá trải nghiệm của bạn tại sự kiện Hòa nhạc Mùa thu', 'Hệ thống', '/danh-gia/2', FALSE);

-- Thêm dữ liệu vào bảng bao_cao_thong_ke
INSERT INTO bao_cao_thong_ke (ma_su_kien, ma_to_chuc, tong_so_ve_ban_ra, tong_doanh_thu, phi_dich_vu, doanh_thu_thuc_te, so_luong_don_hang, so_luong_don_huy, ty_le_chuyen_doi, thoi_gian_bat_dau, thoi_gian_ket_thuc) VALUES
(1, 1, 30, 7000000, 300000, 6700000, 30, 2, 15.5, '2023-05-01', '2023-06-14'),
(2, 2, 45, 15000000, 750000, 14250000, 45, 5, 22.5, '2023-06-01', '2023-07-09'),
(5, 2, 30, 6500000, 350000, 6150000, 30, 3, 25.0, '2023-09-01', '2023-10-09');

-- Thêm dữ liệu vào bảng su_kien_yeu_thich
INSERT INTO su_kien_yeu_thich (ma_nguoi_dung, ma_su_kien) VALUES
(4, 1),
(4, 2),
(5, 2),
(5, 5),
(6, 5),
(6, 3);

-- Thêm dữ liệu vào bảng luot_xem_su_kien
INSERT INTO luot_xem_su_kien (ma_su_kien, ma_nguoi_dung, ip_address, user_agent, thoi_gian_xem) VALUES
(1, 4, '192.168.1.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2023-05-15 10:30:00'),
(1, 5, '192.168.1.2', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)', '2023-05-16 14:20:00'),
(2, 4, '192.168.1.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', '2023-06-05 09:15:00'),
(2, 6, '192.168.1.3', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1)', '2023-06-07 16:40:00'),
(5, 5, '192.168.1.2', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7)', '2023-09-05 11:25:00'),
(5, 6, '192.168.1.3', 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_7_1)', '2023-09-08 18:10:00');

-- Thêm dữ liệu vào bảng quet_ve
INSERT INTO quet_ve (ma_ve, ma_nguoi_dung, thoi_gian_quet, dia_diem_quet, trang_thai, ghi_chu) VALUES
(1, 1, '2023-06-15 19:15:00', 'Cổng vào chính', 'Hợp lệ', 'Khách vào đúng giờ'),
(2, 1, '2023-06-15 19:20:00', 'Cổng vào chính', 'Hợp lệ', 'Khách vào đúng giờ'),
(3, 1, '2023-07-10 19:45:00', 'Cổng vào chính', 'Hợp lệ', 'Khách vào đúng giờ'),
(4, 1, '2023-07-10 19:50:00', 'Cổng vào chính', 'Hợp lệ', 'Khách vào đúng giờ');

-- Thêm dữ liệu vào bảng nhac_nho_su_kien
INSERT INTO nhac_nho_su_kien (ma_nguoi_dung, ma_su_kien, thoi_gian_nhac, da_gui) VALUES
(4, 1, '2023-06-14 19:30:00', TRUE),
(5, 2, '2023-07-09 20:00:00', TRUE),
(6, 5, '2023-10-09 18:00:00', TRUE);

-- Thêm dữ liệu vào bảng cau_hinh_he_thong
INSERT INTO cau_hinh_he_thong (ten_cau_hinh, gia_tri, mo_ta) VALUES
('site_name', 'VENOW', 'Tên trang web'),
('site_description', 'Nền tảng đặt vé sự kiện trực tuyến', 'Mô tả trang web'),
('contact_email', 'contact@venow.vn', 'Email liên hệ'),
('support_phone', '1900 1234', 'Số điện thoại hỗ trợ'),
('maintenance_mode', 'false', 'Chế độ bảo trì'),
('default_currency', 'VND', 'Đơn vị tiền tệ mặc định'),
('booking_expiry_minutes', '60', 'Thời gian hết hạn đặt vé (phút)'),
('max_tickets_per_order', '10', 'Số lượng vé tối đa mỗi đơn hàng');

-- Thêm dữ liệu vào bảng yeu_cau_ho_tro
INSERT INTO yeu_cau_ho_tro (ma_nguoi_dung, ma_don_hang, ma_su_kien, tieu_de, noi_dung_yeu_cau, loai_yeu_cau, muc_do_uu_tien, trang_thai_yeu_cau, ngay_tao_yeu_cau) VALUES
(4, 1, 1, 'Không nhận được vé qua email', 'Tôi đã thanh toán nhưng chưa nhận được vé qua email', 'Đặt vé', 'Cao', 'Đã xử lý', '2023-05-20 10:30:00'),
(5, 2, 2, 'Yêu cầu hoàn tiền', 'Tôi không thể tham dự sự kiện vì lý do cá nhân', 'Hoàn tiền', 'Trung bình', 'Đã xử lý', '2023-05-22 09:15:00'),
(6, 6, 5, 'Thay đổi thông tin người dùng vé', 'Tôi muốn thay đổi tên người sử dụng vé', 'Đặt vé', 'Thấp', 'Đang xử lý', '2023-09-03 10:20:00');

-- Thêm dữ liệu vào bảng phan_hoi_ho_tro
INSERT INTO phan_hoi_ho_tro (ma_yeu_cau, ma_nguoi_dung, noi_dung) VALUES
(1, 1, 'Chúng tôi đã gửi lại vé qua email của bạn. Vui lòng kiểm tra hộp thư đến và thư rác.'),
(2, 1, 'Yêu cầu hoàn tiền của bạn đã được chấp nhận. Số tiền sẽ được hoàn lại trong 3-5 ngày làm việc.');

-- Thêm dữ liệu vào bảng lich_su_hoat_dong
INSERT INTO lich_su_hoat_dong (ma_nguoi_dung, hanh_dong, mo_ta, doi_tuong, ma_doi_tuong, ip_address) VALUES
(4, 'Đăng nhập', 'Đăng nhập vào hệ thống', 'Người dùng', 4, '192.168.1.1'),
(4, 'Đặt vé', 'Đặt vé cho sự kiện Kịch "Người đàn bà điên"', 'Đơn hàng', 1, '192.168.1.1'),
(5, 'Đăng nhập', 'Đăng nhập vào hệ thống', 'Người dùng', 5, '192.168.1.2'),
(5, 'Đặt vé', 'Đặt vé cho sự kiện Hòa nhạc Mùa thu', 'Đơn hàng', 2, '192.168.1.2'),
(6, 'Đăng nhập', 'Đăng nhập vào hệ thống', 'Người dùng', 6, '192.168.1.3'),
(6, 'Đặt vé', 'Đặt vé cho sự kiện Múa rối nước "Huyền thoại Việt"', 'Đơn hàng', 6, '192.168.1.3');
