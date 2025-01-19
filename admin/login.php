<?php
session_start();
require 'functions.php';

$db = new Database();
$connection = $db->conn;
$login = new Login($connection);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($login->login($username, $password)) {
        header('Location: index.php');
        exit();
    } else {
        $errorMessage = "Kesalahan username atau password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="../vendor/css/admin2.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
</head>
<body>
    <div class="admin-container">
        <form id="adminForm" action="login.php" method="POST" onsubmit="return validateForm()">
            <h1>Admin Login</h1>
            <div class="box-input">
                <i class="fas fa-address-book"></i>
                <input type="text" id="username" name="username" placeholder="Username">
            </div>
            <div class="box-input">
                <i class="fas fa-lock"></i>
                <input type="password" id="password" name="password" placeholder="Password">
            </div>
            <button type="submit" name="login" class="btn-input">Login</button>
        </form>
        <?php
        if (isset($errorMessage)) {
            echo "<p style='color:red; text-align:center;'>$errorMessage</p>";
        }
        ?>
    </div>

</body>
</html>
