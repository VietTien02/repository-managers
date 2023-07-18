<?php
require '../connectdb.php';
require '../object/obj_danh_muc.php';

if (isset($_GET['category_id'])) {
    $category_id = $_GET['category_id'];
    $obj = new obj_danhmuc();
    
    try {
        $obj->xoaDanhMuc($conn, $category_id);
        header('Location: ../index.php?p=danhmuc&delete_success=true');
        exit();
    } catch (Exception $e) {
        header('Location: ../index.php?p=danhmuc&delete_error=true');
        exit();
    }
}

header('Location: ../index.php?p=danhmuc');
exit();
?>