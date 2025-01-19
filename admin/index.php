<?php

$modul = (isset($_GET['m'])) ? $_GET['m'] : "dashboard"; // Default ke dashboard
$jawal = "Admin || Admin";

switch ($modul) {
    case 'dashboard':
    default:
        $aktif = "Dashboard";
        $judul = "Dashboard $jawal";
        include "dashboard.php";
        break;
    case 'users':
        $aktif = "Users";
        $judul = "Users $jawal";
        include "users/title.php";
        break;
    case 'poly':
        $aktif = "poly";
        $judul = "poly $jawal";
        include "poly/title.php";
        break;
    case 'antrian':
        $aktif = "Antrian";
        $judul = "Antrian $jawal";
        include "antrian/title.php";
        break;
    case 'panggilan':
        $aktif = "Panggil";
        $judul = "Panggil $jawal";
        include "panggil/title.php";
        break;
}
?>
