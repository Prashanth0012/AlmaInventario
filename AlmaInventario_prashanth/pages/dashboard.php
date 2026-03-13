<?php
session_start();
include "../database/db_connection.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

$q1 = mysqli_query($conn,"SELECT COUNT(*) as total FROM categories");
$total_categories = mysqli_fetch_assoc($q1)['total'];

$q2 = mysqli_query($conn,"SELECT COUNT(*) as total FROM suppliers");
$total_suppliers = mysqli_fetch_assoc($q2)['total'];

$q3 = mysqli_query($conn,"SELECT COUNT(*) as total FROM products");
$total_products = mysqli_fetch_assoc($q3)['total'];

$q4 = mysqli_query($conn,"SELECT SUM(total_amount) as total FROM sales");
$total_sales = mysqli_fetch_assoc($q4)['total'];

$q5 = mysqli_query($conn,"SELECT SUM(total_amount) as total FROM sales WHERE DATE(sale_date)=CURDATE()");
$today_sales = mysqli_fetch_assoc($q5)['total'];

$q6 = mysqli_query($conn,"SELECT COUNT(*) as total FROM products WHERE stock <= 5");
$low_stock = mysqli_fetch_assoc($q6)['total'];


$q7 = mysqli_query($conn,"SELECT SUM(stock * price) as total_inventory FROM products");
$total_inventory = mysqli_fetch_assoc($q7)['total_inventory'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — AlmaInventario</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body class="dashboard-body">

<div class="dashboard-container">

    <div class="app-brand">AlmaInventario 📦</div>

    <h2 class="dash-title">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>
    <p class="dash-role">Role: <?php echo ucfirst($_SESSION['role']); ?> &nbsp;|&nbsp; <?php echo date("d M Y"); ?></p>

    <?php include "navbar.php"; ?>

    <p class="summary-title">System Summary</p>

    <div class="summary-grid">

        <div class="summary-box">
            <h4>Categories</h4>
            <div class="summary-number"><?php echo $total_categories; ?></div>
        </div>

        <div class="summary-box">
            <h4>Suppliers</h4>
            <div class="summary-number"><?php echo $total_suppliers; ?></div>
        </div>

        <div class="summary-box">
            <h4>Products</h4>
            <div class="summary-number"><?php echo $total_products; ?></div>
        </div>

        <div class="summary-box">
            <h4>Inventory Value</h4>
            <div class="summary-number">₹<?php echo $total_inventory ? number_format($total_inventory,0) : 0; ?></div>
        </div>

        <div class="summary-box">
            <h4>Total Sales</h4>
            <div class="summary-number">₹<?php echo $total_sales ? number_format($total_sales,0) : 0; ?></div>
        </div>

        <div class="summary-box">
            <h4>Today's Sales</h4>
            <div class="summary-number">₹<?php echo $today_sales ? number_format($today_sales,0) : 0; ?></div>
        </div>

        <div class="summary-box">
            <h4>Low Stock Items</h4>
            <div class="summary-number low-stock"><?php echo $low_stock; ?></div>
        </div>

    </div>

</div>

</body>
</html>
