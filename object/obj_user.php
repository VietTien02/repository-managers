<?php
class obj_users
{
    public $user_id;
    public $username;
    public $password;
    public $role;

    public function themUser($conn, $username, $password, $role)
    {
        $message = "Đã có username có tên là $username";
        $checkQuery = "SELECT * FROM users WHERE username = '$username'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) > 0) {
            throw new Exception($message);
        } else {
            $sql = "INSERT INTO users (username, password, role ) VALUES ('$username', '$password', '$role')";

            if (mysqli_query($conn, $sql)) {
                $message =  "Thêm user thành công!";
                echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
            } else {
                $message =  "Thêm user thất bại";
                echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
            }
        }
    }

    public function xoaUser($conn, $user_id)
    {
        $sql = "DELETE FROM users WHERE user_id = $user_id";
        mysqli_query($conn, $sql);
    }

    public function layUser($conn)
    {
        $sql = "SELECT * FROM users";
        $result = mysqli_query($conn, $sql);

        $data = array();
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function suaUser($conn, $user_id, $username, $password, $role)
    {
        $sql = "UPDATE users SET username = '$username', password = '$password', role='$role'  WHERE user_id = $user_id";

        mysqli_query($conn, $sql);
    }

    public function timUser($conn, $searchQuery)
    {
        $searchQuery = mysqli_real_escape_string($conn, $searchQuery);
        $searchQueryLowerCase = strtolower($searchQuery);
        $sql = "SELECT * FROM users WHERE LOWER(username) LIKE '%$searchQueryLowerCase%'";
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