<?php
include 'koneksi.php';

echo "<h1>Fix Database Tables</h1><hr>";

// Disable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 0");

echo "<h3>1. Drop semua tabel...</h3>";
mysqli_query($conn, "DROP TABLE IF EXISTS menu");
mysqli_query($conn, "DROP TABLE IF EXISTS supplier");
mysqli_query($conn, "DROP TABLE IF EXISTS jenis_menu");
mysqli_query($conn, "DROP TABLE IF EXISTS users");
echo "✅ Semua tabel dihapus<br>";

echo "<hr><h3>2. Membuat tabel baru...</h3>";

// Create users table
$query = "CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
mysqli_query($conn, $query);
echo "✅ Tabel users dibuat<br>";

// Create jenis_menu table
$query = "CREATE TABLE jenis_menu (
    id_jenis INT AUTO_INCREMENT PRIMARY KEY,
    nama_jenis VARCHAR(50) NOT NULL
)";
mysqli_query($conn, $query);
echo "✅ Tabel jenis_menu dibuat<br>";

// Create supplier table
$query = "CREATE TABLE supplier (
    id_supplier INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(100) NOT NULL
)";
mysqli_query($conn, $query);
echo "✅ Tabel supplier dibuat<br>";

// Create menu table with CORRECT foreign keys
$query = "CREATE TABLE menu (
    id_menu INT AUTO_INCREMENT PRIMARY KEY,
    nama_menu VARCHAR(100) NOT NULL,
    id_jenis INT,
    id_supplier INT,
    harga DECIMAL(10,2),
    stok INT,
    foto_menu VARCHAR(255),
    FOREIGN KEY (id_jenis) REFERENCES jenis_menu(id_jenis) ON DELETE CASCADE,
    FOREIGN KEY (id_supplier) REFERENCES supplier(id_supplier) ON DELETE CASCADE
)";
mysqli_query($conn, $query);
echo "✅ Tabel menu dibuat dengan foreign key yang benar<br>";

// Re-enable foreign key checks
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS = 1");

echo "<hr><h3>3. Menambahkan data...</h3>";

// Add user
mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('admin', 'admin123')");
echo "✅ User admin ditambahkan<br>";

// Add jenis_menu
$jenis = ['Makanan', 'Minuman', 'Snack'];
foreach ($jenis as $j) {
    mysqli_query($conn, "INSERT INTO jenis_menu (nama_jenis) VALUES ('$j')");
}
echo "✅ Jenis menu ditambahkan: " . implode(", ", $jenis) . "<br>";

// Add supplier
$suppliers = ['PT. Sumber Rasa', 'Depot Segar'];
foreach ($suppliers as $s) {
    mysqli_query($conn, "INSERT INTO supplier (nama_supplier) VALUES ('$s')");
}
echo "✅ Supplier ditambahkan: " . implode(", ", $suppliers) . "<br>";

// Add sample menu
$menus = [
    ['Nasi Goreng Special', 1, 1, 25000, 50],
    ['Es Teh Manis', 2, 2, 5000, 100],
    ['Kentang Goreng', 3, 1, 15000, 30],
];
foreach ($menus as $m) {
    mysqli_query($conn, "INSERT INTO menu (nama_menu, id_jenis, id_supplier, harga, stok) VALUES ('$m[0]', $m[1], $m[2], $m[3], $m[4])");
}
echo "✅ Sample menu ditambahkan: " . count($menus) . " item<br>";

echo "<hr><h3>4. Verifikasi dengan JOIN:</h3>";
$query = "SELECT m.nama_menu, j.nama_jenis, s.nama_supplier, m.harga, m.stok
          FROM menu m 
          INNER JOIN jenis_menu j ON m.id_jenis = j.id_jenis 
          INNER JOIN supplier s ON m.id_supplier = s.id_supplier 
          ORDER BY m.id_menu DESC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    echo "✅ <strong>" . mysqli_num_rows($result) . " data ditemukan!</strong><br><br>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "• " . $row['nama_menu'] . " | " . $row['nama_jenis'] . " | " . $row['nama_supplier'] . " | Rp " . number_format($row['harga']) . " | Stok: " . $row['stok'] . "<br>";
    }
    echo "<br><a href='index.php'><button>Lihat Menu</button></a>";
} else {
    echo "❌ Error: " . mysqli_error($conn) . "<br>";
}
?>
