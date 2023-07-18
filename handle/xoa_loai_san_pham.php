<?php
require '../connectdb.php';
require '../object/obj_loai_sp.php';

if (isset($_GET['type_id'])) {
    $type_id = $_GET['type_id'];
    $obj = new obj_loaisanpham();

    try {
        $obj->xoaLoaiSanPham($conn, $type_id);
        header('Location: ../index.php?p=loaisp&delete_success=true');
        exit();
    } catch (Exception $e) {
        header('Location: ../index.php?p=loaisp&delete_error=true');
        exit();
    }
}

header('Location: ../index.php?p=loaisp');
exit();
?>