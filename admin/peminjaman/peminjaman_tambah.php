<?php
session_start();
include "../../inc/koneksi.php";

if (!isset($_SESSION['ses_username'])) {
    header("Location: ../../login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $anggota = mysqli_real_escape_string($koneksi, $_POST['anggota']);
    $buku = mysqli_real_escape_string($koneksi, $_POST['buku']);
    $tgl_pinjam = mysqli_real_escape_string($koneksi, $_POST['tgl_pinjam']);
    $tgl_kembali = mysqli_real_escape_string($koneksi, $_POST['tgl_kembali']);
    
    mysqli_query($koneksi, "
        INSERT INTO peminjaman
        (id_anggota, id_buku, tanggal_pinjam, tanggal_kembali, status, denda)
        VALUES
        ('$anggota', '$buku', '$tgl_pinjam', '$tgl_kembali', 'dipinjam', 0)
    ");

    mysqli_query($koneksi, "
        UPDATE buku SET status='dipinjam'
        WHERE id_buku='$buku'
    ");

    header("Location: peminjaman_data.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Peminjaman | SI Perpustakaan</title>
    <link rel="stylesheet" href="../../assets/css/sidebar.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
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
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .header h2 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .form-card {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #2c3e50;
            font-weight: 600;
            font-size: 14px;
        }

        select, input[type="date"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            outline: none;
        }

        select:focus, input[type="date"]:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-simpan {
            background: #28a745;
            color: white;
        }

        .btn-simpan:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .btn-kembali {
            background: #6c757d;
            color: white;
        }

        .btn-kembali:hover {
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
        <div class="header">
            <h2>➕ Tambah Peminjaman Baru</h2>
            <p>Isi form di bawah untuk menambah data peminjaman</p>
        </div>

        <div class="form-card">
            <form method="post">
                <div class="form-group">
                    <label>Anggota</label>
                    <select name="anggota" required>
                        <option value="">-- Pilih Anggota --</option>
                        <?php
                        $query_anggota = mysqli_query($koneksi, "SELECT * FROM anggota ORDER BY nama");
                        while ($anggota = mysqli_fetch_assoc($query_anggota)) {
                            echo "<option value='{$anggota['id_anggota']}'>{$anggota['nama']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Buku (Tersedia)</label>
                    <select name="buku" required>
                        <option value="">-- Pilih Buku --</option>
                        <?php
                        $query_buku = mysqli_query($koneksi, "SELECT * FROM buku WHERE status='tersedia' ORDER BY judul");
                        while ($buku = mysqli_fetch_assoc($query_buku)) {
                            echo "<option value='{$buku['id_buku']}'>{$buku['judul']} - {$buku['pengarang']}</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Tanggal Pinjam</label>
                    <input type="date" name="tgl_pinjam" value="<?= date('Y-m-d') ?>" required>
                </div>

                <div class="form-group">
                    <label>Tanggal Kembali</label>
                    <input type="date" name="tgl_kembali" value="<?= date('Y-m-d', strtotime('+7 days')) ?>" required>
                </div>

                <div class="btn-group">
                    <button type="submit" name="simpan" class="btn btn-simpan">
                        💾 Simpan
                    </button>
                    <a href="peminjaman_data.php" class="btn btn-kembali">
                        ← Kembali
                    </a>
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