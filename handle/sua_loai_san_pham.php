<?php
require '../connectdb.php';
require '../object/obj_loai_sp.php';

if (isset($_POST['suaLoaiSanPham'])) {
    $type_id = $_POST['type_id'];
    $type_name = $_POST['type_name'];
    
    $obj = new obj_loaisanpham();
    $obj->suaLoaiSanPham($conn, $type_id, $type_name);

    echo $type_id, $type_name;
}

header('Location: ../index.php?p=loaisp');
exit();
?>