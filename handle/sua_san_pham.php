<?php
require '../connectdb.php';
require '../object/obj_san_pham.php';

if (isset($_POST['suaSanPham'])) {
    $product_id = $_POST['product_id'];
    $product_code = $_POST['product_code'];
    $product_name = $_POST['product_name'];
    $product_color = $_POST['product_color'];
    $product_image = $_POST['product_image'];
    $product_price = $_POST['product_price'];
    $product_type_id = $_POST['product_type_id'];
    $product_supplier_id = $_POST['product_supplier_id'];
    $product_category_id = $_POST['product_category_id'];

    $obj = new obj_sanpham();
    $obj->suaSanPham($conn, $product_id, $product_code, $product_name, $product_color,$product_image, $product_price, $product_type_id, $product_category_id);
}

header('Location: ../index.php?p=sanpham');
exit();