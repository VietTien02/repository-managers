<?php
require '../connectdb.php';
require '../object/obj_san_pham.php';

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $obj = new obj_sanpham();
    try {
        $obj->xoaSanPham($conn, $product_id);
        header('Location: ../index.php?p=sanpham&delete_success=true');
        exit();
    } catch (Exception $e) {
        header('Location: ../index.php?p=sanpham&delete_error=true');
        exit();
    }
}

header('Location: ../index.php?p=sanpham');
exit();
?>