<?php
require 'function.php';


// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Inisialisasi Objek
$database = new Database();
$user = new User($database->conn);

$username = $_SESSION['username'];
$userInfo = $user->getUserInfo($username);

// Proses pembaruan data pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'no_hp' => $_POST['no_hp'],
        'new_password' => $_POST['new_password'], // Bisa kosong
    ];

    if ($user->updateUser($username, $data)) {
        echo "<script>alert('Profil berhasil diperbarui!');</script>";
        $userInfo = $user->getUserInfo($username); // Refresh data setelah update
    } else {
        echo "<script>alert('Gagal memperbarui profil. Silakan coba lagi.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background-color: #f4f6f9;
            min-height: 100vh;
        }

        .navbar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: 250px;
            background: #ffffff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            transition: transform 0.3s ease;
        }

        .navbar h2 {
            color: #1a1a1a;
            font-size: 1.5rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f0f0f0;
        }

        .navbar ul {
            list-style: none;
        }

        .navbar ul li {
            margin-bottom: 0.5rem;
        }

        .navbar ul li a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #4a5568;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .navbar ul li a i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .navbar ul li.active a {
            background: #e8f2ff;
            color: #2563eb;
            font-weight: 500;
        }

        .navbar ul li:not(.active) a:hover {
            background: #f8fafc;
            color: #1a1a1a;
        }

        .content {
            margin-left: 250px;
            padding: 2rem;
        }

        .content h1 {
            font-size: 1.875rem;
            color: #1a1a1a;
            margin-bottom: 2rem;
            font-weight: 600;
        }

        .profil {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            max-width: 600px;
            margin: 0 auto;
        }

        .profil h2 {
            color: #1a1a1a;
            font-size: 1.5rem;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #4a5568;
            font-size: 0.875rem;
            font-weight: 500;
        }

        .box-input {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 0.75rem;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .box-input:focus-within {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .box-input i {
            color: #64748b;
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }

        .box-input input {
            width: 100%;
            border: none;
            background: transparent;
            outline: none;
            color: #1a1a1a;
            font-size: 0.875rem;
        }

        .box-input input::placeholder {
            color: #94a3b8;
        }

        .submit-btn {
            display: block;
            width: 100%;
            background: #2563eb;
            color: white;
            border: none;
            padding: 0.875rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.875rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .submit-btn:hover {
            background: #1d4ed8;
        }

        @media (max-width: 768px) {
            .navbar {
                transform: translateX(-100%);
                z-index: 50;
            }

            .content {
                margin-left: 0;
                padding: 1rem;
            }

            .menu-toggle {
                display: block;
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 100;
                background: white;
                border: none;
                padding: 0.5rem;
                border-radius: 4px;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                cursor: pointer;
            }

            .navbar.active {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body>
    <button class="menu-toggle" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </button>

    <nav class="navbar" id="navbar">
        <h2>Menu</h2>
        <ul>
            <li><a href="index.php?m=awal"><i class="fas fa-home"></i>Dashboard</a></li>
            <li><a href="index.php?m=poly&s=awal"><i class="fas fa-hospital"></i>Poli</a></li>
            <li><a href="index.php?m=history&s=awal"><i class="fas fa-history"></i>Riwayat Antrian</a></li>
            <li class="active"><a href="index.php?m=profil&s=awal"><i class="fas fa-cog"></i>Pengaturan</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
        </ul>
    </nav>

    <main class="content">
        <h1>Pengaturan</h1>
        <div class="profil">
            <h2>Data Pengguna</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Nama Lengkap</label>
                    <div class="box-input">
                        <i class="fas fa-user"></i>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userInfo['name']); ?>" required placeholder="Masukkan nama lengkap">
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="box-input">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userInfo['email']); ?>" required placeholder="Masukkan email">
                    </div>
                </div>

                <div class="form-group">
                    <label for="no_hp">Nomor Telepon</label>
                    <div class="box-input">
                        <i class="fas fa-phone"></i>
                        <input type="tel" id="no_hp" name="no_hp" value="<?php echo htmlspecialchars($userInfo['no_hp']); ?>" required placeholder="Masukkan nomor telepon">
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password">Password Baru</label>
                    <div class="box-input">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="new_password" name="new_password" placeholder="Masukkan password baru">
                    </div>
                </div>

                <button type="submit" class="submit-btn">Simpan Perubahan</button>
            </form>
        </div>
    </main>

    <script>
        function toggleMenu() {
            document.getElementById('navbar').classList.toggle('active');
        }
    </script>
</body>
</html>


