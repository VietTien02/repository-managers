<?php
class obj_kho
{
    public $inventory_id;
    public $product_id;
    public $inventory_type;
    public $inventory_date;
    public $inventory_price;

    public function layPhieuNhapKho($conn)
    {
        $sql = "SELECT * FROM inventory JOIN users ON inventory.user_id = users.user_id WHERE inventory_type = 'in'";
        $result = mysqli_query($conn, $sql);

        $data = array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function layPhieuXuatKho($conn)
    {
        $sql = "SELECT * FROM inventory JOIN users ON inventory.user_id = users.user_id WHERE inventory_type = 'out'";
        $result = mysqli_query($conn, $sql);

        $data = array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function nhapKho($conn, $inventory_type, $inventory_date, $total_price, $user_id, $supplier_id)
    {
        $insertSql = "INSERT INTO inventory (inventory_type, inventory_date , user_id, inventory_price, supplier_id) 
                  VALUES ('$inventory_type', '$inventory_date', $user_id, $total_price, '$supplier_id ')";

        mysqli_query($conn, $insertSql);

        return mysqli_insert_id($conn);
    }

    public function themSanPhamVaoPhieuNhap($conn, $inventory_id, $product_id, $inventory_quantity)
    {
        $updateSql = "UPDATE products SET product_quantity = product_quantity + $inventory_quantity WHERE product_id = $product_id";
        mysqli_query($conn, $updateSql);

        $insertSql = "INSERT INTO inventory_items (inventory_id, product_id, inventory_quantity) 
                  VALUES ($inventory_id, $product_id, $inventory_quantity)";

        mysqli_query($conn, $insertSql);
    }

    public function xuatKho($conn, $inventory_type, $inventory_date, $total_price, $user_id, $supplier, $inventory_status)
    {
        $insertSql = "INSERT INTO inventory (inventory_type, inventory_date , user_id, inventory_price, supplier, inventory_status) 
                  VALUES ('$inventory_type', '$inventory_date', $user_id, $total_price, '$supplier', '$inventory_status')";

        mysqli_query($conn, $insertSql);

        return mysqli_insert_id($conn);
    }

    public function themSanPhamVaoPhieuXuat($conn, $inventory_id, $product_id, $inventory_quantity)
    {
        $updateSql = "UPDATE products SET product_quantity = product_quantity - $inventory_quantity WHERE product_id = $product_id";
        mysqli_query($conn, $updateSql);

        $insertSql = "INSERT INTO inventory_items (inventory_id, product_id, inventory_quantity) 
                  VALUES ($inventory_id, $product_id, $inventory_quantity)";

        mysqli_query($conn, $insertSql);
    }

    public function xoaPhieuNhapKho($conn, $inventory_id)
    {
        $sqlDeleteItems = "DELETE FROM inventory_items WHERE inventory_id = $inventory_id";
        mysqli_query($conn, $sqlDeleteItems);

        $sqlDeleteInventory = "DELETE FROM inventory WHERE inventory_id = $inventory_id";
        if (mysqli_query($conn, $sqlDeleteInventory)) {
            // Xóa thành công
            echo '<div class="alert alert-success" role="alert"></div>';
        } else {
            // Xóa thất bại
            $message = "Xóa loại sản phẩm thất bại!";
            echo '<div class="alert alert-danger" role="alert"></div>';
        }
    }
}