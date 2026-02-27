<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food & Beverage Management System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: #f5f5f5;
        }
        .navbar {
            background: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 5px 10px;
            border-radius: 3px;
            transition: background 0.3s;
        }
        .nav-links a:hover {
            background: #34495e;
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .btn-tambah {
            background: #27ae60;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .btn-tambah:hover {
            background: #2ecc71;
        }
        table {
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        th {
            background: #34495e;
            color: white;
            padding: 12px;
            text-align: left;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        tr:hover {
            background: #f5f6fa;
        }
        .thumbnail {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 5px;
        }
        .btn-edit {
            background: #f39c12;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
            margin-right: 5px;
        }
        .btn-hapus {
            background: #e74c3c;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 3px;
        }
        .btn-edit:hover {
            background: #e67e22;
        }
        .btn-hapus:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Food & Beverage Management</h2>
        <div class="nav-links">
            <span>Selamat datang, <?php echo $_SESSION['user']; ?></span>
            <a href="logout.php">Logout</a>
        </div>
    </div>

    <div class="container">
        <div class="header">
            <h1>Daftar Menu</h1>
            <a href="tambah.php" class="btn-tambah">+ Tambah Menu</a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Menu</th>
                    <th>Jenis</th>
                    <th>Supplier</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Query dengan LEFT JOIN dan fallback ke nama_jenis/id jika JOIN gagal
                $query = "SELECT m.*, 
                          COALESCE(j.nama_jenis, 'Tidak ada') as nama_jenis, 
                          COALESCE(s.nama_supplier, 'Tidak ada') as nama_supplier
                          FROM menu m 
                          LEFT JOIN jenis_menu j ON m.id_jenis = j.id_jenis 
                          LEFT JOIN supplier s ON m.id_supplier = s.id_supplier 
                          ORDER BY m.id_menu DESC";
                $result = mysqli_query($conn, $query);
                
                if ($result && mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['id_menu'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_menu']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_jenis']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['nama_supplier']) . "</td>";
                        echo "<td>Rp " . number_format($row['harga']) . "</td>";
                        echo "<td>" . $row['stok'] . "</td>";
                        echo "<td>";
                        if (!empty($row['foto_menu']) && file_exists("img/" . $row['foto_menu'])) {
                            echo "<img src='img/" . $row['foto_menu'] . "' class='thumbnail'>";
                        } else {
                            echo "<img src='img/no-image.png' class='thumbnail'>";
                        }
                        echo "</td>";
                        echo "<td>
                                <a href='edit.php?id=" . $row['id_menu'] . "' class='btn-edit'>Edit</a>
                                <a href='hapus.php?id=" . $row['id_menu'] . "' class='btn-hapus' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8' style='text-align: center; padding: 20px;'>Belum ada data menu</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>