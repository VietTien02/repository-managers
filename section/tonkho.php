<?php require "./connectdb.php" ?>
<div class="head-page">
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
                <th scope="col" class="text-center bg-primary text-light">Tên sản phẩm</th>
                <th scope="col" class="text-center bg-primary text-light">Số lượng trong kho</th>
            </tr>
        </thead>
        <tbody>
            <?php
            require './object/obj_san_pham.php';
            $obj = new obj_sanpham();
            $data = $obj->laySanPham($conn);
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["search"])) {
            $searchQuery = $_POST['search_query'];
            $data = $obj->timSanPham($conn, $searchQuery);
        }

            foreach ($data as $row) {
                echo '<tr>';
                echo '<td class="text-center">' . $row['product_id'] . '</td>';
                echo '<td class="text-center">' . $row['product_name'] . '</td>';
                echo '<td class="text-center">' . $row['product_quantity'] . '</td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>