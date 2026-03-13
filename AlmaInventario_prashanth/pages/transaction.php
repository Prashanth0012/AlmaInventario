<?php
session_start();
include '../database/db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>

<html>

<head>
    <title>Stock Transactions</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body class="page-body">

<div class="page-container">

<h2 class="page-title">Stock Transactions</h2>

<?php include "navbar.php"; ?>

<br>

<div class="table-card">

    <table class="data-table">

        <tr>
            <th>ID</th>
            <th>Product</th>
            <th>Type</th>
            <th>Quantity</th>
            <th>Reference ID</th>
            <th>Date</th>
        </tr>

        <?php
        $query = mysqli_query(
            $conn,
            "SELECT t.*, p.product_name
             FROM transactions t
             JOIN products p ON t.product_id = p.product_id
             ORDER BY t.transaction_id ASC"
        );

        while ($row = mysqli_fetch_assoc($query)) {
            ?>
            <tr>
                <td><?php echo $row['transaction_id']; ?></td>
                <td><?php echo $row['product_name']; ?></td>

                <td>
                    <span class="type-badge <?php echo ($row['type'] == 'PURCHASE') ? 'type-purchase' : 'type-sale'; ?>">
                        <?php echo $row['type']; ?>
                    </span>
                </td>

                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['reference_id']; ?></td>
                <td><?php echo $row['transaction_date']; ?></td>
            </tr>
            <?php
        }
        ?>

    </table>

</div>

<br>

<a href="dashboard.php" class="btn-back">Back to Dashboard</a>


</div>

</body>
</html>
