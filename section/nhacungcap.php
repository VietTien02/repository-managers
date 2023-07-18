<?php require "./handle/checkRole.php" ?>
<?php require "./connectdb.php" ?>
<?php
require './object/obj_nha_cung_cap.php';
$obj = new obj_nhacungcap();
if (isset($_POST['themLoaiSanPham'])) {
    $supplier_name = $_POST['supplier_name'];
    $supplier_phone = $_POST['supplier_phone'];
    $supplier_address = $_POST['supplier_address'];
    
    try {
        $obj->themNhaCungCap($conn, $supplier_name, $supplier_phone, $supplier_address);
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
    echo 'Không được phép xóa do nhà cung cấp đang tồn tại ở dữ liệu kho';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}

if (isset($_GET['delete_success']) && $_GET['delete_success'] === 'true') {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo 'Đã xóa nhà cung cấp thành công.';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}
?>
<div class="head-page">
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#themLoaiSanPhamModal">Thêm nhà cung
        cấp</button>
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
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th scope="col" class="text-center bg-primary text-light">ID</th>
                <th scope="col" class="text-center bg-primary text-light">Tên nhà cung cấp</th>
                <th scope="col" class="text-center bg-primary text-light">Số điện thoại</th>
                <th scope="col" class="text-center bg-primary text-light">Địa chỉ</th>
                <th scope="col" class="text-center bg-primary text-light" colspan="2">Cập nhật</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $data = $obj->layNhaCungCap($conn);
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["search"])) {
                $searchQuery = $_POST['search_query'];
                $data = $obj->timNhaCungCap($conn, $searchQuery);
            }

            foreach ($data as $row) {
                echo '<tr>';
                echo '<td class="text-center">' . $row['supplier_id'] . '</td>';
                echo '<td class="text-center">' . $row['supplier_name'] . '</td>';
                echo '<td class="text-center">' . $row['supplier_phone'] . '</td>';
                echo '<td class="text-center">' . $row['supplier_address'] . '</td>';
                echo '<td class="text-center edit"><a href="#" data-bs-toggle="modal" data-bs-target="#suaLoaiSanPhamModal' . $row['supplier_id'] . '">Sửa</a></td>';
                echo '<td class="text-center remove"><a href="handle/xoa_nha_cung_cap.php?supplier_id=' . $row['supplier_id'] . '">Xóa</a></td>';
                echo '</tr>';

                /* modal */
                echo '<div class="modal fade" id="suaLoaiSanPhamModal' . $row['supplier_id'] . '" tabindex="-1" aria-labelledby="suaLoaiSanPhamModalLabel' . $row['supplier_id'] . '" aria-hidden="true">';
                echo '<div class="modal-dialog">';
                echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo '<h5 class="modal-title" id="suaLoaiSanPhamModalLabel' . $row['supplier_id'] . '">Sửa nhà cung cấp</h5>';
                echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                echo '</div>';
                echo '<div class="modal-body">';
                echo '<form method="post" action="handle/sua_nha_cung_cap.php">';
                echo '<input type="hidden" name="supplier_id" value="' . $row['supplier_id'] . '">';
                echo '<div class="mb-3">';
                echo '<label for="supplier_name" class="form-label">Tên nhà cung cấp</label>';
                echo '<input type="text" class="form-control" id="supplier_name" name="supplier_name" value="' . $row['supplier_name'] . '"  required>';
                echo '</div>';
                echo '<div class="mb-3">';
                echo '<label for="supplier_phone" class="form-label">Số điện thoại nhà cung cấp</label>';
                echo '<input type="text" class="form-control" id="supplier_phone" name="supplier_phone" value="' . $row['supplier_phone'] . '" pattern="^(0|\+84)[1-9]\d{8}$" required>';
                echo '</div>';
                echo '<div class="mb-3">';
                echo '<label for="supplier_name" class="form-label">Địa chỉ nhà cung cấp</label>';
                echo '<input type="text" class="form-control" id="supplier_address" name="supplier_address" value="' . $row['supplier_address'] . '" required>';
                echo '</div>';
                echo '<button type="submit" name="suaNhaCungCap" class="btn btn-primary">Lưu</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </tbody>
    </table>
</div>

<div class="modal fade" id="themLoaiSanPhamModal" tabindex="-1" aria-labelledby="themLoaiSanPhamModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="themLoaiSanPhamModalLabel">Thêm nhà cung cấp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="supplier_name" class="form-label">Tên nhà cung cấp</label>
                        <input type="text" class="form-control" id="supplier_name" name="supplier_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="supplier_phone" name="supplier_phone"
                            pattern="^(0|\+84)[1-9]\d{8}$" required>
                    </div>
                    <div class="mb-3">
                        <label for="supplier_address" class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" id="supplier_address" name="supplier_address" required>
                    </div>
                    <button type="submit" name="themLoaiSanPham" class="btn btn-primary">Thêm</button>
                </form>
            </div>
        </div>
    </div>
</div>