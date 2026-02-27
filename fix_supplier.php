<?php
include 'koneksi.php';

echo "<h1>Fix Supplier Table</h1><hr>";

// Check current supplier data
echo "<h3>Data supplier saat ini:</h3>";
$result = mysqli_query($conn, "SELECT * FROM supplier");
while ($row = mysqli_fetch_assoc($result)) {
    echo "ID: " . $row['id_supplier'] . " | Nama: " . $row['nama_supplier'] . "<br>";
}

// Drop and recreate supplier table
echo "<hr><h3>Memperbaiki tabel supplier...</h3>";
mysqli_query($conn, "DROP TABLE IF EXISTS supplier");

$query = "CREATE TABLE supplier (
    id_supplier INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(100) NOT NULL
)";
mysqli_query($conn, $query);
echo "✅ Tabel supplier dibuat ulang<br>";

// Insert supplier data
$suppliers = ['PT. Sumber Rasa', 'Depot Segar'];
foreach ($suppliers as $supplier) {
    mysqli_query($conn, "INSERT INTO supplier (nama_supplier) VALUES ('$supplier')");
    echo "✅ Ditambahkan: $supplier<br>";
}

echo "<hr><h3>Verifikasi:</h3>";
$result = mysqli_query($conn, "SELECT * FROM supplier");
while ($row = mysqli_fetch_assoc($result)) {
    echo "ID: " . $row['id_supplier'] . " | Nama: " . $row['nama_supplier'] . "<br>";
}

echo "<hr><h3>Update menu dengan ID supplier yang benar:</h3>";
// Update all menu items with supplier ID
$menu_result = mysqli_query($conn, "SELECT id_menu, nama_menu FROM menu");
while ($menu = mysqli_fetch_assoc($menu_result)) {
    $id_menu = $menu['id_menu'];
    // Alternate between supplier 1 and 2
    $id_supplier = ($id_menu % 2 == 0) ? 1 : 2;
    mysqli_query($conn, "UPDATE menu SET id_supplier='$id_supplier' WHERE id_menu='$id_menu'");
    echo "✅ Menu ID $id_menu -> Supplier ID $id_supplier<br>";
}

echo "<hr><h3>Test JOIN query:</h3>";
$query = "SELECT m.*, j.nama_jenis, s.nama_supplier 
          FROM menu m 
          INNER JOIN jenis_menu j ON m.id_jenis = j.id_jenis 
          INNER JOIN supplier s ON m.id_supplier = s.id_supplier 
          ORDER BY m.id_menu DESC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    echo "✅ Data ditemukan: " . mysqli_num_rows($result) . " row(s)<br>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "- " . $row['nama_menu'] . " | " . $row['nama_jenis'] . " | " . $row['nama_supplier'] . "<br>";
    }
    echo "<br><a href='index.php'>Klik di sini untuk melihat menu</a>";
} else {
    echo "❌ Error: " . mysqli_error($conn) . "<br>";
}
?>
