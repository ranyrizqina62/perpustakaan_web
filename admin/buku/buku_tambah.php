<?php
session_start();
if (!isset($_SESSION['ses_username'])) {
    header("Location: ../../login.php");
    exit;
}
include "../../inc/koneksi.php";

// PROSES SIMPAN
if (isset($_POST['simpan'])) {
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $pengarang = mysqli_real_escape_string($koneksi, $_POST['pengarang']);
    $tahun = mysqli_real_escape_string($koneksi, $_POST['tahun']);

    // Handle upload cover
    $cover = "";
    if ($_FILES['cover']['name'] != "") {
        $cover = $_FILES['cover']['name'];
        $tmp = $_FILES['cover']['tmp_name'];
        
        // Rename file dengan timestamp biar unik
        $ext = pathinfo($cover, PATHINFO_EXTENSION);
        $cover = time() . '_' . $kode . '.' . $ext;
        
        move_uploaded_file($tmp, "../../assets/cover/" . $cover);
    }

    // Insert ke database
    $query = mysqli_query($koneksi, "
        INSERT INTO buku (kode_buku, judul, pengarang, tahun_terbit, cover, status)
        VALUES ('$kode', '$judul', '$pengarang', '$tahun', '$cover', 'tersedia')
    ");

    if ($query) {
        echo "<script>
            alert('✅ Buku berhasil ditambahkan!');
            window.location='buku_data.php';
        </script>";
    } else {
        echo "<script>
            alert('❌ Gagal menambahkan buku!');
        </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="../../assets/css/sidebar.css">
    <style>
        body {
            font-family: 'Segoe UI', Arial;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            background-attachment: fixed;
            min-height: 100vh;
        }

        .main-content {
            margin-left: 230px;
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .main-content.full {
            margin-left: 0;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            outline: none;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        button,
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        button[type="submit"] {
            background: #3498db;
            color: white;
        }

        button[type="submit"]:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        @media (max-width: 768px) {
            .main-content { margin-left: 0; padding: 10px; }
        }
    </style>
</head>
<body>

<?php include "../../inc/sidebar.php"; ?>

<div class="main-content" id="main">
    <div class="container">
        <div class="card">
            <h2>➕ Tambah Buku Baru</h2>

            <form method="POST" enctype="multipart/form-data">
                <div class="form-group">
                    <label>📋 Kode Buku</label>
                    <input type="text" name="kode" placeholder="Contoh: BK001" required>
                </div>

                <div class="form-group">
                    <label>📖 Judul Buku</label>
                    <input type="text" name="judul" placeholder="Masukkan judul buku" required>
                </div>

                <div class="form-group">
                    <label>✍️ Pengarang</label>
                    <input type="text" name="pengarang" placeholder="Nama pengarang">
                </div>

                <div class="form-group">
                    <label>📅 Tahun Terbit</label>
                    <input type="number" name="tahun" placeholder="Contoh: 2024" min="1900" max="2100">
                </div>

                <div class="form-group">
                    <label>🖼️ Cover Buku (opsional)</label>
                    <input type="file" name="cover" accept="image/*">
                    <small style="color: #7f8c8d;">Format: JPG, PNG (Maks 2MB)</small>
                </div>

                <div class="btn-group">
                    <button type="submit" name="simpan">💾 Simpan</button>
                    <a href="buku_data.php" class="btn btn-cancel">✖️ Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleSidebar(){
    document.querySelector('.sidebar').classList.toggle('hide');
    document.getElementById('main').classList.toggle('full');
}
</script>

</body>
</html>