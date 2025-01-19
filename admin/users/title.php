<?php
require 'sesi.php';
require 'functions.php'; // Pastikan file functions.php sudah berisi definisi kelas User dan Database

// Membuat koneksi database
$db = new Database();
$connection = $db->conn;

// Membuat objek User
$user = new User($connection);

// Menangani pendaftaran pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    // Ambil data dari form
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];
    $password = $_POST['password'];

    // Panggil metode register dari objek User
    $result = $user->register($name, $username, $email, $no_hp, $password);

    // Tampilkan pesan hasil
    echo $result;
}

// Menampilkan semua pengguna
$allUsers = $user->getAllUsers();
?>
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna - Admin Panel</title>
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
            --red: #E53E3E;
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
            z-index: 1000;
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

        .content h2 {
            color: var(--primary-blue);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        /* User List Styles */
        .user-list {
            background-color: var(--white);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .user-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #EDF2F7;
        }

        .user-item:last-child {
            border-bottom: none;
        }

        .user-info {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .user-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }


        .btn:hover {
            opacity: 0.9;
        }

        /* Form Styles */
        .form-section {
            background-color: var(--white);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--gray);
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #E2E8F0;
            border-radius: 0.375rem;
            margin-bottom: 0.5rem;
            font-size: 0.95rem;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(43, 108, 176, 0.1);
        }

        .submit-btn {
            background-color: var(--primary-blue);
            color: var(--white);
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.375rem;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: auto;
            min-width: 120px;
        }

        .submit-btn:hover {
            background-color: var(--hover-blue);
        }

        /* Alert Messages */
        .alert {
            padding: 1rem;
            border-radius: 0.375rem;
            margin-bottom: 1rem;
        }

        .alert-success {
            background-color: #C6F6D5;
            color: #2F855A;
        }

        .alert-error {
            background-color: #FED7D7;
            color: #C53030;
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

            .user-item {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .user-actions {
                width: 100%;
                justify-content: flex-end;
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
                    <li><a href="index.php?m=dashboard">Dashboard</a></li>
                    <li class="active"><a href="index.php?m=users">Kelola Pengguna</a></li>
                    <li><a href="index.php?m=poly">Kelola Poli</a></li>
                    <li><a href="index.php?m=antrian">Riwayat Antrian</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="content">
            <?php if (isset($message)): ?>
                <div class="alert <?php echo $success ? 'alert-success' : 'alert-error'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <section class="user-list">
                <h2>Daftar Pengguna</h2>
                <?php if (!empty($allUsers)): ?>
                    <?php foreach ($allUsers as $user): ?>
                        <div class="user-item">
                            <div class="user-info">
                                <span><?php echo htmlspecialchars($user['username']); ?></span>
                                <span class="text-gray"><?php echo htmlspecialchars($user['email']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Belum ada pengguna terdaftar.</p>
                <?php endif; ?>
            </section>

            <section class="form-section">
                <h2>Tambah Pengguna Baru</h2>
                <form action="index.php?m=users" method="POST">
                    <div class="form-group">
                        <label for="name">Nama:</label>
                        <input type="text" name="name" id="name" required>
                    </div>

                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" name="username" id="username" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" name="email" id="email" required>
                    </div>

                    <div class="form-group">
                        <label for="no_hp">No. HP:</label>
                        <input type="text" name="no_hp" id="no_hp" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" name="password" id="password" required>
                    </div>

                    <button type="submit" name="add_user" class="submit-btn">Tambah Pengguna</button>
                </form>
            </section>
        </main>
    </div>
</body>
</html>