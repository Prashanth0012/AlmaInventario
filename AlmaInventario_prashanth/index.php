<?php
session_start();
include "database/db_connection.php";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $row = mysqli_fetch_assoc($result);

        if ($password == $row['password']) {

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];

            mysqli_query($conn, "INSERT INTO login_logs (user_id, login_time) 
                                 VALUES ('".$row['id']."', NOW())");

            header("Location: pages/dashboard.php");
            exit();
        }
    }

    $error = "Invalid username or password.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AlmaInventario 📦</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1 class="h1-login">AlmaInventario 📦</h1>

<div class="main-container">

    <div class="card">
        <h2>🛒 Shop</h2>
        <p style="color:rgba(255,255,255,0.8);font-size:13px;margin:0 0 16px;">Browse and purchase products without logging in.</p>
        <a href="pages/shop.php" class="a-login">Go to Shop →</a>
    </div>

    <div class="card2">
        <h2>Staff Login</h2>

        <?php if (isset($error)): ?>
            <p style="color:#dc2626;background:#fee2e2;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;"><?php echo $error; ?></p>
        <?php endif; ?>

        <form method="POST" class="login-form">
            <input type="text" name="username" placeholder="Username" class="input-login" required>
            <input type="password" name="password" placeholder="Password" class="input-login" required>
            <button type="submit" name="login" class="button-login">Login</button>
        </form>
    </div>

</div>

</body>
</html>
