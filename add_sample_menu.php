<?php
include 'koneksi.php';

echo "<h2>Menambahkan Sample Menu</h2><br>";

// Check if tables exist and have data
$check_jenis = mysqli_query($conn, "SELECT * FROM jenis_menu");
$check_supplier = mysqli_query($conn, "SELECT * FROM supplier");

if (mysqli_num_rows($check_jenis) == 0) {
    echo "❌ Tabel jenis_menu kosong. Jalankan add_data.php terlebih dahulu!<br>";
    echo "<a href='add_data.php'>Klik di sini untuk menambah data</a>";
    exit();
}

if (mysqli_num_rows($check_supplier) == 0) {
    echo "❌ Tabel supplier kosong. Jalankan add_data.php terlebih dahulu!<br>";
    echo "<a href='add_data.php'>Klik di sini untuk menambah data</a>";
    exit();
}

// Get id_jenis
$jenis_result = mysqli_query($conn, "SELECT id_jenis, nama_jenis FROM jenis_menu");
$jenis_data = [];
while ($row = mysqli_fetch_assoc($jenis_result)) {
    $jenis_data[$row['nama_jenis']] = $row['id_jenis'];
}

// Get id_supplier
$supplier_result = mysqli_query($conn, "SELECT id_supplier, nama_supplier FROM supplier");
$supplier_data = [];
while ($row = mysqli_fetch_assoc($supplier_result)) {
    $supplier_data[$row['nama_supplier']] = $row['id_supplier'];
}

echo "Jenis yang tersedia: " . implode(", ", array_keys($jenis_data)) . "<br>";
echo "Supplier yang tersedia: " . implode(", ", array_keys($supplier_data)) . "<br><br>";

// Sample menus
$menus = [
    ['Nasi Goreng Special', 'Makanan', 'PT. Sumber Rasa', 25000, 50, 'nasi_goreng.jpg'],
    ['Es Teh Manis', 'Minuman', 'Depot Segar', 5000, 100, 'es_teh.jpg'],
    ['Kentang Goreng', 'Snack', 'PT. Sumber Rasa', 15000, 30, 'kentang.jpg'],
];

foreach ($menus as $menu) {
    $nama_menu = $menu[0];
    $jenis = $menu[1];
    $supplier = $menu[2];
    $harga = $menu[3];
    $stok = $menu[4];
    $foto = $menu[5];
    
    $id_jenis = isset($jenis_data[$jenis]) ? $jenis_data[$jenis] : 0;
    $id_supplier = isset($supplier_data[$supplier]) ? $supplier_data[$supplier] : 0;
    
    if ($id_jenis > 0 && $id_supplier > 0) {
        $query = "INSERT INTO menu (nama_menu, id_jenis, id_supplier, harga, stok, foto_menu) 
                  VALUES ('$nama_menu', '$id_jenis', '$id_supplier', '$harga', '$stok', '$foto')";
        
        if (mysqli_query($conn, $query)) {
            echo "✅ $nama_menu berhasil ditambahkan<br>";
        } else {
            echo "❌ Gagal menambahkan $nama_menu: " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "❌ Gagal menambahkan $nama_menu: Jenis atau Supplier tidak ditemukan<br>";
    }
}

echo "<br><h3>Selesai!</h3>";
echo "<a href='index.php'>Klik di sini untuk melihat menu</a>";
?>
