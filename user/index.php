<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$modul = (isset($_GET['m'])) ? $_GET['m'] : "awal";
$jawal = "User || User";
switch ($modul) {
    case 'dashboard':
    default:
        $aktif = "Dashboard";
        $judul = "Dashboard $jawal";
        include "dashboard.php";
        break;
    case 'poly':
        $aktif = "Poly";
        $judul = "Poly $jawal";
        include "poly/poly.php";
        break;
    case 'history':
        $aktif = "History";
        $judul = "History $jawal";
        include "history/title.php";
        break;
    case 'profile':
        $aktif = "Profile";
        $judul = "Profile $jawal";
        include "profile/title.php";
        break;
}
