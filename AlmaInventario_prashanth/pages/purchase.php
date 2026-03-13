<?php
session_start();
include '../database/db_connection.php';

if(!isset($_SESSION['purchase_cart'])){
    $_SESSION['purchase_cart'] = [];
}

if(isset($_POST['add_purchase'])){

    $product_id = $_POST['product_id'];
    $quantity   = $_POST['quantity'];

    $check = mysqli_query($conn, "SELECT * FROM products WHERE product_id='$product_id'");
    $product = mysqli_fetch_assoc($check);

    if($product){

        $price = $product['price'];
        $subtotal = $price * $quantity;

        $_SESSION['purchase_cart'][] = [
            'product_id' => $product_id,
            'product_name' => $product['product_name'],
            'price' => $price,
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}

if(isset($_POST['save_purchase']) && !empty($_SESSION['purchase_cart'])){

    $supplier_id = $_POST['supplier_id'];
    $purchase_date = date("Y-m-d");

    $total = 0;
    foreach($_SESSION['purchase_cart'] as $item){
        $total += $item['subtotal'];
    }

    mysqli_query($conn,"INSERT INTO purchases (supplier_id, purchase_date, total_amount)
    VALUES ('$supplier_id','$purchase_date','$total')");

    $purchase_id = mysqli_insert_id($conn);

    foreach($_SESSION['purchase_cart'] as $item){

        $product_id = $item['product_id'];
        $qty        = $item['quantity'];
        $price      = $item['price'];
        $subtotal   = $item['subtotal'];

        mysqli_query($conn,"INSERT INTO purchase_items 
        (purchase_id, product_id, quantity, price, subtotal)
        VALUES ('$purchase_id','$product_id','$qty','$price','$subtotal')");

        mysqli_query($conn,"UPDATE products 
        SET stock = stock + $qty
        WHERE product_id='$product_id'");

        mysqli_query($conn,"INSERT INTO transactions
        (product_id, type, quantity, reference_id)
        VALUES ('$product_id','PURCHASE','$qty','$purchase_id')");
    }

    $_SESSION['purchase_cart'] = [];
    echo "<script>alert('Purchase Saved Successfully'); window.location='purchase.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Purchase Module</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>

<body class="page-body">

<div class="page-container">

<h2 class="page-title">Purchase Module</h2>
<?php include "navbar.php"; ?>

<div class="form-card">
<form method="POST" class="form-grid">

<div class="form-group">
<label class="form-label">Supplier</label>
<select name="supplier_id" class="form-input" required>
<option value="">Select Supplier</option>
<?php
$suppliers = mysqli_query($conn,"SELECT * FROM suppliers");
while($row = mysqli_fetch_assoc($suppliers)){
echo "<option value='".$row['supplier_id']."'>".$row['supplier_name']."</option>";
}
?>
</select>
</div>

<div class="form-group">
<label class="form-label">Product</label>
<select name="product_id" class="form-input" required>
<option value="">Select Product</option>
<?php
$products = mysqli_query($conn,"SELECT * FROM products");
while($row = mysqli_fetch_assoc($products)){
echo "<option value='".$row['product_id']."'>".$row['product_name']."</option>";
}
?>
</select>
</div>

<div class="form-group">
<label class="form-label">Quantity</label>
<input type="number" name="quantity" class="form-input" required>
</div>

<button type="submit" name="add_purchase" class="btn-primary">Add to Cart</button>

</form>
</div>

<hr class="divider">

<h3 class="section-title">Purchase Cart</h3>

<div class="table-card">
<table class="data-table">

<tr>
<th>Product</th>
<th>Price</th>
<th>Quantity</th>
<th>Subtotal</th>
</tr>

<?php
$total = 0;

foreach($_SESSION['purchase_cart'] as $item){
echo "<tr>
<td>".$item['product_name']."</td>
<td>".$item['price']."</td>
<td>".$item['quantity']."</td>
<td>".$item['subtotal']."</td>
</tr>";
$total += $item['subtotal'];
}
?>

<tr class="total-row">
<th colspan="3">Total</th>
<th><?php echo $total; ?></th>
</tr>

</table>
</div>

<br>

<?php if(!empty($_SESSION['purchase_cart'])){ ?>
<form method="POST" class="save-form">
<input type="hidden" name="supplier_id" value="<?php echo $_POST['supplier_id'] ?? ''; ?>">
<button type="submit" name="save_purchase" class="btn-primary">Save Purchase</button>
</form>
<?php } ?>

<br><br>

<a href="dashboard.php" class="btn-back">Back</a>

</div>

</body>
</html>