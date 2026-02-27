<?php
echo "<h2>Debug Database</h2>";

// Test connection
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'db_resto_nim';

echo "Testing koneksi...<br>";
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi GAGAL: " . mysqli_connect_error());
}
echo "Koneksi BERHASIL<br><br>";

// List all tables
echo "Tabel di database db_resto_nim:<br>";
$tables = mysqli_query($conn, "SHOW TABLES");
while ($row = mysqli_fetch_array($tables)) {
    echo "- " . $row[0] . "<br>";
}
echo "<br>";

// Check if users table exists
$tableCheck = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
if (mysqli_num_rows($tableCheck) > 0) {
    echo "Tabel users ADA<br><br>";
    
    // Show structure
    echo "Struktur tabel users:<br>";
    $structure = mysqli_query($conn, "DESCRIBE users");
    while ($row = mysqli_fetch_assoc($structure)) {
        echo "- " . $row['Field'] . " (" . $row['Type'] . ")<br>";
    }
    echo "<br>";
    
    // Show all users
    echo "Data di tabel users:<br>";
    $users = mysqli_query($conn, "SELECT * FROM users");
    if (mysqli_num_rows($users) > 0) {
        while ($row = mysqli_fetch_assoc($users)) {
            echo "Username: '" . $row['username'] . "' | Password: '" . $row['password'] . "'<br>";
        }
    } else {
        echo "Tabel users KOSONG - tidak ada data<br>";
    }
} else {
    echo "Tabel users TIDAK ADA - perlu dibuat<br>";
}
?>
