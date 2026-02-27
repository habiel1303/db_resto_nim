<?php
include 'koneksi.php';

echo "<h3>Debug Info</h3>";

// Show all users in database
echo "<h4>Users di database:</h4>";
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo "ID: " . $row['id'] . "<br>";
        echo "Username: " . $row['username'] . "<br>";
        echo "Password: " . $row['password'] . "<br>";
        echo "<hr>";
    }
} else {
    echo "Error: " . mysqli_error($conn) . "<br>";
}

// Test login
if (isset($_GET['test'])) {
    $username = $_GET['username'];
    $password = $_GET['password'];
    
    echo "<h4>Test Login:</h4>";
    echo "Username input: $username<br>";
    echo "Password input: $password<br>";
    
    $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        echo "Rows found: " . mysqli_num_rows($result) . "<br>";
    } else {
        echo "Query error: " . mysqli_error($conn) . "<br>";
    }
}
?>
