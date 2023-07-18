<?php
require '../connectdb.php';
require '../object/obj_user.php';

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $obj = new obj_users();
    try {
        $obj->xoaUser($conn, $user_id);
        header('Location: ../index.php?p=user&delete_success=true');
        exit();
    } catch (Exception $e) {
        header('Location: ../index.php?p=user&delete_error=true');
        exit();
    }
}

header('Location: ../index.php?p=user');
exit();
?>