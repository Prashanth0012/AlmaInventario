<?php
session_start();
include "../database/db_connection.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../login.php");
    exit();
}

if(isset($_POST['add'])){
    $name = $_POST['category_name'];
    mysqli_query($conn,"INSERT INTO categories (category_name) VALUES ('$name')");
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn,"DELETE FROM categories WHERE id='$id'");
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Categories</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body class="page-body">

<div class="page-container">
    

<h2 class="page-title">Categories</h2>
<?php include "navbar.php"; ?>

<br>

<div class="form-card">
<form method="POST" class="form-inline">
    <input type="text" name="category_name" class="form-input" placeholder="Enter Category Name" required>
    <button type="submit" name="add" class="btn-primary">Add Category</button>
</form>
</div>

<div class="table-card">

<table class="data-table">
    <tr>
        <th>ID</th>
        <th>Category Name</th>
        <th>Action</th>
    </tr>

    <?php
    $result = mysqli_query($conn,"SELECT * FROM categories");

    while($row = mysqli_fetch_assoc($result)){
    ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['category_name']; ?></td>
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