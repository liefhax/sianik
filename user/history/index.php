<?php
require 'functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$modul = isset($_GET['m']) ? $_GET['m'] : "awal";

switch ($modul) {
    case 'history':
        include "title.php";
        break;
    case 'queue':
        include "print_queue.php";
        break;
    default:
        include "title.php";
}
?>