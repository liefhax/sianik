<?php
require 'function.php';  // Include the function file to access the necessary classes and methods

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header('Location: login.php');  // Redirect to login page if not logged in
    exit();
}


$db = new Database();
$username = $_SESSION['username'];  // Get the logged-in username
$user = new User($db->conn);  // Create User instance with the database connection
$userInfo = $user->getUserInfo($username);  // Get user info based on the logged-in username
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - SIANIK</title>
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

        .welcome-section {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            border-radius: 16px;
            padding: 2.5rem;
            color: white;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .welcome-section h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.3;
        }

        .welcome-section p {
            font-size: 1.1rem;
            opacity: 0.9;
            max-width: 600px;
            line-height: 1.6;
        }

        .action-button {
            margin: 2rem 0;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            padding: 1rem 2rem;
            background: #2123eb;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s ease;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
        }

        .btn-primary:hover {
            background: #1d4ed8;
            transform: translateY(-2px);
        }

        .btn-primary i {
            margin-right: 0.5rem;
        }

        .info-box {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .info-box p {
            color: #4a5568;
            line-height: 1.7;
            font-size: 1.1rem;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .service-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .service-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            object-fit: contain;
        }

        .service-card h3 {
            color: #2563eb;
            font-size: 1.25rem;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .service-card p {
            color: #4a5568;
            line-height: 1.6;
            font-size: 1rem;
        }

        .menu-toggle {
            display: none;
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
            }

            .navbar.active {
                transform: translateX(0);
            }

            .welcome-section {
                padding: 1.5rem;
            }

            .welcome-section h1 {
                font-size: 1.5rem;
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
            <li class="active"><a href="index.php?m=awal"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="index.php?m=poly&s=awal"><i class="fas fa-hospital"></i> Poli</a></li>
            <li><a href="index.php?m=history&s=awal"><i class="fas fa-history"></i> Riwayat Antrian</a></li>
            <li><a href="index.php?m=profile&s=awal"><i class="fas fa-cog"></i> Pengaturan</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <main class="content">
        <div class="welcome-section">
            <h1>Selamat Datang di SIANIK,<br><?php echo htmlspecialchars($userInfo['name']); ?>!</h1>
            <p>Sistem Antrian Klinik yang memudahkan Anda mendapatkan pelayanan kesehatan dengan lebih efisien.</p>
            <div class="action-button">
                <a href="index.php?m=poly&s=awal" class="btn-primary">
                    <i class="fas fa-ticket-alt"></i>
                    Ambil Antrian Sekarang
                </a>
            </div>
        </div>

        <div class="info-box">
            <p>Sistem antrian SIANIK dirancang untuk memberikan pengalaman yang lebih baik dalam mendapatkan layanan kesehatan. Dengan sistem ini, Anda dapat mengatur waktu kunjungan dengan lebih efisien dan mengurangi waktu tunggu di klinik.</p>
        </div>

        <div class="service-grid">
            <div class="service-card">
                <img src="../vendor/img/icon1.png" alt="Poli Umum" class="service-icon">
                <h3>Poli Umum</h3>
                <p>Layanan pemeriksaan kesehatan umum dan konsultasi dengan dokter umum untuk berbagai keluhan kesehatan.</p>
            </div>
            <div class="service-card">
                <img src="../vendor/img/icon2.png" alt="Poli Gigi" class="service-icon">
                <h3>Poli Gigi</h3>
                <p>Pelayanan kesehatan gigi dan mulut oleh dokter gigi profesional dengan peralatan modern.</p>
            </div>
            <div class="service-card">
                <img src="../vendor/img/icon3.png" alt="Poli Kandungan" class="service-icon">
                <h3>Poli Kandungan</h3>
                <p>Layanan konsultasi dan pemeriksaan kesehatan ibu hamil serta masalah kandungan lainnya.</p>
            </div>
            <div class="service-card">
                <img src="../vendor/img/icon4.png" alt="Poli Mata" class="service-icon">
                <h3>Poli Mata</h3>
                <p>Pemeriksaan kesehatan mata lengkap dan penanganan berbagai masalah penglihatan.</p>
            </div>
        </div>
    </main>

    <script>
        function toggleMenu() {
            document.getElementById('navbar').classList.toggle('active');
        }
    </script>
</body>

</html>