<?php
require 'functions.php';

$modul = isset($_GET['m']) ? $_GET['m'] : "awal";

switch ($modul) {
    case 'users':
        include "title.php";
        break;
    default:
        include "title.php";
}
?>
