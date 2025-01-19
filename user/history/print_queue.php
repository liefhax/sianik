<?php
require '../function.php';

session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Inisialisasi Objek
$database = new Database(); // Objek Database untuk koneksi
$user = new User($database->conn); // Objek User
$queue = new Queue($database->conn); // Objek Queue

$username = $_SESSION['username'];
$userInfo = $user->getUserInfo($username); // Mengambil informasi pengguna
$history = $queue->getQueueHistory($username); // Mengambil riwayat antrian pengguna
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../asset/dashboard.css">
    <link rel="stylesheet" href="../../vendor/css/print.css">
    <title>Cetak Antrian</title>
</head>
<body>
    <div class="container">
        <div class="jumbotron">
            <center>
                <img src="../../vendor/img/logo.png" alt="Logo Klinik" class="logo">
            </center>
        </div>
        <table class="table">
            <tr>
                <td>Nama</td>
                <td>: <?php echo htmlspecialchars($userInfo['name']); ?></td>
            </tr>
            <tr>
                <td>Email</td>
                <td>: <?php echo htmlspecialchars($userInfo['email']); ?></td>
            </tr>
            <tr>
                <td>No HP</td>
                <td>: <?php echo htmlspecialchars($userInfo['no_hp']); ?></td>
            </tr>
        </table>
        <br>
        <table class="table table-striped">
            <tr>
                <th>No</th>
                <th>Poli</th>
                <th>Dokter</th>
                <th>Nomor Antrian</th>
                <th>Tanggal</th>
            </tr>
            <?php if (!empty($history)): ?>
                <?php foreach ($history as $index => $queue): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($queue['poli_name']); ?></td>
                        <td><?php echo htmlspecialchars($queue['doctor_name']); ?></td>
                        <td><?php echo htmlspecialchars($queue['queue_number']); ?></td>
                        <td><?php echo htmlspecialchars($queue['queue_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Tidak ada riwayat antrian.</td>
                </tr>
            <?php endif; ?>
        </table>
        <br><br>
        <div class="pull-right">
            <h5>Tanda Tangan</h5>
            <h5>Yang Bersangkutan</h5>
            <br><br><br>
            <h5>(<?php echo htmlspecialchars($userInfo['name']); ?>)</h5>
        </div>
    </div>
    <script>
        window.print();
    </script>
</body>
</html>
