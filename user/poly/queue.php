<?php
require 'functions.php';
session_start();

// Pastikan user sudah login
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

$username = $_SESSION['username'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['doctor_id']) && ctype_digit($_POST['doctor_id'])) {
        // Ambil ID dokter
        $doctorId = $_POST['doctor_id'];

        // Proses mengambil antrian
        $queueResult = takeQueue($username, $doctorId);
        header('Location: index.php?m=poly&message=' . urlencode($queueResult));
        exit();
    }

    if (isset($_POST['cancel_queue'])) {
        // Proses membatalkan antrian
        $cancelResult = deleteQueue($username);
        header('Location: index.php?m=poly&message=' . urlencode($cancelResult));
        exit();
    }
}

// Redirect jika akses tidak valid
header('Location: index.php?m=poly&error=' . urlencode('Permintaan tidak valid.'));
exit();
?>
