<?php
// config/koneksi.php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_resto_nim';

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
