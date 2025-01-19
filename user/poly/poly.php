<?php 
require 'function.php'; // Adjust to the path where the OOP classes are defined.

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Inisialisasi database dan objek kelas
$db = new Database();
$conn = $db->conn;

$user = new User($conn);
$poli = new Poli($conn);
$queue = new Queue($conn);
$doctor = new Doctor($conn);

$username = $_SESSION['username'];
$allPoli = $poli->getAllPoli();
$selectedPoli = isset($_GET['poli_id']) && ctype_digit($_GET['poli_id']) ? intval($_GET['poli_id']) : null;
$doctors = $selectedPoli ? $doctor->getDoctorsByPoli($selectedPoli) : [];
$userQueue = $queue->getUserQueue($username);

// Logika untuk POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['cancel_queue'])) {
        $queue->deleteQueue($username);
        header("Location: index.php?m=poly&message=" . urlencode("Antrian berhasil dibatalkan"), true, 303);
        exit();
    } elseif (isset($_POST['doctor_id']) && ctype_digit($_POST['doctor_id'])) {
        $doctorId = intval($_POST['doctor_id']);
        $queueResult = $queue->takeQueue($username, $doctorId);
        header("Location: index.php?m=poly&message=" . urlencode($queueResult), true, 303);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Poli</title>
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

        .message {
            background: #ffffff;
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            color: #2563eb;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .poli-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-top: 2rem;
        }

        .poli-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            text-decoration: none;
            color: #1a1a1a;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .poli-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .poli-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1rem;
            object-fit: contain;
        }

        .poli-card p {
            font-weight: 500;
            color: #2563eb;
            margin-top: 0.5rem;
        }

        .doctor-list {
            background: #ffffff;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .doctor-list h2 {
            color: #1a1a1a;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .doctor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .doctor-card {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }

        .doctor-card p {
            color: #1a1a1a;
            font-size: 1.125rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .button:hover {
            background: #1d4ed8;
        }

        .button i {
            margin-right: 0.5rem;
        }

        .queue-info {
            background: #ffffff;
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .queue-info h2 {
            color: #1a1a1a;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .queue-info p {
            color: #4a5568;
            margin-bottom: 0.75rem;
            font-size: 1.125rem;
        }

        .queue-number {
            font-size: 2.5rem;
            color: #2563eb;
            font-weight: 700;
            margin: 1.5rem 0;
        }

        .cancel-button {
            background: #ef4444;
        }

        .cancel-button:hover {
            background: #dc2626;
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

            .poli-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
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
            <li class="active"><a href="index.php?m=poly&s=awal"><i class="fas fa-hospital"></i>Poli</a></li>
            <li><a href="index.php?m=history&s=awal"><i class="fas fa-history"></i>Riwayat Antrian</a></li>
            <li><a href="index.php?m=profile&s=awal"><i class="fas fa-cog"></i>Pengaturan</a></li>
            <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></li>
        </ul>
    </nav>

    <main class="content">
        <h1>Pilih Poli</h1>

        <?php if (isset($_GET['message'])): ?>
            <div class="message">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <?php if ($userQueue): ?>
            <div class="queue-info">
                <h2>Informasi Antrian Anda</h2>
                <p>Dokter: <?php echo htmlspecialchars($userQueue['doctor_name']); ?></p>
                <div class="queue-number">
                    No. <?php echo $userQueue['queue_number']; ?>
                </div>
                <form method="POST">
                    <button type="submit" name="cancel_queue" class="button cancel-button">
                        <i class="fas fa-times"></i>
                        Batalkan Antrian
                    </button>
                </form>
            </div>
        <?php elseif ($selectedPoli): ?>
            <div class="doctor-list">
                <h2>Dokter yang Tersedia</h2>
                <div class="doctor-grid">
                    <?php foreach ($doctors as $doctor): ?>
                        <div class="doctor-card">
                            <p><?php echo htmlspecialchars($doctor['doctor_name']); ?></p>
                            <form method="POST">
                                <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctor['id']); ?>">
                                <button type="submit" class="button">
                                    <i class="fas fa-ticket-alt"></i>
                                    Ambil Antrian
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="poli-grid">
                <a href="?m=poly&poli_id=1" class="poli-card">
                    <img src="../vendor/img/icon1.png" alt="Poli Umum" class="poli-icon">
                    <p>Poli Umum</p>
                </a>
                <a href="?m=poly&poli_id=3" class="poli-card">
                    <img src="../vendor/img/icon3.png" alt="Poli Kandungan" class="poli-icon">
                    <p>Poli Kandungan</p>
                </a>
                <a href="?m=poly&poli_id=4" class="poli-card">
                    <img src="../vendor/img/icon2.png" alt="Poli Gigi" class="poli-icon">
                    <p>Poli Gigi</p>
                </a>
                <a href="?m=poly&poli_id=5" class="poli-card">
                    <img src="../vendor/img/icon4.png" alt="Poli Mata" class="poli-icon">
                    <p>Poli Mata</p>
                </a>
            </div>
        <?php endif; ?>
    </main>

    <script>
        function toggleMenu() {
            document.getElementById('navbar').classList.toggle('active');
        }
    </script>
</body>
</html>


