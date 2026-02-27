<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_menu = mysqli_real_escape_string($conn, $_POST['nama_menu']);
    $id_jenis = $_POST['id_jenis'];
    $id_supplier = $_POST['id_supplier'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    
    // Validasi
    $errors = [];
    
    if (empty($nama_menu)) {
        $errors[] = "Nama menu tidak boleh kosong";
    }
    
    if (!is_numeric($harga) || $harga < 0) {
        $errors[] = "Harga harus berupa angka dan minimal 0";
    }
    
    if (!is_numeric($stok) || $stok < 0) {
        $errors[] = "Stok harus berupa angka dan minimal 0";
    }
    
    // Upload file
    $foto_menu = '';
    if ($_FILES['foto_menu']['name']) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['foto_menu']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            $errors[] = "Format file harus JPG/PNG";
        } else {
            // Buat nama file unik
            $foto_menu = date('YmdHis') . '_' . $filename;
            $upload_path = 'img/' . $foto_menu;
            
            if (!move_uploaded_file($_FILES['foto_menu']['tmp_name'], $upload_path)) {
                $errors[] = "Gagal upload file";
            }
        }
    } else {
        $errors[] = "Foto menu wajib diupload";
    }
    
    if (empty($errors)) {
        $query = "INSERT INTO menu (nama_menu, id_jenis, id_supplier, harga, stok, foto_menu) 
                  VALUES ('$nama_menu', '$id_jenis', '$id_supplier', '$harga', '$stok', '$foto_menu')";
        
        if (mysqli_query($conn, $query)) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Gagal menyimpan data: " . mysqli_error($conn);
        }
    }
}

// Ambil data untuk dropdown
$jenis = mysqli_query($conn, "SELECT * FROM jenis_menu");
$supplier = mysqli_query($conn, "SELECT * FROM supplier");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Menu</title>
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
            background: #1f93ff;
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
        }
        .container {
            max-width: 600px;
            margin: 30px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 30px;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background: #27ae60;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        button:hover {
            background: #2ecc71;
        }
        .btn-batal {
            background: #95a5a6;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }
        .btn-batal:hover {
            background: #7f8c8d;
        }
        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
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
        <h2>Tambah Menu Baru</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Menu</label>
                <input type="text" name="nama_menu" required>
            </div>

            <div class="form-group">
                <label>Jenis Menu</label>
                <select name="id_jenis" required>
                    <option value="">Pilih Jenis</option>
                    <?php while ($row = mysqli_fetch_assoc($jenis)): ?>
                        <option value="<?php echo $row['id_jenis']; ?>">
                            <?php echo htmlspecialchars($row['nama_jenis']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Supplier</label>
                <select name="id_supplier" required>
                    <option value="">Pilih Supplier</option>
                    <?php while ($row = mysqli_fetch_assoc($supplier)): ?>
                        <option value="<?php echo $row['id_supplier']; ?>">
                            <?php echo htmlspecialchars($row['nama_supplier']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Harga (Minimal 0)</label>
                <input type="number" name="harga" min="0" required>
            </div>

            <div class="form-group">
                <label>Stok (Minimal 0)</label>
                <input type="number" name="stok" min="0" required>
            </div>

            <div class="form-group">
                <label>Foto Menu (JPG/PNG)</label>
                <input type="file" name="foto_menu" accept=".jpg,.jpeg,.png" required>
            </div>

            <div>
                <button type="submit">Simpan</button>
                <a href="index.php" class="btn-batal">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>