<?php
class obj_nhacungcap
{
    public $supplier_id;
    public $supplier_name;

    public function themNhaCungCap($conn, $supplier_name, $supplier_phone, $supplier_address)
{
    $message = "Đã có nhà cung cấp có tên là $supplier_name";
    
    // Kiểm tra nếu nhà cung cấp đã tồn tại
    $checkQuery = "SELECT * FROM suppliers WHERE supplier_name = '$supplier_name'";
    $checkResult = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        throw new Exception($message);
    } else {
        $sql = "INSERT INTO suppliers (supplier_name, supplier_phone, supplier_address) 
                VALUES ('$supplier_name', '$supplier_phone', '$supplier_address')";

        if (mysqli_query($conn, $sql)) {
            $message = "Thêm nhà cung cấp thành công!";
            echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
        } else {
            $message = "Thêm nhà cung cấp thất bại";
            echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
        }
    }
}


    public function xoaNhaCungCap($conn, $supplier_id)
    {
        $message = "Xóa nhà cung cấp thành công!";
        $sql = "DELETE FROM suppliers WHERE supplier_id = $supplier_id";

        if (mysqli_query($conn, $sql)) {
            echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
        } else {
            $message = "Xóa nhà cung cấp thất bại!";
            echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
        }
    }

    public function layNhaCungCap($conn)
    {
        $sql = "SELECT * FROM suppliers";
        $result = mysqli_query($conn, $sql);

        $data = array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function suaNhaCungCap($conn, $supplier_id, $supplier_name, $supplier_phone, $supplier_address)
    {
        $message = "Chỉnh sửa nhà cung cấp thành công!";
        $sql = "UPDATE suppliers SET supplier_name = '$supplier_name', supplier_phone = '$supplier_phone', supplier_address = '$supplier_address' WHERE supplier_id = $supplier_id";

        if (mysqli_query($conn, $sql)) {
            echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
        } else {
            $message = "Chỉnh sửa nhà cung cấp thất bại!";
            echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
        }
    }

    public function timNhaCungCap($conn, $searchQuery)
    {
        $searchQuery = mysqli_real_escape_string($conn, $searchQuery);
        $searchQueryLowerCase = strtolower($searchQuery);
        $sql = "SELECT * FROM suppliers WHERE LOWER(supplier_name) LIKE '%$searchQueryLowerCase%'";
        $result = mysqli_query($conn, $sql);

        $data = array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }
}