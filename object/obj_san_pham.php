<?php
class obj_sanpham
{
    public $user_id;
    public $username;
    public $password;
    public $role;

    public function themSanPham($conn,  $product_code, $product_name, $product_color, $product_image, $product_quantity, $product_price, $product_type_id, $product_category_id)
    {
        $message = "Đã có sản phẩm có tên là $product_name";
        $checkQuery = "SELECT * FROM products WHERE product_code = '$product_code'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            throw new Exception($message);
        } else {
            $sql = "INSERT INTO products (product_code, product_name, product_color, product_image, product_quantity,product_price,product_type_id, product_category_id) VALUES ('$product_code', '$product_name', '$product_color', '$product_image','$product_quantity','$product_price','$product_type_id', '$product_category_id')";

            if (mysqli_query($conn, $sql)) {
                $message =  "Thêm loại sản phẩm thành công!";
                echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
            } else {
                $message =  "Thêm loại sản phẩm thất bại";
                echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
            }
        }
    }

    public function xoaSanPham($conn, $product_id)
    {
        $message = "Xóa sản phẩm thành công!";
        $sql = "DELETE FROM products WHERE product_id = $product_id";

        if (mysqli_query($conn, $sql)) {
            echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
        } else {
            $message = "Xóa loại sản phẩm thất bại!";
            echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
        }
    }

    public function laySanPham($conn)
    {
        $sql = "SELECT * FROM products";
        $result = mysqli_query($conn, $sql);

        $data = array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function suaSanPham($conn, $product_id, $product_code, $product_name, $product_color,$product_image, $product_price, $product_type_id, $product_category_id)
    {
        $message = "Chỉnh sửa sản phẩm thành công!";
        $sql = "UPDATE products SET product_code = '$product_code', product_name = '$product_name', product_color = '$product_color', product_image = '$product_image', product_price = $product_price, product_type_id = $product_type_id, product_category_id = $product_category_id WHERE product_id = $product_id";

        if (mysqli_query($conn, $sql)) {
            echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
        } else {
            $message = "Chỉnh sửa sản phẩm thất bại!";
            echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
        }
    }

    public function timSanPham($conn, $searchQuery)
    {
        $searchQuery = mysqli_real_escape_string($conn, $searchQuery);
        $searchQueryLowerCase = strtolower($searchQuery);
        $sql = "SELECT * FROM products WHERE LOWER(product_name) LIKE '%$searchQueryLowerCase%'";
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