<?php
include 'koneksi.php';

echo "<h1>Debug Data</h1><hr>";

// Check menu table
echo "<h3>Data di tabel menu:</h3>";
$result = mysqli_query($conn, "SELECT * FROM menu");
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row['id_menu'] . " | ";
        echo "Nama: " . $row['nama_menu'] . " | ";
        echo "ID Jenis: " . $row['id_jenis'] . " | ";
        echo "ID Supplier: " . $row['id_supplier'] . "<br>";
    }
} else {
    echo "❌ Tabel menu kosong!<br>";
}

echo "<hr><h3>Data di tabel jenis_menu:</h3>";
$result = mysqli_query($conn, "SELECT * FROM jenis_menu");
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row['id_jenis'] . " | Nama: " . $row['nama_jenis'] . "<br>";
    }
} else {
    echo "❌ Tabel jenis_menu kosong!<br>";
}

echo "<hr><h3>Data di tabel supplier:</h3>";
$result = mysqli_query($conn, "SELECT * FROM supplier");
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row['id_supplier'] . " | Nama: " . $row['nama_supplier'] . "<br>";
    }
} else {
    echo "❌ Tabel supplier kosong!<br>";
}

echo "<hr><h3>Query JOIN Test:</h3>";
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
} else {
    echo "❌ Tidak ada data! Error: " . mysqli_error($conn) . "<br>";
}

echo "<hr><h3>Fix: Update ID Jenis dan Supplier</h3>";
// Fix the data
$menu_result = mysqli_query($conn, "SELECT id_menu, nama_menu FROM menu");
while ($menu = mysqli_fetch_assoc($menu_result)) {
    $id_menu = $menu['id_menu'];
    $nama = strtolower($menu['nama_menu']);
    
    // Determine jenis
    if (strpos($nama, 'goreng') !== false || strpos($nama, 'nasi') !== false || strpos($nama, 'mie') !== false) {
        $id_jenis = 1; // Makanan
    } elseif (strpos($nama, 'es') !== false || strpos($nama, 'jus') !== false) {
        $id_jenis = 2; // Minuman
    } else {
        $id_jenis = 3; // Snack
    }
    
    // Determine supplier (alternating)
    $id_supplier = ($id_menu % 2 == 0) ? 1 : 2; // PT. Sumber Rasa : Depot Segar
    
    mysqli_query($conn, "UPDATE menu SET id_jenis='$id_jenis', id_supplier='$id_supplier' WHERE id_menu='$id_menu'");
    echo "✅ Updated: " . $menu['nama_menu'] . " | Jenis: $id_jenis | Supplier: $id_supplier<br>";
}

echo "<hr><h3>✅ Data sudah diperbaiki!</h3>";
echo "<a href='index.php'>Klik di sini untuk melihat menu</a>";
?>
