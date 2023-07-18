<?php
echo '<div class="modal fade" id="chiTietModal_' . $row['inventory_id'] . '" tabindex="-1" aria-labelledby="chiTietModalLabel" aria-hidden="true">';
echo '<div class="modal-dialog modal-dialog-centered modal-lg">';
echo '<div class="modal-content">';
echo '<div class="modal-header">';
if (isset($page)) {
    if ($page === 'xuatkho') {
        echo '<h5 class="modal-title" id="chiTietModalLabel">Chi tiết phiếu xuất kho</h5>';
    } elseif ($page === 'nhapkho') {
        echo '<h5 class="modal-title" id="chiTietModalLabel">Chi tiết phiếu nhập kho</h5>';
    }
}
echo '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>';
echo '</div>';
echo '<div class="modal-body">';
echo '<p class="mt-3"><em class="text-decoration-underline">Thông tin đơn hàng</em></p>';
echo '<hr>';
echo '<div class="row">';
echo '<div class="col-md-6">';
echo '<p class="mb-3"><strong>ID phiếu nhập kho:</strong> ' . $row['inventory_id'] . '</p>';
echo '<p class="mb-3"><strong>Ngày nhập kho:</strong> ' . $row['inventory_date'] . '</p>';
echo '<p class="mb-3"><strong>ID Người nhập kho:</strong> ' . $row['user_id'] . '</p>';
echo '<p class="mb-3"><strong>Tổng tiền:</strong> ' . $inventory_price . '</p>';
$status = $row['inventory_status'];
$statusVietnamese = '';

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
    echo '<span class="fw-bold">Số lượng: ' .  $item['inventory_quantity'] . '</span>';
    echo '</div>';
    echo '</li>';
}
echo '</ul>';

echo '</div>';
echo '</div>';
echo '</div>';
echo '</div>';