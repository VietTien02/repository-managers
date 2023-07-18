<?php global $conn;
require "./connectdb.php" ?>
<?php
if (isset($_GET['products']) && $_GET['products'] === 'false') {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
    echo 'Không được để trống sản phẩm';
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}
?>
<?php
require "./connectdb.php";
require './object/obj_kho.php';
$obj = new obj_kho();

if (isset($_POST['themPhieuNhapKho'])) {
    
    if (!isset($_POST['product_id'])) {
    $url = $_SERVER['REQUEST_URI'] . '&products=false';
    header('Location: ' . $url);
    exit;
}

    $product_ids = $_POST['product_id'];
    $product_quantitys = $_POST['product_quantity'];
    $inventory_type = 'out'; // Loại nhập kho là 'in'
    $inventory_quantity = $_POST['inventory_quantity'];
    $inventory_date = $_POST['inventory_date'];
    $inventory_prices = $_POST['inventory_price'];
    $supplier = $_POST['supplier'];
    $inventory_status = $_POST['inventory_status'];
    $username = $_SESSION['username'];
    $userQuery = "SELECT user_id FROM users WHERE username = '$username'";
    $userResult = mysqli_query($conn, $userQuery);
    $userRow = mysqli_fetch_assoc($userResult);
    $user_id = $userRow['user_id'];
    $total_price = 0;
    $error_flag = false;

    foreach ($product_ids as $key => $product_id) {
        $query = "SELECT product_name, product_price, product_quantity FROM products WHERE product_id = $product_id";
        $result = mysqli_query($conn, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $productName = $row['product_name'];
            $productPrice = $row['product_price'];
            $productQuantity = $row['product_quantity'];

            if ($product_quantitys[$key] > $productQuantity) {
                echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
                echo "Số lượng sản phẩm $productName muốn xuất lớn hơn số lượng hiện có.";
                echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
                echo '</div>';
                $error_flag = true;
                break;
            }

            $total_price += $productPrice * $product_quantitys[$key];
        }
    }
    if (!$error_flag) { // Nếu không có lỗi, thực hiện các lệnh tiếp theo
        $inventory_id = $obj->xuatKho($conn, $inventory_type, $inventory_date, $total_price, $user_id, $supplier, $inventory_status);

        foreach ($product_ids as $key => $product_id) {
            $product_quantity = $product_quantitys[$key];
            $query = "SELECT product_name, product_price FROM products WHERE product_id = $product_id";
            $result = mysqli_query($conn, $query);
            if ($result && mysqli_num_rows($result) > 0) {
                $row = mysqli_fetch_assoc($result);
                $productName = $row['product_name'];
                $productPrice = $row['product_price'];

                $obj->themSanPhamVaoPhieuXuat($conn, $inventory_id, $product_id, $product_quantity);
            }
        }

        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();
    }
}
?>
<div class="head-page">
    <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#themLoaiSanPhamModal">Thêm phiếu xuất
        kho</button>
    <?php include('filter.php') ?>
    <?php include('account.php') ?>
</div>
<table class="table">
    <thead>
        <tr>
            <th scope="col" class="text-center bg-primary text-light">ID</th>
            <th scope="col" class="text-center bg-primary text-light">Ngày</th>
            <th scope="col" class="text-center bg-primary text-light">Giá</th>
            <th scope="col" class="text-center bg-primary text-light">UserID</th>
            <th scope="col" colspan="2" class="text-center bg-primary text-light">Hành động</th>
            <th scope="col" class="text-center bg-primary text-light">Cập nhật</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $data = $obj->layPhieuXuatKho($conn);

        $filterDate = isset($_POST['filter_date']) ? $_POST['filter_date'] : null;
        $filterMonth = isset($_POST['filter_month']) ? $_POST['filter_month'] : null;
        $filterYear = isset($_POST['filter_year']) ? $_POST['filter_year'] : null;

        if (!empty($filterDate) || !empty($filterMonth) || !empty($filterYear)) {
            $query = "SELECT * FROM inventory WHERE 1=1";
            if (!empty($filterDate)) {
                $query .= " AND inventory_date = '$filterDate'";
            }
            if (!empty($filterMonth)) {
                $query .= " AND MONTH(inventory_date) = '$filterMonth'";
            }
            if (!empty($filterYear)) {
                $query .= " AND YEAR(inventory_date) = '$filterYear'";
            }
            $result = mysqli_query($conn, $query);
            $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
        }

        foreach ($data as $row) {
            echo '<tr>';
            echo '<td class="text-center">' . $row['inventory_id'] . '</td>';
            echo '<td class="text-center">' . $row['inventory_date'] . '</td>';
            $inventory_price = number_format($row['inventory_price'], 0, ',', '.') . ' VND';
            $inventory_price = str_replace(' ', ' ', $inventory_price);
            echo '<td class="text-center">' . $inventory_price . '</td>';
            echo '<td class="text-center">' . $row['user_id'] . '</td>';
            echo '<td class="text-center"><a href="#" class="detail" data-bs-toggle="modal" data-bs-target="#chiTietModal_' . $row['inventory_id'] . '">Xem chi tiết</a></td>';
            echo '<td class="text-center"><a href="#" class="print" data-bs-toggle="modal" data-bs-target="#printModal' . $row['inventory_id'] . '"><i class="fa-solid fa-print"></i> Xuất hóa đơn</a></td>';
            echo '<td class="text-center remove"><a href="handle/xoa_xuat_kho.php?inventory_id=' . $row['inventory_id'] . '">Hủy</a></td>';
            echo '</tr>';

             // Modal chi tiết
            include("chitiet.php");
            include('inhoadon1.php');
        }
        ?>
    </tbody>
</table>

<div class="modal fade" id="themLoaiSanPhamModal" tabindex="-1" aria-labelledby="themPhieuNhapKhoModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="themPhieuNhapKhoModalLabel">Thêm phiếu xuất kho</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Thông tin Đơn hàng -->
                <h6 class="mb-3">Đơn hàng</h6>
                <form method="post" action="./index.php?p=xuatkho">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="supplier" class="form-label">Phân phối đến</label>
                                <input type="text" class="form-control" id="supplier" name="supplier" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inventory_date" class="form-label">Ngày xuất</label>
                                <input type="date" class="form-control" id="inventory_date" name="inventory_date"
                                    value="<?php echo date('Y-m-d'); ?>" readonly required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="inventory_status" class="form-label">Trạng thái</label>
                                <select class="form-select" id="inventory_status" name="inventory_status" required>
                                    <option value="unpaid">Chưa thanh toán</option>
                                    <option value="paid">Đã thanh toán</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="staff" class="form-label">Nhân viên</label>
                                <input type="text" class="form-control" id="staff" name="staff" required
                                    value="<?php echo $_SESSION['username'] ?>" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Chi tiết đơn hàng sản phẩm -->
                    <h6 class="mt-5 mb-3">Chi tiết đơn hàng sản phẩm</h6>
                    <div id="product_details">
                        <div class="mb-3">
                            <label for="product_id" class="form-label">Sản phẩm</label>
                            <select class="form-select form-select-product" id="product_id" required>
                                <?php
                                $query = "SELECT product_id, product_name, product_price FROM products";
                                $result = mysqli_query($conn, $query);
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo '<option value="' . $row['product_id'] . '" data-price="' . $row['product_price'] . '">' . $row['product_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="inventory_quantity" class="form-label">Số lượng</label>
                            <input type="number" class="form-control" id="inventory_quantity" min="1"
                                pattern="[1-9][0-9]*" name="inventory_quantity[]" oninput="validateInput(this)">
                        </div>
                        <div class="mb-3">
                            <label for="inventory_price" class="form-label">Giá</label>
                            <input type="text" class="form-control" id="inventory_price" name="inventory_price[]">
                        </div>
                        <button type="button" id="themSanPhamBtn" class="btn btn-primary">Thêm sản phẩm</button>
                    </div>
                    <h6 class="mt-5 mb-3">Danh sách sản phẩm</h6>
                    <ul id="product_list" class="list-group mb-3"></ul>

                    <!-- Nút thêm -->
                    <button type="submit" name="themPhieuNhapKho" class="btn btn-primary">Tạo phiếu xuất kho</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
var selectElement = document.getElementById("product_id");
var quantityElement = document.getElementById("inventory_quantity");
var priceElement = document.getElementById("inventory_price");

selectElement.addEventListener("change", function() {
    updatePrice();
});

quantityElement.addEventListener("input", function() {
    updatePrice();
});

function updatePrice() {
    var selectedOption = selectElement.options[selectElement.selectedIndex];
    var price = parseFloat(selectedOption.getAttribute("data-price"));
    var quantity = parseInt(quantityElement.value);

    if (!isNaN(price) && !isNaN(quantity)) {
        var totalPrice = price * quantity;
        priceElement.value = totalPrice.toFixed(2);
    }
}
</script>
<script>
const button = document.getElementById("print_inventory");

function generatePDF() {
    console.log("check");
    const element = document.getElementById("inventory_out");
    html2pdf().from(element).save();
}
button.addEventListener("click", generatePDF);
</script>
<script>
document.getElementById('themSanPhamBtn').addEventListener('click', function() {
    var productSelect = document.getElementById('product_id');
    var quantityInput = document.getElementById('inventory_quantity');
    var priceInput = document.getElementById('inventory_price');

    var selectedProduct = productSelect.options[productSelect.selectedIndex];
    var productName = selectedProduct.textContent;
    var productPrice = selectedProduct.getAttribute('data-price');
    var quantity = quantityInput.value;
    var price = priceInput.value;

    var existingProduct = findProductByName(productName);

    if (existingProduct) {
        existingProduct.quantity += parseInt(quantity);
        existingProduct.price = parseFloat(existingProduct.price) + (parseFloat(price) * parseInt(quantity));
        existingProduct.listItem.querySelector('.quantity').textContent = 'Số lượng: ' + existingProduct
            .quantity;
        existingProduct.listItem.querySelector('.price').textContent = 'Giá: ' + formatCurrency(existingProduct
            .price);
        existingProduct.listItem.querySelector('input[name="product_quantity[]"]').value = existingProduct
            .quantity;
    } else {
        var listItem = createProductListItem(productName, quantity, price);
        var product = {
            name: productName,
            quantity: parseInt(quantity),
            price: parseFloat(price) * parseInt(quantity),
            listItem: listItem
        };
        productList.appendChild(listItem);
        productArray.push(product);
    }

    productSelect.selectedIndex = 0;
    quantityInput.value = '';
    priceInput.value = '';
});

function removeProduct(button) {
    var li = button.parentNode;
    var ul = li.parentNode;
    ul.removeChild(li);

    // Xóa thông tin sản phẩm trong mảng productArray
    var productName = li.querySelector('.product-name').textContent.replace(/Sản phẩm: /g, "");;
    console.log(productName)
    productArray = productArray.filter((item) => {
        return item.name !== productName;
    });
}


function findProductByName(name) {
    for (var i = 0; i < productArray.length; i++) {
        if (productArray[i].name === name) {
            return productArray[i];
        }
    }
    return null;
}

function formatCurrency(price) {
    var formatter = new Intl.NumberFormat('vi-VN');
    var parts = formatter.formatToParts(price);
    var formattedPrice = '';

    for (var i = 0; i < parts.length; i++) {
        if (parts[i].type === 'group' || parts[i].type === 'integer') {
            formattedPrice += parts[i].value;
        } else if (parts[i].type === 'decimal') {
            formattedPrice += '.' + parts[i].value;
        }
    }

    return formattedPrice + ',000 VNĐ';
}

function createProductListItem(name, quantity, price) {
    let productId = document.querySelector('.form-select-product').value;
    var listItem = document.createElement('li');
    listItem.className = 'list-group-item d-flex justify-content-between align-items-center';
    listItem.innerHTML = `
        <span class="product-name" style="min-width: 200px">Sản phẩm: ${name}</span>
        <span class="quantity" style="min-width: 200px">Số lượng: ${quantity}</span>
        <span class="price" style="min-width: 200px">Giá: ${formatCurrency(parseFloat(price))}</span>
        <input type="hidden" name="product_id[]" value="${productId}">
        <input type="hidden" name="product_quantity[]" value="${quantity}">
    `;

    var removeButton = document.createElement('button');
    removeButton.innerHTML = 'Xóa';
    removeButton.className = 'btn btn-danger btn-sm';
    removeButton.addEventListener('click', function() {
        removeProduct(this);
    });

    listItem.appendChild(removeButton);

    return listItem;
}

var productArray = [];
var productList = document.getElementById('product_list');
</script>
<script>
function validateInput(input) {
    var value = input.value;
    if (value < 1 || /[+\-*\/]/.test(value)) {
        input.value = value.replace(/[+\-*\/]/g, '');
    }
    value = value.replace(/^0+/, '');
    input.value = value;
}
</script>