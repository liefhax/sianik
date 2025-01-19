<?php
require 'functions.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$modul=(isset($_GET['s']))?$_GET['s']:"awal";
switch($modul){
	case 'awal': default: include "poly/poly.php"; break;
	case 'tambah_setoran': include "poly/tambah_setoran.php"; break;
	
}
?>
