<?php
include 'koneksi.php';

// Create users table if not exists
$query = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
)";

if (mysqli_query($conn, $query)) {
    echo "Tabel users berhasil dibuat/ada<br>";
} else {
    echo "Error membuat tabel: " . mysqli_error($conn) . "<br>";
}

// Check if admin user exists
$check = "SELECT * FROM users WHERE username='admin'";
$result = mysqli_query($conn, $check);

if (mysqli_num_rows($result) == 0) {
    // Insert default user
    $insert = "INSERT INTO users (username, password) VALUES ('admin', 'admin123')";
    if (mysqli_query($conn, $insert)) {
        echo "User admin berhasil dibuat!<br>";
        echo "Username: admin<br>";
        echo "Password: admin123";
    } else {
        echo "Error insert user: " . mysqli_error($conn);
    }
} else {
    echo "User admin sudah ada di database";
}
?>
