<?php
include 'koneksi.php';

echo "<h2>Menambahkan Data Jenis Menu dan Supplier</h2>";

// Add jenis_menu data
echo "<h3>Jenis Menu:</h3>";
$jenis_menu = ['Makanan', 'Minuman', 'Snack'];

foreach ($jenis_menu as $jenis) {
    // Check if exists
    $check = mysqli_query($conn, "SELECT * FROM jenis_menu WHERE nama_jenis='$jenis'");
    if (mysqli_num_rows($check) == 0) {
        $query = "INSERT INTO jenis_menu (nama_jenis) VALUES ('$jenis')";
        if (mysqli_query($conn, $query)) {
            echo "✅ $jenis berhasil ditambahkan<br>";
        } else {
            echo "❌ Gagal menambahkan $jenis: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "ℹ️ $jenis sudah ada<br>";
    }
}

// Add supplier data
echo "<h3>Supplier:</h3>";
$suppliers = ['PT. Sumber Rasa', 'Depot Segar'];

foreach ($suppliers as $supplier) {
    // Check if exists
    $check = mysqli_query($conn, "SELECT * FROM supplier WHERE nama_supplier='$supplier'");
    if (mysqli_num_rows($check) == 0) {
        $query = "INSERT INTO supplier (nama_supplier) VALUES ('$supplier')";
        if (mysqli_query($conn, $query)) {
            echo "✅ $supplier berhasil ditambahkan<br>";
        } else {
            echo "❌ Gagal menambahkan $supplier: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "ℹ️ $supplier sudah ada<br>";
    }
}

echo "<br><h3>Selesai!</h3>";
echo "Sekarang Anda bisa menambah menu dengan pilihan:<br>";
echo "- Jenis: Makanan, Minuman, Snack<br>";
echo "- Supplier: PT. Sumber Rasa, Depot Segar";
?>
