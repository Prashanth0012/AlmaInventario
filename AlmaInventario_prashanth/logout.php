<?php
session_start();
include "database/db_connection.php";

$user_id = $_SESSION['user_id'];

mysqli_query($conn,"UPDATE login_logs SET logout_time=NOW() WHERE user_id='$user_id' AND logout_time IS NULL");

session_destroy();
header("Location: index.php");
?>
