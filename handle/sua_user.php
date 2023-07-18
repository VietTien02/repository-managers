<?php
require '../connectdb.php';
require '../object/obj_user.php';

if (isset($_POST['suaUser'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    $obj = new obj_users();
    $obj->suaUser($conn, $user_id, $username, $password, $role);
}

header('Location: ../index.php?p=user');
exit();
?>