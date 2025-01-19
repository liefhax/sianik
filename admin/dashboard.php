<?php
require 'sesi.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Klinik</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary-blue: #2B6CB0;
            --light-blue: #EBF4FF;
            --hover-blue: #2C5282;
            --white: #ffffff;
            --gray: #718096;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 280px;
            background-color: var(--primary-blue);
            color: var(--white);
            padding: 2rem;
            position: fixed;
            height: 100vh;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            padding-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
        }

        .navbar ul {
            list-style: none;
        }

        .navbar li {
            margin-bottom: 0.5rem;
        }

        .navbar a {
            color: var(--white);
            text-decoration: none;
            display: block;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }

        .navbar a:hover, .navbar .active a {
            background-color: var(--hover-blue);
        }

        /* Main Content Styles */
        .content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
            background-color: var(--light-blue);
        }

        .welcome-section {
            background-color: var(--white);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }

        .welcome-section h1 {
            color: var(--primary-blue);
            margin-bottom: 1rem;
            font-size: 2rem;
        }

        .welcome-section p {
            color: var(--gray);
            line-height: 1.6;
        }

        .visi-misi-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .content-box {
            background-color: var(--white);
            padding: 2rem;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .content-box h2 {
            color: var(--primary-blue);
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .content-box p, .content-box ol {
            color: var(--gray);
            line-height: 1.6;
        }

        .content-box ol {
            margin-left: 1.5rem;
        }

        .content-box li {
            margin-bottom: 0.5rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }

            .container {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">🏥 Admin Panel</div>
            <nav class="navbar">
                <ul>
                    <li class="active"><a href="index.php?m=dashboard">Dashboard</a></li>
                    <li><a href="index.php?m=users">Kelola Pengguna</a></li>
                    <li><a href="index.php?m=poly">Kelola Poli</a></li>
                    <li><a href="index.php?m=antrian">Riwayat Antrian</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <section class="welcome-section">
                <h1>Selamat Datang, Admin!</h1>
                <p>Anda dapat mengelola sistem melalui menu di samping kiri.</p>
            </section>
            <section class="visi-misi-section">
                <div class="content-box">
                    <h2>Visi</h2>
                    <p>Menjadi klinik kesehatan yang berkualitas, bermanfaat dan terpercaya dalam upaya menuju masyarakat sehat</p>
                </div>
                <div class="content-box">
                    <h2>Misi</h2>
                    <ol>
                        <li>Memberikan pelayanan dasar kesehatan yang profesional kepada individu maupun keluarga dalam masyarakat.</li>
                        <li>Menjadi mitra masyarakat yang utama dengan bertukar informasi dan edukasi kesehatan kepada lingkungan sekitar dalam usaha membantu pemerintah mewujudkan masyarakat yang sehat.</li>
                        <li>Mengembangkan kemitraan dengan organisasi/lembaga/instansi maupun perusahaan menyediakan pelayanan kesehatan bermutu dan terjangkau.</li>
                    </ol>
                </div>
            </section>
        </main>
    </div>
</body>
</html>