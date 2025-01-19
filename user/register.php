<?php
require 'function.php';  // Memanggil file koneksi database

// Menghubungkan ke database
$db = new Database();
$user = new User($db->conn);  // Membuat objek User sekali saja

// Menyimpan pesan di dalam sesi untuk digunakan pada form
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $no_hp = $_POST['phone'];
    $password = $_POST['password'];

    // Mengecek jika username atau email sudah terdaftar
    if ($user->isUsernameExists($username)) {
        $message = "<p class='error-message'>Username sudah digunakan. Silakan pilih yang lain.</p>";
    }  else {
        // Proses registrasi jika valid
        $result = $user->register($name, $username, $email, $no_hp, $password);
        if ($result) {
            $message = "<p class='success-message'>Akun berhasil dibuat! Silakan <a href='login.php'>masuk</a>.</p>";
        } else {
            $message = "<p class='error-message'>Gagal mendaftarkan pengguna. Coba lagi.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../vendor/css/register.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
</head>
<body>
    <div class="register-container">
        <h2>Register</h2>
        <form action="register.php" method="POST">
            <!-- Menampilkan pesan jika ada -->
            <?php if ($message): ?>
                <div class="message"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <div class="box-input">
                <i class="fas fa-user"></i>
                <input type="text" name="name" placeholder="Nama Lengkap" required>
            </div>
            <div class="box-input">
                <i class="fas fa-address-book"></i>
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="box-input">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="box-input">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="box-input">
                <i class="fas fa-phone"></i>
                <input type="tel" name="phone" placeholder="Nomor HP" required>
            </div>
            <button type="submit" class="btn-input">Register</button>
        </form>
        <div class="bottom">
            <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
        </div>
    </div>
</body>
</html>
