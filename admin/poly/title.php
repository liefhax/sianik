<?php
require 'sesi.php';
require 'functions.php';

// Koneksi database dan pembuatan objek
$db = new Database();
$connection = $db->conn;

$doctor = new Doctor($connection);
$poli = new Poli($connection);

// Menangani penambahan dokter
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['doctor_name']) && isset($_POST['poli_id'])) {
    $doctorName = $_POST['doctor_name'];
    $poliId = $_POST['poli_id'];
    echo $doctor->addDoctor($doctorName, $poliId);
    header("Location: index.php?m=poly"); // Redirect ke halaman yang sama
    exit();
}

// Menangani pengeditan dokter
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_doctor_id'])) {
    $doctorId = $_POST['edit_doctor_id'];
    $newDoctorName = $_POST['new_doctor_name'];
    echo $doctor->updateDoctor($doctorId, $newDoctorName);
    header("Location: index.php?m=poly"); // Redirect ke halaman yang sama
    exit();
}

// Menangani penghapusan dokter
if (isset($_GET['delete_doctor'])) {
    $doctorId = $_GET['delete_doctor'];
    echo $doctor->deleteDoctor($doctorId);
    header("Location: index.php?m=poly"); // Redirect ke halaman yang sama
    exit();
}

// Menampilkan semua dokter dan poli
$allDoctors = $doctor->getAllDoctors();
$allPoli = $poli->getAllPoli();
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

        ul {
            list-style: none;
        }

        .doctor-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #EDF2F7;
        }

        button {
            background-color: var(--primary-blue);
            color: var(--white);
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            cursor: pointer;
            margin-left: 0.5rem;
        }

        button a {
            color: var(--white);
            text-decoration: none;
        }

        .delete-button {
            background-color: var(--red);
        }

        input[type="text"],
        select {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #E2E8F0;
            border-radius: 0.375rem;
            margin: 0.5rem 0 1rem;
        }

        label {
            display: block;
            margin-top: 1rem;
            color: var(--gray);
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
        <aside class="sidebar">
            <div class="logo">🏥 Admin Panel</div>
            <nav class="navbar">
                <ul>
                    <li><a href="index.php?m=dashboard">Dashboard</a></li>
                    <li><a href="index.php?m=users">Kelola Pengguna</a></li>
                    <li class="active"><a href="index.php?m=poly">Kelola Poli</a></li>
                    <li><a href="index.php?m=antrian">Riwayat Antrian</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </nav>
        </aside>

        <div class="content">
            <div class="card">
                <h2>Kelola Dokter</h2>
                <ul>
                    <?php if (!empty($allDoctors)): ?>
                        <?php foreach ($allDoctors as $doctor): ?>
                            <li class="doctor-item">
                                <?php echo htmlspecialchars($doctor['doctor_name']); ?>
                                <div>
                                    <button>
                                        <a href="index.php?m=poly&edit_doctor=<?php echo htmlspecialchars($doctor['id']); ?>">Edit</a>
                                    </button>
                                    <button class="delete-button">
                                        <a href="index.php?m=poly&delete_doctor=<?php echo htmlspecialchars($doctor['id']); ?>">Hapus</a>
                                    </button>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li class="doctor-item">Tidak ada dokter yang ditemukan.</li>
                    <?php endif; ?>
                </ul>
            </div>

            <div class="card">
                <h2>Tambahkan Dokter Baru</h2>
                <form action="index.php?m=poly" method="POST">
                    <label for="doctor_name">Nama Dokter:</label>
                    <input type="text" name="doctor_name" id="doctor_name" required>
                    
                    <label for="poli_id">Pilih Poli:</label>
                    <select name="poli_id" required>
                        <?php foreach ($allPoli as $poli): ?>
                            <option value="<?php echo htmlspecialchars($poli['id']); ?>">
                                <?php echo htmlspecialchars($poli['poli_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Tambahkan Dokter</button>
                </form>
            </div>

            <?php if (isset($_GET['edit_doctor'])): ?>
                <div class="card">
                    <h2>Edit Dokter</h2>
                    <form action="index.php?m=poly" method="POST">
                        <input type="hidden" name="edit_doctor_id" value="<?php echo htmlspecialchars($_GET['edit_doctor']); ?>">
                        <label for="edit_doctor_name">Nama Dokter Baru:</label>
                        <input type="text" name="new_doctor_name" id="edit_doctor_name" required>
                        <button type="submit">Update Dokter</button>
                    </form>
                </div>
            <?php endif; ?>

            <div class="card">
                <h2>Tambahkan Poli Baru</h2>
                <form action="index.php?m=poly" method="POST">
                    <label for="poli_name">Nama Poli:</label>
                    <input type="text" name="poli_name" id="poli_name" required>
                    <button type="submit">Tambahkan Poli</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>