<?php echo '<div class="modal fade" id="printModal' . $row['inventory_id'] . '" tabindex="-1" aria-labelledby="chiTietModalLabel" aria-hidden="true">';
            echo '<div class="modal-dialog modal-dialog-centered modal-lg">';
            echo '<div class="modal-content">';
            echo '<div class="modal-header">';
            echo '<h5 class="modal-title" id="chiTietModalLabel">In hóa đơn</h5>';
            echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
            echo '</div>';
            echo '<div class="modal-body" id="inventory_out">';
            echo '<div class="text-center">';
            echo '<img src="assets/Logo.jpg" alt="Logo công ty">';
            echo '<p class="mt-3">Đồ gia dụng nhà bếp chính hãng</p>';
            echo '</div>';
            echo '<p class="mt-3"><em class="text-decoration-underline">Thông tin đơn hàng</em></p>';
            echo '<hr>';
            echo '<div class="row">';
            echo '<div class="col-md-6">';
            echo '<p class="mb-3"><strong>ID phiếu xuất kho:</strong> ' . $row['inventory_id'] . '</p>';
            echo '<p class="mb-3"><strong>Ngày xuất kho:</strong> ' . $row['inventory_date'] . '</p>';
            echo '<p class="mb-3"><strong>ID Người xuất kho:</strong> ' . $row['user_id'] . '</p>';
            echo '<p class="mb-3"><strong>Tổng tiền:</strong> ' . $inventory_price . '</p>';
            if ($status === 'paid') {
    $statusVietnamese = 'Đã thanh toán';
} elseif ($status === 'unpaid') {
    $statusVietnamese = 'Chưa thanh toán';
} elseif ($status === 'available') {
    $statusVietnamese = 'Có sẵn';
}
echo '<p class="mb-3"><strong>Trạng thái:</strong> ' . $statusVietnamese . '</p>';
            if (!empty($row['supplier'])) {
    echo '<p class="mb-3"><strong>Khách hàng:</strong> ' . $row['supplier'] . '</p>';
}else{
    $supplier_id = $row['supplier_id'];

    // Truy vấn bảng suppliers để lấy supplier_name dựa trên supplier_id
    $supplierQuery = "SELECT supplier_name FROM suppliers WHERE supplier_id = $supplier_id";
    $supplierResult = mysqli_query($conn, $supplierQuery);
    $supplierRow = mysqli_fetch_assoc($supplierResult);
    $supplier_name = $supplierRow['supplier_name'];

    echo '<p class="mb-3"><strong>Khách hàng:</strong> ' . $supplier_name . '</p>';
}
            echo '</div>';
            echo '</div>';

            echo '<p class="mt-3"><em class="text-decoration-underline">Chi tiết đơn hàng</em></p>';
            echo '<hr>';

            // Truy vấn và hiển thị thông tin chi tiết sản phẩm
            $query = "SELECT inventory_items.*, products.product_name, products.product_price FROM inventory_items
        INNER JOIN products ON inventory_items.product_id = products.product_id
        WHERE inventory_items.inventory_id = " . $row['inventory_id'];
            $result = mysqli_query($conn, $query);
            $inventory_items = mysqli_fetch_all($result, MYSQLI_ASSOC);

            echo '<ul class="list-group">';
            foreach ($inventory_items as $item) {
                echo '<li class="list-group-item">';
                echo '<h6 class="mb-1">Tên sản phẩm: ' . $item['product_name'] . '</h6>';
                echo '<div class="d-flex justify-content-between">';
                echo '<span>Giá: ' . $item['product_price'] . '</span>';
                echo '<span class="fw-bold">Số lượng: ' . $item['inventory_quantity'] . '</span>';
                echo '</div>';
                echo '</li>';
            }
            echo '</ul>';
        
            echo '</div>';
            echo '<button type="button" class="btn btn-primary mt-3" id="print_inventory"><i class="fa-solid fa-print"></i> In hóa đơn</button>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        ?>