<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
include('./theme.php')
?>
<?php
$baseUrl = $_SERVER['PHP_SELF'];
?>