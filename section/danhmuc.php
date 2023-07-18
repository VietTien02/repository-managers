<?php require "./handle/checkRole.php" ?>
<?php require "./connectdb.php" ?>
<?php
require './object/obj_danh_muc.php';
$obj = new obj_danhmuc();
if (isset($_POST['themLoaiSanPham'])) {
    $category_name = $_POST['category_name'];

    try {
        $obj->themDanhMuc($conn, $category_name);
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
    echo 'Không được phép xóa do danh mục đang tồn tại ở dữ liệu kho';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}

if (isset($_GET['delete_success']) && $_GET['delete_success'] === 'true') {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo 'Đã xóa danh mục thành công.';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}
?>
<div class="head-page">
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#themLoaiSanPhamModal">Thêm danh
        mục</button>
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
    <thead class="thead-dark">
        <tr>
            <th scope="col" class="text-center bg-primary text-light">ID</th>
            <th scope="col" class="text-center bg-primary text-light">Tên danh mục</th>
            <th scope="col" class="text-center bg-primary text-light">Xem sản phẩm thuộc danh mục</th>
            <th scope="col" colspan="2" class="text-center bg-primary text-light">Cập nhật</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $data = $obj->layDanhMuc($conn);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["search"])) {
            $searchQuery = $_POST['search_query'];
            $data = $obj->timDanhMuc($conn, $searchQuery);
        }

        foreach ($data as $row) {
            echo '<tr>';
            echo '<td class="text-center">' . $row['category_id'] . '</td>';
            echo '<td class="text-center">' . $row['category_name'] . '</td>';
            echo '<td class="text-center"><a href="#" class="view" data-bs-toggle="modal" data-bs-target="#xemSanPhamModal' . $row['category_id'] . '">Xem</a></td>';
            echo '<td class="text-center edit"><a href="#" data-bs-toggle="modal" data-bs-target="#suaLoaiSanPhamModal' . $row['category_id'] . '">Sửa</a></td>';
            echo '<td class="text-center remove"><a href="handle/xoa_danh_muc.php?category_id=' . $row['category_id'] . '">Xóa</a></td>';
            echo '</tr>';

            /* modal */
            echo '<div class="modal fade" id="suaLoaiSanPhamModal' . $row['category_id'] . '" tabindex="-1" aria-labelledby="suaLoaiSanPhamModalLabel' . $row['category_id'] . '" aria-hidden="true">';
            echo '<div class="modal-dialog">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="suaLoaiSanPhamModalLabel' . $row['category_id'] . '">Sửa danh mục</h5>';
            echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<form method="post" action="handle/sua_danh_muc.php">';
            echo '<input type="hidden" name="category_id" value="' . $row['category_id'] . '">';
            echo '<div class="mb-3">';
            echo '<label for="category_name" class="form-label">Tên danh mục</label>';
            echo '<input type="text" class="form-control" id="category_name" name="category_name" value="' . $row['category_name'] . '" required>';
            echo '</div>';
            echo '<button type="submit" name="suaDanhMuc" class="btn btn-primary">Lưu</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';

            $categoryId = $row['category_id'];
            $productQuery = "SELECT * FROM products WHERE product_category_id = $categoryId";
            $products = $conn->query($productQuery);
            /* modal xem chi tiết sản phẩm */
            echo '<div class="modal fade" id="xemSanPhamModal' . $row['category_id'] . '" tabindex="-1" aria-labelledby="xemSanPhamModalLabel' . $row['category_id'] . '" aria-hidden="true">';
            echo '<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 550px;">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="xemSanPhamModalLabel' . $row['category_id'] . '">Xem chi tiết sản phẩm</h5>';
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
                    echo '<span class="fw-bold">Giá sản phẩm: ' . number_format($product['product_price'], 0, ',', '.') . ',000 VND</span>';
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


<div class="modal fade" id="themLoaiSanPhamModal" tabindex="-1" aria-labelledby="themLoaiSanPhamModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="themLoaiSanPhamModalLabel">Thêm danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Tên loại sản phẩm</label>
                        <input type="text" class="form-control" id="category_name" name="category_name" required>
                    </div>
                    <button type="submit" name="themLoaiSanPham" class="btn btn-primary">Thêm</button>
                </form>
            </div>
        </div>
    </div>
</div>