<?php
require 'sesi.php';
require 'functions.php';

// Koneksi database dan pembuatan objek Queue
$db = new Database();
$connection = $db->conn;
$queue = new Queue($connection);

// Proses reset antrian jika tombol ditekan
if (isset($_POST['reset'])) {
    $queue->resetQueues();  // Reset antrian menggunakan metode kelas Queue
    echo "<script>alert('Antrian telah direset!');</script>";
}

// Ambil data antrian
$queues = $queue->getQueues();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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

        .content {
            flex: 1;
            margin-left: 280px;
            padding: 2rem;
            background-color: var(--light-blue);
        }

        .card {
            background-color: var(--white);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: var(--primary-blue);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }

        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #EDF2F7;
        }

        th {
            background-color: var(--primary-blue);
            color: var(--white);
        }

        button {
            background-color: var(--primary-blue);
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: var(--hover-blue);
        }

        .delete-button {
            background-color: var(--red);
        }

        .delete-button:hover {
            background-color: #C53030;
        }
    </style>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">🏥 Admin Panel</div>
            <nav class="navbar">
                <ul>
                    <li><a href="index.php?m=dashboard">Dashboard</a></li>
                    <li><a href="index.php?m=users">Kelola Pengguna</a></li>
                    <li><a href="index.php?m=poly">Kelola Poli</a></li>
                    <li class="active"><a href="index.php?m=antrian">Riwayat Antrian</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <div class="content">
            <div class="card">
                <h2>Riwayat Antrian</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Doctor ID</th>
                            <th>Nomor Antrian</th>
                            <th>Tanggal Antrian</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($queueData = mysqli_fetch_assoc($queues)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($queueData['username']); ?></td>
                                <td><?php echo htmlspecialchars($queueData['doctor_id']); ?></td>
                                <td><?php echo htmlspecialchars($queueData['queue_number']); ?></td>
                                <td><?php echo htmlspecialchars($queueData['queue_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <form method="POST" action="">
                    <button type="submit" name="reset" onclick="return confirm('Apakah Anda yakin ingin mereset antrian?')">Reset Antrian</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>