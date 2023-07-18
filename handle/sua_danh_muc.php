<?php
require '../connectdb.php';
require '../object/obj_danh_muc.php';

if (isset($_POST['suaDanhMuc'])) {
    $category_id = $_POST['category_id'];
    $category_name = $_POST['category_name'];
    
    $obj = new obj_danhmuc();
    $obj->suaDanhMuc($conn, $category_id, $category_name);
}

header('Location: ../index.php?p=danhmuc');
exit();
?>