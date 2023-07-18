<?php
require '../connectdb.php';
require '../object/obj_nha_cung_cap.php';

if (isset($_GET['supplier_id'])) {
    $supplier_id = $_GET['supplier_id'];
    $obj = new obj_nhacungcap();
    try {
        $obj->xoaNhaCungCap($conn, $supplier_id);
        header('Location: ../index.php?p=nhacungcap&delete_success=true');
        exit();
    } catch (Exception $e) {
        header('Location: ../index.php?p=nhacungcap&delete_error=true');
        exit();
    }
}

header('Location: ../index.php?p=nhacungcap');
exit();
?>