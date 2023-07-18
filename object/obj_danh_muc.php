<?php
class obj_danhmuc
{
    public $category_id;
    public $category_name;

    public function themDanhMuc($conn, $category_name)
    {
        $message = "Đã có danh mục có tên là $category_name";
        // Kiểm tra nếu loại sản phẩm đã tồn tại
        $checkQuery = "SELECT * FROM categories WHERE category_name = '$category_name'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            throw new Exception($message);
        } else {
            $sql = "INSERT INTO categories (category_name) VALUES ('$category_name')";

            if (mysqli_query($conn, $sql)) {
                $message =  "Thêm danh mục thành công!";
                echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
            } else {
                $message =  "Thêm danh mục thất bại";
                echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
            }
        }
    }

    public function xoaDanhMuc($conn, $category_id)
    {
        $message = "Xóa danh mục thành công!";
        $sql = "DELETE FROM categories WHERE category_id = $category_id";

        if (mysqli_query($conn, $sql)) {
            echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
        } else {
            $message = "Xóa danh mục thất bại!";
            echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
        }
    }

    public function layDanhMuc($conn)
    {
        $sql = "SELECT * FROM categories";
        $result = mysqli_query($conn, $sql);

        $data = array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function suaDanhMuc($conn, $category_id, $category_name)
    {
        $message = "Chỉnh sửa danh mục thành công!";
        $sql = "UPDATE categories SET category_name = '$category_name' WHERE category_id = $category_id";

        if (mysqli_query($conn, $sql)) {
            echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
        } else {
            $message = "Chỉnh sửa danh mục thất bại!";
            echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
        }
    }

    public function timDanhMuc($conn, $searchQuery)
    {
        $searchQuery = mysqli_real_escape_string($conn, $searchQuery);
        $searchQueryLowerCase = strtolower($searchQuery);
        $sql = "SELECT * FROM categories WHERE LOWER(category_name) LIKE '%$searchQueryLowerCase%'";
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