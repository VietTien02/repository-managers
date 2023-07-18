<?php
class obj_loaisanpham
{
    public $type_id;
    public $type_name;

    public function themLoaiSanPham($conn, $type_name)
    {
        $message = "Đã có loại sản phẩm có tên là $type_name";
        // Kiểm tra nếu loại sản phẩm đã tồn tại
        $checkQuery = "SELECT * FROM product_types WHERE type_name = '$type_name'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            throw new Exception($message);
        } else {
            $sql = "INSERT INTO product_types (type_name) VALUES ('$type_name')";

            if (mysqli_query($conn, $sql)) {
                $message =  "Thêm loại sản phẩm thành công!";
                echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
            } else {
                $message =  "Thêm loại sản phẩm thất bại";
                echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
            }
        }
    }

    public function xoaLoaiSanPham($conn, $type_id)
    {
        $message = "Xóa loại sản phẩm thành công!";
        $sql = "DELETE FROM product_types WHERE type_id = $type_id";

        if (mysqli_query($conn, $sql)) {
            echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
        } else {
            $message = "Xóa loại sản phẩm thất bại!";
            echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
        }
    }

    public function layLoaiSanPham($conn)
    {
        $sql = "SELECT * FROM product_types";
        $result = mysqli_query($conn, $sql);

        $data = array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function suaLoaiSanPham($conn, $type_id, $type_name)
    {
        $message = "Chỉnh sửa loại sản phẩm thành công!";
        $sql = "UPDATE product_types SET type_name = '$type_name' WHERE type_id = $type_id";

        if (mysqli_query($conn, $sql)) {
            echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
        } else {
            $message = "Chỉnh sửa loại sản phẩm thất bại!";
            echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
        }
    }

    public function timLoaiSanPham($conn, $searchQuery)
    {
        $searchQuery = mysqli_real_escape_string($conn, $searchQuery);
        $searchQueryLowerCase = strtolower($searchQuery);
        $sql = "SELECT * FROM product_types WHERE LOWER(type_name) LIKE '%$searchQueryLowerCase%'";
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