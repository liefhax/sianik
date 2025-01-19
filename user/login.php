<?php
session_start();
require 'function.php';  // Include the necessary database and class files

// Create a Database instance and pass it to the User class
$db = new Database();
$user = new User($db->conn);

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($user->login($username, $password)) {
        header('Location: index.php');  // Redirect to the dashboard on successful login
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
    <title>Login</title>
    <link rel="stylesheet" href="../vendor/css/login.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
</head>
<body>
    <div class="login-container">
        <h1>LOGIN</h1>
        <form action="login.php" method="POST">
            <div class="box-input">
                <i class="fas fa-address-book"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="box-input">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" name="login" class="btn-input">Login</button>
        </form>
        <div class="bottom">
            <p>Belum punya akun?
                <a href="register.php">Register disini</a>
            </p>
        </div>

        <?php
        if (isset($errorMessage)) {
            echo "<p style='color:red; text-align:center;'>$errorMessage</p>";
        }
        ?>
    </div>
</body>
</html>
