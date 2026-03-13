<?php
session_start();
include "../database/db_connection.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$result = mysqli_query($conn,"SELECT * FROM products WHERE stock <= 5");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Low Stock</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body class="page-body">

<div class="page-container">

<h2 class="page-title">Low Stock Products</h2>
<?php include "navbar.php"; ?>

<br>

<div class="table-card">

<table class="data-table">
<tr>
    <th>Product Name</th>
    <th>Stock</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($result)){
?>
<tr>
    <td><?php echo $row['product_name']; ?></td>
    <td>
        <span class="stock-badge <?php echo $row['stock'] == 0 ? 'stock-out' : 'stock-low'; ?>">
            <?php echo $row['stock']; ?>
        </span>
    </td>
</tr>
<?php } ?>
</table>

</div>

<br>

<a href="dashboard.php" class="btn-back">Back</a>

</div>

</body>
</html>