<?php
require '../connectdb.php';
require '../object/obj_nha_cung_cap.php';

if (isset($_POST['suaNhaCungCap'])) {
    $supplier_id = $_POST['supplier_id'];
    $supplier_name = $_POST['supplier_name'];
    $supplier_phone = $_POST['supplier_phone'];
    $supplier_address = $_POST['supplier_address'];
    
    $obj = new obj_nhacungcap();
    $obj->suaNhaCungCap($conn, $supplier_id, $supplier_name, $supplier_phone, $supplier_address);

}

header('Location: ../index.php?p=nhacungcap');
exit();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <!-- 0140327454 -->
</body>
</html>