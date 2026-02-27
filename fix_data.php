<?php
include 'koneksi.php';

echo "<h1>Fix Database Data</h1><hr>";

// Check if tables exist
$tables = ['jenis_menu', 'supplier', 'menu'];
foreach ($tables as $table) {
    $result = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($result) == 0) {
        echo "❌ Tabel '$table' tidak ada!<br>";
        echo "<p>Silakan buat tabel terlebih dahulu di phpMyAdmin atau jalankan setup_complete.php</p>";
        exit();
    }
}

echo "<h3>1. Menambah data Jenis Menu:</h3>";
$jenis = ['Makanan', 'Minuman', 'Snack'];
foreach ($jenis as $j) {
    $check = mysqli_query($conn, "SELECT id_jenis FROM jenis_menu WHERE nama_jenis='$j'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO jenis_menu (nama_jenis) VALUES ('$j')");
        echo "✅ $j<br>";
    } else {
        echo "ℹ️ $j sudah ada<br>";
    }
}

echo "<h3>2. Menambah data Supplier:</h3>";
$suppliers = ['PT. Sumber Rasa', 'Depot Segar'];
foreach ($suppliers as $s) {
    $check = mysqli_query($conn, "SELECT id_supplier FROM supplier WHERE nama_supplier='$s'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO supplier (nama_supplier) VALUES ('$s')");
        echo "✅ $s<br>";
    } else {
        echo "ℹ️ $s sudah ada<br>";
    }
}

echo "<h3>3. Update ID yang kosong/null:</h3>";
// Get valid IDs
$jenis_result = mysqli_query($conn, "SELECT id_jenis, nama_jenis FROM jenis_menu");
$jenis_map = [];
while ($row = mysqli_fetch_assoc($jenis_result)) {
    $jenis_map[$row['nama_jenis']] = $row['id_jenis'];
}

$supplier_result = mysqli_query($conn, "SELECT id_supplier, nama_supplier FROM supplier");
$supplier_map = [];
while ($row = mysqli_fetch_assoc($supplier_result)) {
    $supplier_map[$row['nama_supplier']] = $row['id_supplier'];
}

echo "Jenis ID: " . json_encode($jenis_map) . "<br>";
echo "Supplier ID: " . json_encode($supplier_map) . "<br><br>";

// Update menu with null/empty id_jenis
$update = "UPDATE menu SET id_jenis = 1 WHERE id_jenis IS NULL OR id_jenis = '' OR id_jenis = 0";
mysqli_query($conn, $update);
echo "✅ Semua menu设为 Jenis ID 1 (Makanan)<br>";

// Update menu with null/empty id_supplier
$update = "UPDATE menu SET id_supplier = 1 WHERE id_supplier IS NULL OR id_supplier = '' OR id_supplier = 0";
mysqli_query($conn, $update);
echo "✅ Semua menu设为 Supplier ID 1 (PT. Sumber Rasa)<br>";

echo "<h3>4. Verifikasi:</h3>";
$query = "SELECT m.nama_menu, j.nama_jenis, s.nama_supplier 
          FROM menu m 
          LEFT JOIN jenis_menu j ON m.id_jenis = j.id_jenis 
          LEFT JOIN supplier s ON m.id_supplier = s.id_supplier 
          ORDER BY m.id_menu DESC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    echo "✅ <strong>" . mysqli_num_rows($result) . " data ditemukan!</strong><br><br>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "• " . $row['nama_menu'] . " | " . $row['nama_jenis'] . " | " . $row['nama_supplier'] . "<br>";
    }
    echo "<br><a href='index.php'><button>Lihat Menu</button></a>";
} else {
    echo "❌ Error: " . mysqli_error($conn) . "<br>";
}
?>
