<?php
$baseUrl = $_SERVER['PHP_SELF'];
$pages = array(
    'home' => array('Trang Chủ', './section/home.php'),
    'loaisp' => array('Loại sản phẩm', './section/loaisp.php'),
    'danhmuc' => array('Danh mục', './section/danhmuc.php'),
    'nhacungcap' => array('Nhà cung cấp', './section/nhacungcap.php'),
    'user' => array('Tài khoản', './section/user.php'),
    'sanpham' => array('Sản phẩm', './section/sanpham.php'),
    'nhapkho' => array('Nhập kho', './section/nhapkho.php'),
    'xuatkho' => array('Xuất kho', './section/xuatkho.php'),
    'tonkho' => array('Tồn kho', './section/tonkho.php'),
    'baocao' => array('Báo cáo', './section/baocao.php'),
    'trangchu' => array('Trang chủ', './section/trangchu.php'),

);
?>
<?php
$url = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$queryString = parse_url($url, PHP_URL_QUERY);
if (!empty($queryString)) {
    parse_str($queryString, $params);
    if (isset($params['p'])) {
        $page = $params['p'];
    }
}
?>
<?php
include('./connectdb.php');
$username = $_SESSION['username'];
$query = "SELECT role FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$role = $row['role'];
?>
<nav class="sidebar card py-2 mb-4">
    <img src="./assets/Logo.jpg" alt="" class="Logo mr-2">
    <?php if ($role === 'admin') : ?>
    <ul class="nav flex-column" id="nav_accordion">
    <li class="nav-item <?php if ($page === 'trangchu') echo 'active--nav' ?>">
            <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=trangchu" ?>"> <i class="bi bi-house-fill "></i> Trang chủ</a>
        </li>
        <li class="nav-item">
            <a class="nav-link textdiconomic" href="#"> <i class="bi bi-list-task me-2"></i>Quản lí hàng hóa</a>
            <ul class="submenu show">
           
                <li class="<?php if ($page === 'danhmuc') echo 'active--nav' ?>">
                    <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=danhmuc" ?>"><i class="bi bi-tags-fill me-2"></i>Quản lí danh mục</a>
                </li>
                <li class="<?php if ($page === 'loaisp') echo 'active--nav' ?>">
                    <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=loaisp" ?>"><i class="bi bi-bookmark-star-fill me-2"></i>Quản lí loại sản phẩm</a>
                </li>
                <li class="<?php if ($page === 'nhacungcap') echo 'active--nav' ?>">
                    <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=nhacungcap" ?>"><i class="bi bi-person-check-fill me-2"></i>Quản lí nhà cung cấp</a>
                </li>
                <li class="<?php if ($page === 'sanpham') echo 'active--nav' ?>">
                    <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=sanpham" ?>"><i class="bi bi-cart-check-fill me-2"></i>Quản lí sản phẩm</a>
                </li>
            </ul>
        </li>
        <li class="nav-item <?php if ($page === 'nhapkho') echo 'active--nav' ?>">
            <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=nhapkho" ?>"> <i class="bi bi-box-arrow-in-left me-2"></i>Quản lí nhập kho </a>
        </li>
        <li class="nav-item <?php if ($page === 'xuatkho') echo 'active--nav' ?>">
            <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=xuatkho" ?>"> <i class="bi bi-box-arrow-right me-2"></i>Quản lí xuất kho</a>
        </li>
        <li class="nav-item <?php if ($page === 'tonkho') echo 'active--nav' ?>">
            <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=tonkho" ?>"><i class="bi bi-x-square-fill me-2"></i> Quản lí tồn kho</a>
        </li>
        <li class="nav-item <?php if ($page === 'user') echo 'active--nav' ?>">
            <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=user" ?>"><i class="bi bi-person-vcard-fill me-2"></i> Quản lí tài khoản</a>
        </li>
        <li class="nav-item <?php if ($page === 'baocao') echo 'active--nav' ?>">
            <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=baocao" ?>"> <i class="bi bi-bar-chart-fill me-2"></i>Thống kê và báo cáo</a>
        </li>
        
    </ul>
    <?php else : ?>
    <ul class="nav flex-column" id="nav_accordion">
    <li class="nav-item <?php if ($page === 'trangchu') echo 'active--nav' ?>">
            <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=trangchu" ?>"> <i class="bi bi-house-fill me-2"></i>Trang chủ</a>
        </li>
        <li class="nav-item <?php if ($page === 'nhapkho') echo 'active--nav' ?>">
            <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=nhapkho" ?>"><i class="bi bi-box-arrow-in-left me-2"></i>Quản lí nhập kho </a>
        </li>
        <li class="nav-item <?php if ($page === 'xuatkho') echo 'active--nav' ?>">
            <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=xuatkho" ?>"> <i class="bi bi-box-arrow-right me-2"></i>Quản lí xuất kho</a>
        </li>
        <li class="nav-item <?php if ($page === 'tonkho') echo 'active--nav' ?>">
            <a class="nav-link textdiconomic" href="<?php echo "$baseUrl?p=tonkho" ?>"> <i class="bi bi-x-square-fill me-2"></i>Quản lí tồn kho</a>
        </li>
        
    </ul>
    <?php endif; ?>
</nav>