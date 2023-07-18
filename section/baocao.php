<style>
table {
    border-collapse: collapse;
    width: 100%;
    table-layout: fixed;
}

th,
td {
    padding: 10px;
    border: 1px solid #ddd;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

th {
    background-color: #f2f2f2;
    font-weight: bold;
}

tr:nth-child(even) {
    background-color: #f9f9f9;
}
</style>
<?php require "./handle/checkRole.php" ?>
<?php require "./connectdb.php" ?>
<div class="head-page">
    <?php include('filter.php') ?>
    <?php include('account.php') ?>
</div>
<?php
$filterDate = isset($_POST['filter_date']) ? $_POST['filter_date'] : null;
$filterMonth = isset($_POST['filter_month']) ? $_POST['filter_month'] : null;
$filterYear = isset($_POST['filter_year']) ? $_POST['filter_year'] : null;

$query = "SELECT 
    (SELECT COUNT(*) FROM products WHERE product_quantity > 0";

if (!empty($filterDate) || !empty($filterMonth) || !empty($filterYear)) {
    $query .= " AND product_id IN (
        SELECT DISTINCT inventory_items.product_id
        FROM inventory_items
        JOIN inventory ON inventory_items.inventory_id = inventory.inventory_id
        WHERE 1=1";
    if (!empty($filterDate)) {
        $query .= " AND DATE(inventory.inventory_date) = '$filterDate'";
    }
    if (!empty($filterMonth)) {
        $query .= " AND MONTH(inventory.inventory_date) = '$filterMonth'";
    }
    if (!empty($filterYear)) {
        $query .= " AND YEAR(inventory.inventory_date) = '$filterYear'";
    }
    $query .= ")";
}

$query .= ") AS totalProductsInStock,
    (SELECT COUNT(*) FROM inventory WHERE inventory_type = 'out'";

if (!empty($filterDate)) {
    $query .= " AND DATE(inventory.inventory_date) = '$filterDate'";
}
if (!empty($filterMonth)) {
    $query .= " AND MONTH(inventory.inventory_date) = '$filterMonth'";
}
if (!empty($filterYear)) {
    $query .= " AND YEAR(inventory.inventory_date) = '$filterYear'";
}
$query .= ") AS totalProductsSoldOut,
    (SELECT SUM(products.product_price * inventory_items.inventory_quantity) FROM products 
    JOIN inventory_items ON products.product_id = inventory_items.product_id
    JOIN inventory ON inventory_items.inventory_id = inventory.inventory_id 
    WHERE inventory.inventory_type = 'in'";

if (!empty($filterDate)) {
    $query .= " AND DATE(inventory.inventory_date) = '$filterDate'";
}
if (!empty($filterMonth)) {
    $query .= " AND MONTH(inventory.inventory_date) = '$filterMonth'";
}
if (!empty($filterYear)) {
    $query .= " AND YEAR(inventory.inventory_date) = '$filterYear'";
}
$query .= ") AS totalImportValue,
    (SELECT SUM(products.product_price * inventory_items.inventory_quantity) FROM products 
    JOIN inventory_items ON products.product_id = inventory_items.product_id
    JOIN inventory ON inventory_items.inventory_id = inventory.inventory_id 
    WHERE inventory.inventory_type = 'out'";

if (!empty($filterDate)) {
    $query .= " AND DATE(inventory.inventory_date) = '$filterDate'";
}
if (!empty($filterMonth)) {
    $query .= " AND MONTH(inventory.inventory_date) = '$filterMonth'";
}
if (!empty($filterYear)) {
    $query .= " AND YEAR(inventory.inventory_date) = '$filterYear'";
}
$query .= ") AS totalExportValue";

$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

$totalProductsInStock = isset($row['totalProductsInStock']) ? $row['totalProductsInStock'] : 0;
$totalProductsSoldOut = isset($row['totalProductsSoldOut']) ? $row['totalProductsSoldOut'] : 0;
$totalImportValue = isset($row['totalImportValue']) ? number_format($row['totalImportValue'], 0, ',', '.') . ',000 VND' : 0;
$totalExportValue = isset($row['totalExportValue']) ? number_format($row['totalExportValue'], 0, ',', '.') . ',000 VND' : 0;

echo "<table id='invoice'>";
echo "<tr><th>Sản phẩm còn lại trong kho</th><td>{$totalProductsInStock}</td></tr>";
echo "<tr><th>Sản phẩm đã xuất kho</th><td>{$totalProductsSoldOut}</td></tr>";
echo "<tr><th>Giá trị hàng hóa đã nhập</th><td>{$totalImportValue}</td></tr>";
echo "<tr><th>Giá trị hàng hóa đã xuất</th><td>{$totalExportValue}</td></tr>";
echo "</table>";
echo '<button id="export-pdf" class="btn btn-primary float-end mt-3">Xuất báo cáo</button>';

mysqli_free_result($result);
mysqli_close($conn);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
const button = document.getElementById("export-pdf");

function generatePDF() {
    const element = document.getElementById("invoice");
    html2pdf().from(element).save();
}
button.addEventListener("click", generatePDF);
</script>