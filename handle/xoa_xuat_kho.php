<?php
require '../connectdb.php';
require '../object/obj_kho.php';

if (isset($_GET['inventory_id'])) {
    $inventory_id = $_GET['inventory_id'];
    $obj = new obj_kho();
    $obj->xoaPhieuNhapKho($conn, $inventory_id);
}

header('Location: ../index.php?p=xuatkho');
exit();
?>