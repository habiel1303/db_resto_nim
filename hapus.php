<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

$id = $_GET['id'];

// Ambil data foto untuk dihapus
$query = "SELECT foto_menu FROM menu WHERE id_menu = $id";
$result = mysqli_query($conn, $query);
$menu = mysqli_fetch_assoc($result);

if ($menu) {
    // Hapus file gambar jika ada
    if ($menu['foto_menu'] && file_exists('img/' . $menu['foto_menu'])) {
        unlink('img/' . $menu['foto_menu']);
    }
    
    // Hapus record dari database
    $query = "DELETE FROM menu WHERE id_menu = $id";
    mysqli_query($conn, $query);
}

header("Location: index.php");
exit();
?>