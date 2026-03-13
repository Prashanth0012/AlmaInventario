
<?php
session_start();
include "../database/db_connection.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['add'])){
    $name = $_POST['product_name'];
    $category = $_POST['category_id'];
    $supplier = $_POST['supplier_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    mysqli_query($conn,"INSERT INTO products 
    (product_name,category_id,supplier_id,price,stock) 
    VALUES ('$name','$category','$supplier','$price','$stock')");

    header("Location: products.php");
    exit();
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM products WHERE product_id='$id'");
    header("Location: products.php");
    exit();
}

$condition = "";

if(isset($_GET['category']) && $_GET['category']!=""){
    $category = $_GET['category'];
    $condition = "WHERE p.category_id='$category'";
}

$query = "SELECT p.*, c.category_name, s.supplier_name 
          FROM products p
          JOIN categories c ON p.category_id = c.id
          JOIN suppliers s ON p.supplier_id = s.id
          $condition
          ORDER BY p.product_id ASC";

$result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Products</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>

<body class="page-body">

<div class="page-container">

<h2 class="page-title">Products</h2>
<?php include "navbar.php"; ?>

<br>

<div class="form-card">

<h3>Add Product</h3>

<form method="POST" class="form-grid">

<div class="form-group">
<label class="form-label">Product Name</label>
<input type="text" name="product_name" class="form-input" required>
</div>

<div class="form-group">
<label class="form-label">Category</label>

<select name="category_id" class="form-input" required>

<option value="">Select Category</option>

<?php
$cat = mysqli_query($conn,"SELECT * FROM categories");
while($c = mysqli_fetch_assoc($cat)){
?>

<option value="<?php echo $c['id']; ?>">
<?php echo $c['category_name']; ?>
</option>

<?php } ?>

</select>

</div>

<div class="form-group">
<label class="form-label">Supplier</label>

<select name="supplier_id" class="form-input" required>

<option value="">Select Supplier</option>

<?php
$sup = mysqli_query($conn,"SELECT * FROM suppliers");
while($s = mysqli_fetch_assoc($sup)){
?>

<option value="<?php echo $s['id']; ?>">
<?php echo $s['supplier_name']; ?>
</option>

<?php } ?>

</select>

</div>

<div class="form-group">
<label class="form-label">Price</label>
<input type="number" name="price" class="form-input" required>
</div>

<div class="form-group">
<label class="form-label">Stock</label>
<input type="number" name="stock" class="form-input" required>
</div>

<button type="submit" name="add" class="btn-primary">Add Product</button>

</form>

</div>

<br>

<form method="GET" class="filter-bar">

<select name="category">

<option value="">All Categories</option>

<?php
$cat = mysqli_query($conn,"SELECT * FROM categories");
while($c = mysqli_fetch_assoc($cat)){
?>

<option value="<?php echo $c['id']; ?>" 
<?php if(isset($_GET['category']) && $_GET['category']==$c['id']) echo "selected"; ?>>
<?php echo $c['category_name']; ?>
</option>

<?php } ?>

</select>

<button type="submit">Filter</button>

<a href="products.php">Reset</a>

</form>

<br>

<div class="table-card">

<table class="data-table">

<tr>
<th>ID</th>
<th>Name</th>
<th>Category</th>
<th>Supplier</th>
<th>Price</th>
<th>Stock</th>
<th>Action</th>
</tr>

<?php
while($row = mysqli_fetch_assoc($result)){
?>

<tr>

<td><?php echo $row['product_id']; ?></td>
<td><?php echo $row['product_name']; ?></td>
<td><?php echo $row['category_name']; ?></td>
<td><?php echo $row['supplier_name']; ?></td>
<td><?php echo $row['price']; ?></td>
<td><?php echo $row['stock']; ?></td>

<td>
<a href="?delete=<?php echo $row['product_id']; ?>" class="btn-delete">
Delete
</a>
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

