<?php require "./handle/checkRole.php" ?>
<?php require "./connectdb.php" ?>
<?php
require './object/obj_loai_sp.php';
$obj = new obj_loaisanpham();
if (isset($_POST['themLoaiSanPham'])) {
    $type_name = $_POST['type_name'];

    try {
        $obj->themLoaiSanPham($conn, $type_name);
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
    echo 'Không được phép xóa do loại sản phẩm đang tồn tại ở dữ liệu kho';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}

if (isset($_GET['delete_success']) && $_GET['delete_success'] === 'true') {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo 'Đã xóa loại sản phẩm thành công.';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}
?>
<div class="head-page">
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#themLoaiSanPhamModal">Thêm loại sản
        phẩm</button>
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
        <thead class="thead-dark">
            <tr>
                <th scope="col" class="text-center bg-primary text-light">ID</th>
                <th scope="col" class="text-center bg-primary text-light">Tên loại sản phẩm</th>
                <th scope="col" class="text-center bg-primary text-light">Xem sản phẩm thuộc loại</th>
                <th scope="col" class="text-center bg-primary text-light" colspan="2">Cập nhật</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $data = $obj->layLoaiSanPham($conn);
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["search"])) {
                $searchQuery = $_POST['search_query'];
                $data = $obj->timLoaiSanPham($conn, $searchQuery);
            }

            foreach ($data as $row) {
                echo '<tr>';
                echo '<td class="text-center">' . $row['type_id'] . '</td>';
                echo '<td class="text-center">' . $row['type_name'] . '</td>';
                echo '<td class="text-center"><a href="#" class="view" data-bs-toggle="modal" data-bs-target="#xemSanPhamModal' . $row['type_id'] . '">Xem</a></td>';
                echo '<td class="text-center edit"><a href="#" data-bs-toggle="modal" data-bs-target="#suaLoaiSanPhamModal' . $row['type_id'] . '">Sửa</a></td>';
                echo '<td class="text-center remove"><a href="handle/xoa_loai_san_pham.php?type_id=' . $row['type_id'] . '">Xóa</a></td>';
                echo '</tr>';

                /* modal */
                echo '<div class="modal fade" id="suaLoaiSanPhamModal' . $row['type_id'] . '" tabindex="-1" aria-labelledby="suaLoaiSanPhamModalLabel' . $row['type_id'] . '" aria-hidden="true">';
                echo '<div class="modal-dialog">';
                echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo '<h5 class="modal-title" id="suaLoaiSanPhamModalLabel' . $row['type_id'] . '">Sửa loại sản phẩm</h5>';
                echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                echo '</div>';
                echo '<div class="modal-body">';
                echo '<form method="post" action="handle/sua_loai_san_pham.php">';
                echo '<input type="hidden" name="type_id" value="' . $row['type_id'] . '">';
                echo '<div class="mb-3">';
                echo '<label for="type_name" class="form-label">Tên loại sản phẩm</label>';
                echo '<input type="text" class="form-control" id="type_name" name="type_name" value="' . $row['type_name'] . '" required>';
                echo '</div>';
                echo '<button type="submit" name="suaLoaiSanPham" class="btn btn-primary">Lưu</button>';
                echo '</form>';
                echo '</div>';
                echo '</div>';
                echo '</div>';
                echo '</div>';

                $type_id = $row['type_id'];
                $productQuery = "SELECT * FROM products WHERE product_type_id = $type_id";
                $products = $conn->query($productQuery);
                /* modal xem chi tiết sản phẩm */
                echo '<div class="modal fade" id="xemSanPhamModal' . $row['type_id'] . '" tabindex="-1" aria-labelledby="xemSanPhamModalLabel' . $row['type_id'] . '" aria-hidden="true">';
                echo '<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 550px;">';
                echo '<div class="modal-content">';
                echo '<div class="modal-header">';
                echo '<h5 class="modal-title" id="xemSanPhamModalLabel' . $row['type_id'] . '">Xem chi tiết sản phẩm</h5>';
                echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
                echo '</div>';
                echo '<div class="modal-body">';
                echo '<ul class="list-group" style="max-height: 400px; overflow-y: scroll;">';

                if ($products->num_rows > 0) {
                    while ($product = $products->fetch_assoc()) {
                        echo '<li class="list-group-item">';
                        echo '<h6 class="mb-1">Mã sản phẩm: ' . $product['product_code'] . '</h6>';
                        echo '<div class="d-flex justify-content-between">';
                        echo '<span>Tên sản phẩm: ' . $product['product_name'] . '</span>';
                        echo '<span class="fw-bold">Giá sản phẩm: ' . number_format($product['product_price'], 0, ',', '.') . '.000 VND VND</span>';
                        echo '</div>';
                        echo '</li>';
                    }
                } else {
                    echo '<li class="list-group-item">Không có sản phẩm thuộc loại sản phẩm này.</li>';
                }

                echo '</ul>';
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
                <h5 class="modal-title" id="themLoaiSanPhamModalLabel">Thêm loại sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="type_name" class="form-label">Tên loại sản phẩm</label>
                        <input type="text" class="form-control" id="type_name" name="type_name" required>
                    </div>
                    <button type="submit" name="themLoaiSanPham" class="btn btn-primary">Thêm</button>
                </form>
            </div>
        </div>
    </div>
</div>