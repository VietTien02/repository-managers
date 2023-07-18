<?php require "./connectdb.php";?>

<div class="head-page">
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../styles/main.css" rel="stylesheet" />
    <style>
        #company-info {
        font-family: Arial, sans-serif;
        margin: 50px 50px 50px 50px;
        }

        .company-name {
        font-style: italic;
        font-weight: bold;
        text-transform: uppercase;
        }

    </style>
    <title>Document</title>
</head>

<body>
    
<div class="account">
    <div class="user">
        
    </div>
    <div class="name">
        <h3></h3>    </div>
    <div class="logout">
       
    </div>
</div>
    <div class="container-xxl mt-3">
        <div class="row">
            <div class="col-3">
                <div class="border modal-tke text-white">
                    <div class="tke-left border-bottom mb-0">
                        <p class="mb-0 fw-bold"> Sản phẩm còn lại trong kho</p>
                    </div>
                    <div class="tke-right">
                        <p class="mt-2 fs-6 mb-0"><?php echo $totalProductsInStock ?></p>
                    </div>
                </div>
            </div>
            
            <div class="col-3">
                <div class="border modal-tke text-white">
                    <div class="tke-left border-bottom mb-0">
                        <p class="mb-0 fw-bold"> Giá trị hàng hóa đã nhập</p>
                    </div>
                    <div class="tke-right">
                        <p class="mt-2 fs-6 mb-0"><?php echo $totalImportValue ?></p>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="border modal-tke text-white">
                    <div class="tke-left border-bottom mb-0">
                        <p class="mb-0 fw-bold"> Giá trị hàng hóa đã xuất</p>
                    </div>
                    <div class="tke-right">
                        <p class="mt-2 fs-6 mb-0"><?php echo $totalExportValue ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="company-info">
        <h2>Giới Thiệu: </h2>
        <p>"Công ty <span class="company-name">Bán Đồ Gia Dụng CTD"</span> là một doanh nghiệp kinh doanh các sản phẩm gia dụng và tiện ích cho gia đình. <br></p>
        <p>Chúng tôi sử dụng hệ thống quản lí kho để theo dõi và điều hành hoạt động  lưu trữ và quản lí hàng hóa. <br>
        <p></p> Hệ thống này giúp công ty lưu trữ thông tin chi tiết về hàng hóa, kiểm soát việc nhập xuất kho và tối ưu hóa tồn kho. <br>
         Điều này giúp <span class="company-name">"Công ty CTD"</span> cung cấp sản phẩm đúng thời gian, tối ưu hóa quy trình và giảm thiểu lãng phí.</p>
    </div>

    
</body>

</html>