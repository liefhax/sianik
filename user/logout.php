<?php
// Memasukkan file yang berisi kelas Session dan functions.php
require 'function.php';

// Membuat objek Session
$session = new Session();

// Memanggil metode logout untuk mengakhiri sesi
$session->logout();

// Redirect ke halaman login setelah logout
header('Location: login.php');
exit();
