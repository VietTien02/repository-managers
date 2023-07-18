<?php
if (isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 'trangchu';
}
?>

<h4 class="fw-bold fs-1"> <?php echo $pages[$page][0] ?> </h4>
<hr />

<?php
if ($page == 'loaisp') {
    include('./section/loaisp.php');
}
if ($page == 'danhmuc') {
    include('./section/danhmuc.php');
}
if ($page == 'nhacungcap') {
    include('./section/nhacungcap.php');
}
if ($page == 'user') {
    include('./section/user.php');
}
if ($page == 'sanpham') {
    include('./section/sanpham.php');
}
if ($page == 'nhapkho') {
    include('./section/nhapkho.php');
}
if ($page == 'xuatkho') {
    include('./section/xuatkho.php');
}
if ($page == 'tonkho') {
    include('./section/tonkho.php');
}
if ($page == 'baocao') {
    include('./section/baocao.php');
}
if($page=='trangchu'){
    include('./section/trangchu.php');
}
else {
    include('./section/home.php');
}
?>