<?php require "./handle/checkRole.php" ?>
<?php require "./connectdb.php" ?>
<?php
require './object/obj_user.php';
$obj = new obj_users();
if (isset($_POST['themUser'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    try {
        $obj->themUser($conn, $username, $password, $role);
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    } catch (Exception $e) {
        echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
        echo $e->getMessage();
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        echo '</div>';
    }
}
?>
<?php
if (isset($_GET['delete_error']) && $_GET['delete_error'] === 'true') {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    echo 'Không được phép xóa do tài khoản đang tồn tại ở dữ liệu kho';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}

if (isset($_GET['delete_success']) && $_GET['delete_success'] === 'true') {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo 'Đã xóa tài khoản thành công.';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}
?>
<div class="head-page">
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#themLoaiSanPhamModal">Thêm tài
        khoản</button>
    <form method="post" action="">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="Search" name="search_query">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <input type="hidden" name="search" />
    </form>
    <?php include('account.php') ?>
</div>
<table class="table">
    <thead>
        <tr>
            <th scope="col" class="text-center bg-primary text-light">ID</th>
            <th scope="col" class="text-center bg-primary text-light">Username</th>
            <th scope="col" class="text-center bg-primary text-light">Password</th>
            <th scope="col" class="text-center bg-primary text-light">Role</th>
            <th scope="col" colspan="2" class="text-center bg-primary text-light">Cập nhật</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $data = $obj->layUser($conn);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["search"])) {
            $searchQuery = $_POST['search_query'];
            $data = $obj->timUser($conn, $searchQuery);
        }

        foreach ($data as $row) {
            echo '<tr>';
            echo '<td class="text-center">' . $row['user_id'] . '</td>';
            echo '<td class="text-center">' . $row['username'] . '</td>';
            echo '<td class="text-center">' . $row['password'] . '</td>';
            echo '<td class="text-center">' . $row['role'] . '</td>';
            echo '<td class="text-center edit"><a href="#" data-bs-toggle="modal" data-bs-target="#suaLoaiSanPhamModal' . $row['user_id'] . '">Sửa</a></td>';
            echo '<td class="text-center remove"><a href="handle/xoa_user.php?user_id=' . $row['user_id'] . '">Xóa</a></td>';
            echo '</tr>';

            /* modal */
            echo '<div class="modal fade" id="suaLoaiSanPhamModal' . $row['user_id'] . '" tabindex="-1" aria-labelledby="suaLoaiSanPhamModalLabel' . $row['user_id'] . '" aria-hidden="true">';
            echo '<div class="modal-dialog">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="suaLoaiSanPhamModalLabel' . $row['user_id'] . '">Sửa tài khoản</h5>';
            echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<form method="post" action="handle/sua_user.php">';
            echo '<input type="hidden" name="user_id" value="' . $row['user_id'] . '">';
            echo '<div class="mb-3">';
            echo '<label for="username" class="form-label">Username</label>';
            echo '<input type="text" class="form-control" id="username" name="username" value="' . $row['username'] . '" required>';
            echo '</div>';
            echo '<div class="mb-3">';
            echo '<label for="password" class="form-label">Password</label>';
            echo '<input type="text" class="form-control" id="password" name="password" value="' . $row['password'] . '" required>';
            echo '</div>';
            echo '<div class="mb-3">';
            echo '<label for="role" class="form-label">Role</label>';
            echo '<select class="form-select" id="role" name="role" required>';
            echo '<option value="admin" ' . ($row['role'] === 'admin' ? 'selected' : '') . '>Admin</option>';
            echo '<option value="quản lí kho" ' . ($row['role'] === 'quản lí kho' ? 'selected' : '') . '>Quản lí kho</option>';
            echo '</select>';
            echo '</div>';
            echo '<button type="submit" name="suaUser" class="btn btn-primary">Lưu</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </tbody>
</table>


<div class="modal fade" id="themLoaiSanPhamModal" tabindex="-1" aria-labelledby="themLoaiSanPhamModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="themLoaiSanPhamModalLabel">Thêm tài khoản</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" action="index.php?p=user">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="text" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="admin">Admin</option>
                            <option value="quản lí kho">Quản lí kho</option>
                        </select>
                    </div>
                    <button type="submit" name="themUser" class="btn btn-primary">Thêm</button>
                </form>
            </div>
        </div>
    </div>
</div>