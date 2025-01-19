<?php
require 'function.php';  // Include the necessary functions

$db = new Database();
$queue = new Queue($db->conn);
$username = $_SESSION['username'];
$history = $queue->getQueueHistory($username);  // Fetch the queue history for the logged-in user
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Antrian</title>
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

        .table-container {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            padding: 1.5rem;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        th {
            background-color: #f8fafc;
            color: #4a5568;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem;
            text-align: left;
            border-bottom: 2px solid #e2e8f0;
        }

        td {
            padding: 1rem;
            color: #1a1a1a;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.875rem;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .print-link {
            display: inline-flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background: #e8f2ff;
            color: #2563eb;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .print-link:hover {
            background: #2563eb;
            color: white;
        }

        .print-link i {
            margin-right: 0.5rem;
        }

        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6b7280;
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
        }
    </style>
</head>
<body>

    <!-- Menu Toggle Button -->
    <button class="menu-toggle" onclick="toggleMenu()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Navbar -->
    <nav class="navbar" id="navbar">
        <h2>Menu</h2>
        <ul>
            <li><a href="index.php?m=awal"><i class="fas fa-home"></i>Dashboard</a></li>
            <li><a href="index.php?m=poly&s=awal"><i class="fas fa-hospital"></i>Poli</a></li>
            <li class="active"><a href="index.php?m=history&s=awal"><i class="fas fa-history"></i>Riwayat Antrian</a></li>
            <li><a href="index.php?m=profile&s=awal"><i class="fas fa-cog"></i>Pengaturan</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
        </ul>
    </nav>

    <!-- Main Content Section -->
    <main class="content">
        <h1>Riwayat Antrian</h1>

        <!-- Table to display queue history -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Poli</th>
                        <th>Dokter</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($history)): ?>
                        <?php foreach ($history as $index => $queue): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($queue['poli_name']); ?></td>
                                <td><?php echo htmlspecialchars($queue['doctor_name']); ?></td>
                                <td><?php echo htmlspecialchars($queue['queue_date']); ?></td>
                                <td>
                                <a href="history/print_queue.php?id=<?php echo urlencode($queue['queue_number']); ?>" 
                                       class="print-link" 
                                       target="_blank">
                                        <i class="fas fa-print"></i>
                                        Print
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>Tidak ada riwayat antrian.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Script to toggle the menu -->
    <script>
        function toggleMenu() {
            document.getElementById('navbar').classList.toggle('active');
        }
    </script>

</body>
</html>


