<?php
session_start();
include "../database/db_connection.php";

if(!isset($_SESSION['user_id'])){
    header("Location: ../index.php");
    exit();
}

if($_SESSION['role'] != 'admin'){
    echo "Access Denied";
    exit();
}

if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    if($id != $_SESSION['user_id']){
        mysqli_query($conn,"DELETE FROM users WHERE id='$id'");
    }
}

$result = mysqli_query($conn,"SELECT id, username, role, status FROM users");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body class="dashboard-body">

<div class="dashboard-container">

<h2 class="page-title">Manage Users</h2>
<?php include "navbar.php"; ?>

<br>

<div class="summary-card">

<table class="dashboard-table">

<tr>
    <th>ID</th>
    <th>Username</th>
    <th>Role</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)){ ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['username']; ?></td>
    <td><?php echo ucfirst($row['role']); ?></td>
    <td>
        <span class="status-badge <?php echo $row['status'] == 'active' ? 'status-active' : 'status-inactive'; ?>">
            <?php echo ucfirst($row['status']); ?>
        </span>
    </td>
    <td>
        <?php if($row['id'] != $_SESSION['user_id']){ ?>
            <a href="?delete=<?php echo $row['id']; ?>" class="btn-delete">Delete</a>
        <?php } else { ?>
            <span class="logged-user">Logged In User</span>
        <?php } ?>
    </td>
</tr>
<?php } ?>

</table>

</div>

<br>

<a href="dashboard.php" class="btn-back">Back to Dashboard</a>

</div>

</body>
</html>