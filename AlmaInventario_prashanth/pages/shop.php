<?php
session_start();
include "../database/db_connection.php";

if(!isset($_SESSION['cart'])){
    $_SESSION['cart'] = [];
}

if(isset($_POST['add_multiple'])){

    if(isset($_POST['qty'])){

        foreach($_POST['qty'] as $product_id => $qty){

            $product_id = (int)$product_id;
            $qty = (int)$qty;

            if($qty > 0){

                $result = mysqli_query($conn,"SELECT * FROM products WHERE product_id='$product_id'");
                $product = mysqli_fetch_assoc($result);

                if($product && $qty <= $product['stock']){

                    $_SESSION['cart'][$product_id] = [
                        "product_id"   => $product['product_id'],
                        "product_name" => $product['product_name'],
                        "price"        => $product['price'],
                        "quantity"     => $qty
                    ];
                }
            }
        }
    }

    header("Location: cart.php");
    exit();
}

$result = mysqli_query($conn,"SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop — AlmaInventario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="shop-body">

<h2 class="h2-shop">🛒 Shop</h2>

<a href="cart.php" class="cart-link">View Cart 🛒</a>
<a href="../index.php" class="back-shop">← Back</a>

<form method="POST" class="from-shop">

<table class="table-shop">
<tr>
    <th>Product Name</th>
    <th>Price</th>
    <th>Stock</th>
    <th>Quantity</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){
    $stock = $row['stock'];
?>
<tr>
    <td style="text-align:left;padding-left:20px;"><?php echo htmlspecialchars($row['product_name']); ?></td>
    <td>₹<?php echo number_format($row['price'],2); ?></td>

    <td>
        <?php
        if($stock > 5){
            echo "<span class='badge badge-green'>$stock</span>";
        }
        elseif($stock > 0){
            echo "<span class='badge badge-yellow'>$stock</span>";
        }
        else{
            echo "<span class='badge badge-red'>$stock</span>";
        }
        ?>
    </td>

    <td>
        <input type="number"
               name="qty[<?php echo $row['product_id']; ?>]"
               min=""
               max="<?php echo $stock; ?>"
               value=""
               <?php if($stock == 0) echo "disabled"; ?>>
    </td>
</tr>
<?php } ?>
</table>

<button type="submit" name="add_multiple" class="button-shop">
    Add to Cart 🛒
</button>

</form>

</body>
</html>
