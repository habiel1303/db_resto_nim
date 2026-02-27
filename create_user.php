<?php
include 'koneksi.php';

echo "<h2>Menambahkan User Admin</h2>";

// Insert user admin
$username = 'admin';
$password = 'admin123';

$query = "INSERT INTO users (username, password) VALUES ('$username', '$password')";

if (mysqli_query($conn, $query)) {
    echo "✅ User BERHASIL dibuat!<br><br>";
    echo "Silakan login dengan:<br>";
    echo "Username: <strong>admin</strong><br>";
    echo "Password: <strong>admin123</strong>";
} else {
    echo "❌ Error: " . mysqli_error($conn);
}
?>
