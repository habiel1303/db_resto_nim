<?php
include 'koneksi.php';

echo "<h1>Setup Lengkap Database</h1><hr>";

// 1. Create tables if not exist
echo "<h3>1. Membuat tabel...</h3>";

// Create jenis_menu table
$query = "CREATE TABLE IF NOT EXISTS jenis_menu (
    id_jenis INT AUTO_INCREMENT PRIMARY KEY,
    nama_jenis VARCHAR(50) NOT NULL
)";
if (mysqli_query($conn, $query)) {
    echo "✅ Tabel jenis_menu berhasil dibuat/ada<br>";
} else {
    echo "❌ Error: " . mysqli_error($conn) . "<br>";
}

// Create supplier table
$query = "CREATE TABLE IF NOT EXISTS supplier (
    id_supplier INT AUTO_INCREMENT PRIMARY KEY,
    nama_supplier VARCHAR(100) NOT NULL
)";
if (mysqli_query($conn, $query)) {
    echo "✅ Tabel supplier berhasil dibuat/ada<br>";
} else {
    echo "❌ Error: " . mysqli_error($conn) . "<br>";
}

// Create menu table
$query = "CREATE TABLE IF NOT EXISTS menu (
    id_menu INT AUTO_INCREMENT PRIMARY KEY,
    nama_menu VARCHAR(100) NOT NULL,
    id_jenis INT,
    id_supplier INT,
    harga DECIMAL(10,2),
    stok INT,
    foto_menu VARCHAR(255)
)";
if (mysqli_query($conn, $query)) {
    echo "✅ Tabel menu berhasil dibuat/ada<br>";
} else {
    echo "❌ Error: " . mysqli_error($conn) . "<br>";
}

// Create users table
$query = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)";
if (mysqli_query($conn, $query)) {
    echo "✅ Tabel users berhasil dibuat/ada<br>";
} else {
    echo "❌ Error: " . mysqli_error($conn) . "<br>";
}

echo "<hr><h3>2. Menambahkan data Jenis Menu...</h3>";
$jenis_menu = ['Makanan', 'Minuman', 'Snack'];
foreach ($jenis_menu as $jenis) {
    $check = mysqli_query($conn, "SELECT * FROM jenis_menu WHERE nama_jenis='$jenis'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO jenis_menu (nama_jenis) VALUES ('$jenis')");
        echo "✅ $jenis ditambahkan<br>";
    } else {
        echo "ℹ️ $jenis sudah ada<br>";
    }
}

echo "<hr><h3>3. Menambahkan data Supplier...</h3>";
$suppliers = ['PT. Sumber Rasa', 'Depot Segar'];
foreach ($suppliers as $supplier) {
    $check = mysqli_query($conn, "SELECT * FROM supplier WHERE nama_supplier='$supplier'");
    if (mysqli_num_rows($check) == 0) {
        mysqli_query($conn, "INSERT INTO supplier (nama_supplier) VALUES ('$supplier')");
        echo "✅ $supplier ditambahkan<br>";
    } else {
        echo "ℹ️ $supplier sudah ada<br>";
    }
}

echo "<hr><h3>4. Menambahkan user admin...</h3>";
$check = mysqli_query($conn, "SELECT * FROM users WHERE username='admin'");
if (mysqli_num_rows($check) == 0) {
    mysqli_query($conn, "INSERT INTO users (username, password) VALUES ('admin', 'admin123')");
    echo "✅ User admin ditambahkan (password: admin123)<br>";
} else {
    echo "ℹ️ User admin sudah ada<br>";
}

echo "<hr><h3>5. Menambahkan sample menu...</h3>";
// Get IDs
$jenis_result = mysqli_query($conn, "SELECT id_jenis, nama_jenis FROM jenis_menu");
$jenis_data = [];
while ($row = mysqli_fetch_assoc($jenis_result)) {
    $jenis_data[$row['nama_jenis']] = $row['id_jenis'];
}

$supplier_result = mysqli_query($conn, "SELECT id_supplier, nama_supplier FROM supplier");
$supplier_data = [];
while ($row = mysqli_fetch_assoc($supplier_result)) {
    $supplier_data[$row['nama_supplier']] = $row['id_supplier'];
}

$menus = [
    ['Nasi Goreng Special', 'Makanan', 'PT. Sumber Rasa', 25000, 50],
    ['Es Teh Manis', 'Minuman', 'Depot Segar', 5000, 100],
    ['Kentang Goreng', 'Snack', 'PT. Sumber Rasa', 15000, 30],
];

foreach ($menus as $menu) {
    $nama_menu = mysqli_real_escape_string($conn, $menu[0]);
    $id_jenis = $jenis_data[$menu[1]];
    $id_supplier = $supplier_data[$menu[2]];
    $harga = $menu[3];
    $stok = $menu[4];
    $foto = date('YmdHis') . '_' . str_replace(' ', '_', $nama_menu) . '.jpg';
    
    $query = "INSERT INTO menu (nama_menu, id_jenis, id_supplier, harga, stok, foto_menu) 
              VALUES ('$nama_menu', '$id_jenis', '$id_supplier', '$harga', '$stok', '$foto')";
    
    if (mysqli_query($conn, $query)) {
        echo "✅ $nama_menu ditambahkan<br>";
    } else {
        echo "❌ Gagal: " . mysqli_error($conn) . "<br>";
    }
}

echo "<hr><h2>✅ Setup Selesai!</h2>";
echo "<p>Silakan login dengan:</p>";
echo "<ul>";
echo "<li>Username: <strong>admin</strong></li>";
echo "<li>Password: <strong>admin123</strong></li>";
echo "</ul>";
echo "<p><a href='index.php'>Klik di sini untuk melihat menu</a></p>";
?>
