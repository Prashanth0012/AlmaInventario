<?php
session_start();

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_GET['remove'])){
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
    header("Location: cart.php");
    exit();
}

$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart — AlmaInventario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="shop-body">

<div class="cart-container">

<div class="cart-header">
    <h2>🛒 Your Cart</h2>
    <a href="shop.php" class="back-shop">← Continue Shopping</a>
</div>

<table class="table-cart">
<tr>
    <th>Product</th>
    <th>Price</th>
    <th>Qty</th>
    <th>Subtotal</th>
    <th>Action</th>
</tr>

<?php
if(!empty($_SESSION['cart'])){

    foreach($_SESSION['cart'] as $item){

        $subtotal = $item['price'] * $item['quantity'];
        $total += $subtotal;
?>
<tr>
    <td><?php echo htmlspecialchars($item['product_name']); ?></td>
    <td>₹<?php echo number_format($item['price'],2); ?></td>
    <td><?php echo $item['quantity']; ?></td>
    <td>₹<?php echo number_format($subtotal,2); ?></td>
    <td>
        <a href="cart.php?remove=<?php echo $item['product_id']; ?>" class="remove-btn">
            Remove
        </a>
    </td>
</tr>
<?php
    }

}else{
    echo "<tr><td colspan='5' style='text-align:center;color:#9ca3af;padding:30px;'>Your cart is empty.</td></tr>";
}
?>

</table>

<?php if(!empty($_SESSION['cart'])){ ?>
<div class="checkout-card">
    <h3>Order Summary</h3>
    <p style="font-size:1.1rem;font-weight:700;color:#1e3a5f;margin:0 0 18px;">
        Total: <span style="color:#4f6ef7;">₹<?php echo number_format($total,2); ?></span>
    </p>
    <form method="POST" action="checkout.php">
        <input type="text" name="customer_name" placeholder="Enter Customer Name" required>
        <button type="submit" class="checkout-btn">✅ Confirm & Checkout</button>
    </form>
</div>
<?php } ?>

</div>

</body>
</html>
