<?php
session_start();
include "../database/db_connection.php";

if(!isset($_SESSION['cart']) || empty($_SESSION['cart'])){
    echo "Cart is empty";
    exit();
}

if(empty($_POST['customer_name'])){
    echo "Customer name required";
    exit();
}

$customer_name = mysqli_real_escape_string($conn,$_POST['customer_name']);
$invoice_no = "INV".rand(1000,99999);
$date = date("Y-m-d H:i:s");

$total_amount = 0;

foreach($_SESSION['cart'] as $item){
    $total_amount += $item['price'] * $item['quantity'];
}

mysqli_query($conn,"INSERT INTO sales 
(customer_name,total_amount,sale_date,invoice_no)
VALUES 
('$customer_name','$total_amount',NOW(),'$invoice_no')");

$sale_id = mysqli_insert_id($conn);

foreach($_SESSION['cart'] as $item){

    $product_id = $item['product_id'];
    $quantity   = $item['quantity'];
    $price      = $item['price'];
    $subtotal   = $price * $quantity;

    mysqli_query($conn,"INSERT INTO sale_items
    (sale_id,product_id,quantity,price,subtotal)
    VALUES
    ('$sale_id','$product_id','$quantity','$price','$subtotal')");

    mysqli_query($conn,"UPDATE products
    SET stock = stock - $quantity
    WHERE product_id='$product_id'");

    mysqli_query($conn,"INSERT INTO stock_transactions
    (product_id,type,quantity,date)
    VALUES
    ('$product_id','OUT','$quantity',NOW())");
}

$invoice_items = $_SESSION['cart'];
unset($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?php echo $invoice_no; ?> — AlmaInventario</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        @media print {
            .invoice-actions { display: none; }
        }
    </style>
</head>
<body class="shop-body">

<div class="invoice-box">

    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:16px;margin-bottom:24px;">
        <div>
            <div style="font-size:1.6rem;font-weight:800;color:#4f6ef7;">AlmaInventario 📦</div>
            <p style="color:#6b7280;font-size:13px;margin:4px 0 0;">Invoice / Bill</p>
        </div>
        <div style="text-align:right;">
            <p style="font-size:1.1rem;font-weight:700;color:#1e3a5f;margin:0;">#<?php echo $invoice_no; ?></p>
            <p style="color:#6b7280;font-size:13px;margin:4px 0 0;"><?php echo $date; ?></p>
        </div>
    </div>

    <div style="background:#f9fafb;padding:16px 20px;border-radius:10px;margin-bottom:24px;">
        <p style="margin:0;font-size:14px;"><strong style="color:#1e3a5f;">Customer:</strong> <?php echo htmlspecialchars($customer_name); ?></p>
    </div>

    <table class="table-invoice">
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Subtotal</th>
    </tr>

    <?php foreach($invoice_items as $item){ ?>
    <tr>
        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
        <td>₹<?php echo number_format($item['price'],2); ?></td>
        <td><?php echo $item['quantity']; ?></td>
        <td>₹<?php echo number_format($item['price'] * $item['quantity'],2); ?></td>
    </tr>
    <?php } ?>
    </table>

    <div class="grand-total">
        Grand Total: ₹<?php echo number_format($total_amount,2); ?>
    </div>

    <div class="invoice-actions">
        <button onclick="window.print()" class="button-invoice">🖨 Print Invoice</button>
        <a href="shop.php" class="a-invoice">← Back to Shop</a>
    </div>

</div>

</body>
</html>
