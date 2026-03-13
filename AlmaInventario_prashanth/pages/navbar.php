<nav class="dashboard-nav">

    <?php if($_SESSION['role'] == 'admin'){ ?>
    <a href="users.php" class="dash-link">👥 Employees</a>
    <?php } ?>

    <a href="dashboard.php" class="dash-link">🏠 Dashboard</a>
    <a href="categories.php" class="dash-link">🗂 Categories</a>
    <a href="suppliers.php" class="dash-link">🏭 Suppliers</a>
    <a href="products.php" class="dash-link">📦 Products</a>
    <a href="purchase.php" class="dash-link">🛒 Purchase</a>
    <a href="transaction.php" class="dash-link">🔄 Transactions</a>
    <a href="reports.php" class="dash-link">📊 Reports</a>
    <a href="low_stock.php" class="dash-link">⚠️ Low Stock</a>
    <a href="../logout.php" class="dash-logout">🚪 Logout</a>

</nav>
