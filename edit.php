<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
include 'koneksi.php';

// Ambil data menu berdasarkan ID
$id = $_GET['id'];
$query = "SELECT * FROM menu WHERE id_menu = $id";
$result = mysqli_query($conn, $query);
$menu = mysqli_fetch_assoc($result);

if (!$menu) {
    header("Location: index.php");
    exit();
}

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
    
    // Upload file jika ada
    $foto_menu = $menu['foto_menu']; // Default pakai file lama
    
    if ($_FILES['foto_menu']['name']) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['foto_menu']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (!in_array($ext, $allowed)) {
            $errors[] = "Format file harus JPG/PNG";
        } else {
            // Hapus file lama
            if ($foto_menu && file_exists('img/' . $foto_menu)) {
                unlink('img/' . $foto_menu);
            }
            
            // Upload file baru
            $foto_menu = date('YmdHis') . '_' . $filename;
            $upload_path = 'img/' . $foto_menu;
            
            if (!move_uploaded_file($_FILES['foto_menu']['tmp_name'], $upload_path)) {
                $errors[] = "Gagal upload file";
            }
        }
    }
    
    if (empty($errors)) {
        $query = "UPDATE menu SET 
                  nama_menu = '$nama_menu',
                  id_jenis = '$id_jenis',
                  id_supplier = '$id_supplier',
                  harga = '$harga',
                  stok = '$stok',
                  foto_menu = '$foto_menu'
                  WHERE id_menu = $id";
        
        if (mysqli_query($conn, $query)) {
            header("Location: index.php");
            exit();
        } else {
            $errors[] = "Gagal update data: " . mysqli_error($conn);
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
    <title>Edit Menu</title>
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
            background: #f39c12;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }
        button:hover {
            background: #e67e22;
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
        .current-image {
            margin-top: 10px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .current-image img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            margin-top: 5px;
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
        <h2>Edit Menu</h2>
        
        <?php if (!empty($errors)): ?>
            <div class="error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo $error; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label>Nama Menu*</label>
                <input type="text" name="nama_menu" value="<?php echo htmlspecialchars($menu['nama_menu']); ?>" required>
            </div>

            <div class="form-group">
                <label>Jenis Menu*</label>
                <select name="id_jenis" required>
                    <option value="">Pilih Jenis</option>
                    <?php while ($row = mysqli_fetch_assoc($jenis)): ?>
                        <option value="<?php echo $row['id_jenis']; ?>" 
                            <?php echo ($row['id_jenis'] == $menu['id_jenis']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['nama_jenis']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Supplier*</label>
                <select name="id_supplier" required>
                    <option value="">Pilih Supplier</option>
                    <?php while ($row = mysqli_fetch_assoc($supplier)): ?>
                        <option value="<?php echo $row['id_supplier']; ?>"
                            <?php echo ($row['id_supplier'] == $menu['id_supplier']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($row['nama_supplier']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Harga* (Minimal 0)</label>
                <input type="number" name="harga" min="0" value="<?php echo $menu['harga']; ?>" required>
            </div>

            <div class="form-group">
                <label>Stok* (Minimal 0)</label>
                <input type="number" name="stok" min="0" value="<?php echo $menu['stok']; ?>" required>
            </div>

            <div class="form-group">
                <label>Foto Menu (JPG/PNG)</label>
                <input type="file" name="foto_menu" accept=".jpg,.jpeg,.png">
                <div class="current-image">
                    <p>Foto Saat Ini:</p>
                    <?php if ($menu['foto_menu'] && file_exists('img/' . $menu['foto_menu'])): ?>
                        <img src="img/<?php echo $menu['foto_menu']; ?>" alt="Current Image">
                    <?php else: ?>
                        <p>Tidak ada foto</p>
                    <?php endif; ?>
                </div>
            </div>

            <div>
                <button type="submit">Update</button>
                <a href="index.php" class="btn-batal">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>