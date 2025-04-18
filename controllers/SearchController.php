<?php
class SearchController {
    private $sukienModel;
    
    public function __construct() {
        // Include the model
        include_once(__DIR__ . "/../models/msukien.php");
        $this->sukienModel = new mSuKien();
    }
    
    /**
     * Process search parameters and return search results
     * 
     * @return array Search results and metadata
     */
    public function processSearch() {
        // Get search keyword
        $keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        // Build the WHERE clause
        $whereClause = $this->buildWhereClause($keyword);
        
        // Handle pagination
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
        $limit = 12; // Events per page
        $offset = ($page - 1) * $limit;
        
        // Get total events count
        $totalEvents = count($this->sukienModel->getDanhSachSuKien($whereClause));
        $totalPages = ceil($totalEvents / $limit);
        
        // Get events list
        $sukien = $this->sukienModel->getDanhSachSuKien($whereClause, "sk.ngay_bat_dau ASC", $limit, $offset);
        
        // Get categories list
        $danhMuc = $this->sukienModel->getDanhMucSuKien();
        
        // Return all data needed for the view
        return [
            'keyword' => $keyword,
            'sukien' => $sukien,
            'danhMuc' => $danhMuc,
            'totalEvents' => $totalEvents,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ];
    }
    
    /**
     * Build the SQL WHERE clause based on search parameters
     * 
     * @param string $keyword Search keyword
     * @return string SQL WHERE clause
     */
    private function buildWhereClause($keyword) {
        $whereClause = "sk.trang_thai = 'Đã duyệt'";
        
        // Add keyword search condition
        if (!empty($keyword)) {
            $whereClause .= " AND (sk.ten_su_kien LIKE '%$keyword%' OR sk.mo_ta_ngan LIKE '%$keyword%' OR dd.ten_dia_diem LIKE '%$keyword%' OR dd.thanh_pho LIKE '%$keyword%')";
        }
        
        // Filter by category
        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $category = $_GET['category'];
            $whereClause .= " AND dm.slug = '$category'";
        }
        
        // Filter by date
        if (isset($_GET['date']) && !empty($_GET['date'])) {
            $whereClause .= $this->getDateFilter($_GET['date']);
        }
        
        // Filter by location
        if (isset($_GET['location']) && !empty($_GET['location'])) {
            $whereClause .= $this->getLocationFilter($_GET['location']);
        }
        
        // Filter by price
        if (isset($_GET['price']) && !empty($_GET['price'])) {
            $whereClause .= $this->getPriceFilter($_GET['price']);
        }
        
        return $whereClause;
    }
    
    /**
     * Get date filter SQL condition
     * 
     * @param string $dateFilter Date filter value
     * @return string SQL condition for date filtering
     */
    private function getDateFilter($dateFilter) {
        $today = date('Y-m-d');
        $tomorrow = date('Y-m-d', strtotime('+1 day'));
        $weekend = date('Y-m-d', strtotime('next Saturday'));
        $weekEnd = date('Y-m-d', strtotime('next Sunday'));
        $monthEnd = date('Y-m-t'); // Last day of current month
        
        switch ($dateFilter) {
            case 'today':
                return " AND DATE(sk.ngay_bat_dau) = '$today'";
            case 'tomorrow':
                return " AND DATE(sk.ngay_bat_dau) = '$tomorrow'";
            case 'weekend':
                return " AND (DATE(sk.ngay_bat_dau) = '$weekend' OR DATE(sk.ngay_bat_dau) = '$weekEnd')";
            case 'week':
                return " AND DATE(sk.ngay_bat_dau) BETWEEN '$today' AND DATE_ADD('$today', INTERVAL 7 DAY)";
            case 'month':
                return " AND DATE(sk.ngay_bat_dau) BETWEEN '$today' AND '$monthEnd'";
            case 'custom':
                $condition = "";
                if (isset($_GET['start_date']) && !empty($_GET['start_date'])) {
                    $start_date = $_GET['start_date'];
                    $condition .= " AND DATE(sk.ngay_bat_dau) >= '$start_date'";
                }
                if (isset($_GET['end_date']) && !empty($_GET['end_date'])) {
                    $end_date = $_GET['end_date'];
                    $condition .= " AND DATE(sk.ngay_bat_dau) <= '$end_date'";
                }
                return $condition;
            default:
                return "";
        }
    }
    
    /**
     * Get location filter SQL condition
     * 
     * @param string $locationFilter Location filter value
     * @return string SQL condition for location filtering
     */
    private function getLocationFilter($locationFilter) {
        switch ($locationFilter) {
            case 'hcm':
                return " AND dd.thanh_pho LIKE '%Hồ Chí Minh%'";
            case 'hanoi':
                return " AND dd.thanh_pho LIKE '%Hà Nội%'";
            case 'danang':
                return " AND dd.thanh_pho LIKE '%Đà Nẵng%'";
            default:
                return " AND dd.thanh_pho LIKE '%$locationFilter%'";
        }
    }
    
    /**
     * Get price filter SQL condition
     * 
     * @param string $priceFilter Price filter value
     * @return string SQL condition for price filtering
     */
    private function getPriceFilter($priceFilter) {
        switch ($priceFilter) {
            case 'free':
                return " AND sk.gia_ve_thap_nhat = 0";
            case '0-100000':
                return " AND sk.gia_ve_thap_nhat BETWEEN 0 AND 100000";
            case '100000-300000':
                return " AND sk.gia_ve_thap_nhat BETWEEN 100000 AND 300000";
            case '300000-500000':
                return " AND sk.gia_ve_thap_nhat BETWEEN 300000 AND 500000";
            case '500000+':
                return " AND sk.gia_ve_thap_nhat > 500000";
            default:
                return "";
        }
    }
    
    /**
     * Get the display name for a category by slug
     * 
     * @param string $slug Category slug
     * @param array $categories List of categories
     * @return string Category display name
     */
    public function getCategoryName($slug, $categories) {
        foreach ($categories as $category) {
            if ($category['slug'] === $slug) {
                return $category['ten_danh_muc'];
            }
        }
        return '';
    }
    
    /**
     * Get the display name for a date filter
     * 
     * @param string $dateFilter Date filter value
     * @return string Date filter display name
     */
    public function getDateFilterName($dateFilter) {
        switch ($dateFilter) {
            case 'today':
                return 'Hôm nay';
            case 'tomorrow':
                return 'Ngày mai';
            case 'weekend':
                return 'Cuối tuần này';
            case 'week':
                return 'Tuần này';
            case 'month':
                return 'Tháng này';
            case 'custom':
                $start = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                $end = isset($_GET['end_date']) ? $_GET['end_date'] : '';
                return "Từ $start đến $end";
            default:
                return '';
        }
    }
    
    /**
     * Get the display name for a location filter
     * 
     * @param string $locationFilter Location filter value
     * @return string Location filter display name
     */
    public function getLocationFilterName($locationFilter) {
        switch ($locationFilter) {
            case 'hcm':
                return 'TP. Hồ Chí Minh';
            case 'hanoi':
                return 'Hà Nội';
            case 'danang':
                return 'Đà Nẵng';
            default:
                return $locationFilter;
        }
    }
    
    /**
     * Get the display name for a price filter
     * 
     * @param string $priceFilter Price filter value
     * @return string Price filter display name
     */
    public function getPriceFilterName($priceFilter) {
        switch ($priceFilter) {
            case 'free':
                return 'Miễn phí';
            case '0-100000':
                return 'Dưới 100.000 ₫';
            case '100000-300000':
                return '100.000 ₫ - 300.000 ₫';
            case '300000-500000':
                return '300.000 ₫ - 500.000 ₫';
            case '500000+':
                return 'Trên 500.000 ₫';
            default:
                return '';
        }
    }
}
?>
