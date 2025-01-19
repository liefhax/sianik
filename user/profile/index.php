<?php
require 'functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$modul = isset($_GET['m']) ? $_GET['m'] : "awal";

switch ($modul) {
    case 'profile':
        include "title.php";
        break;
    default:
        include "title.php";
}
?>
