<?php
$host = "localhost";
$username = "root";
$password = "1234";
$dbname = "repository"; 


$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Kết nối đến cơ sở dữ liệu thất bại: " . mysqli_connect_error());
}