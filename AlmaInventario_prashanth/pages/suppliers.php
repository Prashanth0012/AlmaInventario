<?php
session_start();
include "../database/db_connection.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['add'])){
    $name = $_POST['supplier_name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    mysqli_query($conn,"INSERT INTO suppliers (supplier_name,phone,address) VALUES ('$name','$phone','$address')");
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM suppliers WHERE id='$id'");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Suppliers</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body class="page-body">

<div class="page-container">

<h2 class="page-title">Suppliers</h2>
<?php include "navbar.php"; ?>

<br>


<div class="form-card">
<form method="POST" class="form-grid">
    <input type="text" name="supplier_name" class="form-input" placeholder="Supplier Name" required>
    <input type="text" name="phone" class="form-input" placeholder="Phone">
    <input type="text" name="address" class="form-input" placeholder="Address">
    <button type="submit" name="add" class="btn-primary">Add Supplier</button>
</form>
</div>

<div class="table-card">

<table class="data-table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Action</th>
    </tr>

    <?php
    $result = mysqli_query($conn,"SELECT * FROM suppliers");

    while($row = mysqli_fetch_assoc($result)){
    ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['supplier_name']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td><?php echo $row['address']; ?></td>
            <td>
                <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete">Delete</a>
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