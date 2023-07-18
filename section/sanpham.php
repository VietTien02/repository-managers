<?php require "./handle/checkRole.php" ?>
<?php require "./connectdb.php" ?>
<?php
require './object/obj_san_pham.php';
$obj = new obj_sanpham();
if (isset($_POST['themSanPham'])) {
    $product_code = $_POST['product_code'];
    $product_name = $_POST['product_name'];
    $product_color = $_POST['product_color'];
    $product_image = $_POST['product_image'];
  
    $product_quantity = 0;
    $product_price = $_POST['product_price'];
    $product_type_id = $_POST['product_type_id'];
    $product_category_id = $_POST['product_category_id'];

    try {
        $obj->themSanPham($conn, $product_code, $product_name, $product_color, $product_image, $product_quantity, $product_price, $product_type_id, $product_category_id);
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
    echo 'Không được phép xóa do sản phẩm đang tồn tại ở dữ liệu kho';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}

if (isset($_GET['delete_success']) && $_GET['delete_success'] === 'true') {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
    echo 'Đã xóa sản phẩm thành công.';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}
?>
<div class="head-page">
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#themLoaiSanPhamModal">Thêm sản
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
<script>
    function showImage() {
        var fileInput = document.getElementById("inputGroupFile02");
        var file = fileInput.files[0];
         console.log(fileInput.value);
         console.log(file);
        if (file) {
            var reader = new FileReader();

            reader.onload = function(event) {
                var imageUrl = event.target.result;
                var imgElement = document.createElement("img");
                imgElement.src = imageUrl;
                imgElement.alt = "Hình ảnh";
                document.getElementById("imageContainer").innerHTML = ""; // Xóa hình ảnh hiện tại (nếu có)
                document.getElementById("imageContainer").appendChild(imgElement);
                imgElement.classList.add("w-10p");
                console.log(imageUrl);
            };

            reader.readAsDataURL(file);
        }
    }

    function handleInputChange() {
        showImage();
    }
</script>
<table class="table">
    <thead>
        <tr>
            <th scope="col" class="text-center bg-primary text-light">ID</th>
            <th scope="col" class="text-center bg-primary text-light">Mã Loại SP</th>
            <th scope="col" class="text-center bg-primary text-light">Mã Danh mục</th>
            <th scope="col" class="text-center bg-primary text-light">Mã</th>
            <th scope="col" class="text-center bg-primary text-light">Tên</th>
            <th scope="col" class="text-center bg-primary text-light">Color</th>
            <th scope="col" class="text-center bg-primary text-light">Ảnh</th>
            <th scope="col" class="text-center bg-primary text-light">Số lượng</th>
            <th scope="col" class="text-center bg-primary text-light">Giá</th>
            <th scope="col" colspan="2" class="text-center bg-primary text-light">Cập nhật</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $data = $obj->laySanPham($conn);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["search"])) {
            $searchQuery = $_POST['search_query'];
            $data = $obj->timSanPham($conn, $searchQuery);
        }


        foreach ($data as $row) {
            echo '<tr>';
            echo '<td class="text-center">' . $row['product_id'] . '</td>';
            echo '<td class="text-center">' . $row['product_type_id'] . '</td>';
            echo '<td class="text-center">' . $row['product_category_id'] . '</td>';
            echo '<td class="text-center">' . $row['product_code'] . '</td>';
            echo '<td class="text-center">' . $row['product_name'] . '</td>';
            echo '<td class="text-center">' . $row['product_color'] . '</td>';
            echo '<td class="text-center"> <img class="w-10p" src="images/' . $row['product_image'] . '" alt=""></td>';

            echo '<td class="text-center">' . $row['product_quantity'] . '</td>';
            echo '<td class="text-center">' . number_format($row['product_price'], 0, ',', '.') . ',000 VND</td>';
            echo '<td class="text-center edit"><a href="#" data-bs-toggle="modal" data-bs-target="#suaLoaiSanPhamModal' . $row['product_id'] . '">Sửa</a></td>';
            echo '<td class="text-center remove"><a href="handle/xoa_san_pham.php?product_id=' . $row['product_id'] . '">Xóa</a></td>';
            echo '</tr>';

            /* model */
            echo '<div class="modal fade" id="suaLoaiSanPhamModal' . $row['product_id'] . '" tabindex="-1" aria-labelledby="themSanPhamModalLabel' . $row['product_id'] . '" aria-hidden="true">';
            echo '<div class="modal-dialog">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="themSanPhamModalLabel' . $row['product_id'] . '">Sửa sản phẩm</h5>';
            echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
            echo '</div>';
            echo '<div class="modal-body">';
            echo '<form method="post" action="handle/sua_san_pham.php">';
            echo '<input type="hidden" name="product_id" value="' . $row['product_id'] . '">';
            echo '<div class="mb-3">';
            echo '<label for="product_name" class="form-label">Tên sản phẩm</label>';
            echo '<input type="text" class="form-control" id="product_name" name="product_name" required value="' . $row['product_name'] . '">';
            echo '</div>';
            echo '<div class="mb-3">';
            echo '<label for="product_code" class="form-label">Mã sản phẩm</label>';
            echo '<input type="text" class="form-control" id="product_code" name="product_code" required value="' . $row['product_code'] . '">';
            echo '</div>';
            echo '<div class="mb-3">';
            echo '<label for="product_color" class="form-label">Màu sắc</label>';
            echo '<input type="text" class="form-control" id="product_color" name="product_color" required value="' . $row['product_color'] . '">';
            echo '</div>';
            
            echo '<div class="mb-3">';
            echo '<label for="product_image" class="form-label">Ảnh</label>';
            echo '<input type="file" class="form-control" id="product_image" name="product_image" required value="' . $row['product_image'] . '">';
            echo '</div>';

            echo '<div class="mb-3">';
            echo '<label for="product_price" class="form-label">Giá sản phẩm</label>';
            echo '<input type="number" class="form-control" id="product_price" name="product_price" required value="' . $row['product_price'] . '">';
            echo '</div>';
            echo '<div class="mb-3">';
            echo '<label for="product_type_id" class="form-label">Loại sản phẩm</label>';
            echo '<select class="form-select" id="product_type_id" name="product_type_id" required>';

            $query = "SELECT type_id, type_name FROM product_types";
            $result = mysqli_query($conn, $query);
            while ($type_row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $type_row['type_id'] . '">' . $type_row['type_name'] . '</option>';
            }
            echo '</select>';
            echo '</div>';
            echo '<div class="mb-3">';
            echo '<label for="product_supplier_id" class="form-label">Nhà cung cấp</label>';
            echo '<select class="form-select" id="product_supplier_id" name="product_supplier_id" required>';

            $query = "SELECT supplier_id, supplier_name FROM suppliers";
            $result = mysqli_query($conn, $query);
            while ($supplier_row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $supplier_row['supplier_id'] . '">' . $supplier_row['supplier_name'] . '</option>';
            }
            echo '</select>';
            echo '</div>';
            echo '<div class="mb-3">';
            echo '<label for="product_category_id" class="form-label">Danh mục sản phẩm</label>';
            echo '<select class="form-select" id="product_category_id" name="product_category_id" required>';

            $query = "SELECT category_id, category_name FROM categories";
            $result = mysqli_query($conn, $query);
            while ($category_row = mysqli_fetch_assoc($result)) {
                echo '<option value="' . $category_row['category_id'] . '">' . $category_row['category_name'] . '</option>';
            }
            echo '</select>';
            echo '</div>';
            echo '<button type="submit" name="suaSanPham" class="btn btn-primary">Sửa</button>';
            echo '</form>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </tbody>
</table>

<script>
    function showImage() {
        var fileInput = document.getElementById("inputGroupFile01");
        var file = fileInput.files[0];
         console.log(fileInput.value);
         console.log(file);
        if (file) {
            var reader = new FileReader();

            reader.onload = function(event) {
                var imageUrl = event.target.result;
                var imgElement = document.createElement("img");
                imgElement.src = imageUrl;
                imgElement.alt = "Hình ảnh";
                document.getElementById("imageContainer").innerHTML = ""; // Xóa hình ảnh hiện tại (nếu có)
                document.getElementById("imageContainer").appendChild(imgElement);
                imgElement.classList.add("w-10p");
                console.log(imageUrl);
            };

            reader.readAsDataURL(file);
        }
    }

    function handleInputChange() {
        showImage();
    }
</script>
</head>
<div class="modal fade" id="themLoaiSanPhamModal" tabindex="-1" aria-labelledby="themSanPhamModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="themSanPhamModalLabel">Thêm sản phẩm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="product_code" class="form-label">Mã sản phẩm</label>
                        <input type="text" class="form-control" id="product_code" name="product_code" required>
                    </div>

                    <div class="mb-3">
                        <label for="product_name" class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                    </div>

                    <div class="mb-3">
                        <label for="product_color" class="form-label">Màu sắc</label>
                        <input type="text" class="form-control" id="product_color" name="product_color" required>
                    </div>
                    <div class="mb-3">
                        <label for="product_color" class="form-label">hình ảnh</label>

                        <div class="input-group mb-3">

                            <input type="file" class="form-control" id="inputGroupFile01" name="product_image" oninput="handleInputChange()">
                        </div>
                        <div id="imageContainer" class="mb-3">

                        </div>

                    </div>

                    <div class="mb-3">
                        <label for="product_price" class="form-label">Giá sản phẩm</label>
                        <input type="number" class="form-control" id="product_price" name="product_price" required>
                    </div>

                    <div class="mb-3">
                        <label for="product_type_id" class="form-label">Loại sản phẩm</label>
                        <select class="form-select" id="product_type_id" name="product_type_id" required>
                            <?php
                            $query = "SELECT type_id, type_name FROM product_types";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<option value="' . $row['type_id'] . '">' . $row['type_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="product_category_id" class="form-label">Danh mục sản phẩm</label>
                        <select class="form-select" id="product_category_id" name="product_category_id" required>
                            <?php
                            $query = "SELECT category_id, category_name FROM categories";
                            $result = mysqli_query($conn, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<option value="' . $row['category_id'] . '">' . $row['category_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <button type="submit" name="themSanPham" class="btn btn-primary">Thêm</button>
                </form>
            </div>
        </div>
    </div>
</div>