<?php
session_start();
include "../../inc/koneksi.php";

if (!isset($_SESSION['ses_username'])) {
    header("Location: ../../login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);
    
    mysqli_query($koneksi, "
        INSERT INTO anggota(nama, username, password, id_level)
        VALUES('$nama', '$username', '$password', '$level')
    ");
    
    header("Location: anggota_data.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Anggota | SI Perpustakaan</title>
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
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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

        label .required {
            color: #dc3545;
            margin-left: 3px;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s;
            outline: none;
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        select {
            cursor: pointer;
            background: white;
        }

        .form-hint {
            font-size: 12px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            flex-wrap: wrap;
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
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }

        .btn-simpan:hover {
            background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
        }

        .btn-kembali {
            background: #6c757d;
            color: white;
        }

        .btn-kembali:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        /* Icon Input */
        .input-with-icon {
            position: relative;
        }

        .input-with-icon input {
            padding-left: 40px;
        }

        .input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
        }

        @media (max-width: 768px) {
            .main-content { margin-left: 0; padding: 10px; }
            .form-card { padding: 20px; }
            .btn-group { flex-direction: column; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

<?php include "../../inc/sidebar.php"; ?>

<div class="main-content" id="main">
    <div class="container">
        <div class="header">
            <h2>➕ Tambah Anggota Baru</h2>
            <p>Isi form di bawah untuk menambah anggota perpustakaan</p>
        </div>

        <div class="form-card">
            <form method="post" onsubmit="return validateForm()">
                <div class="form-group">
                    <label>
                        Nama Lengkap <span class="required">*</span>
                    </label>
                    <div class="input-with-icon">
                        <span class="input-icon">👤</span>
                        <input type="text" 
                               name="nama" 
                               id="nama"
                               placeholder="Masukkan nama lengkap" 
                               required>
                    </div>
                    <div class="form-hint">Nama lengkap anggota</div>
                </div>

                <div class="form-group">
                    <label>
                        Username <span class="required">*</span>
                    </label>
                    <div class="input-with-icon">
                        <span class="input-icon">📧</span>
                        <input type="text" 
                               name="username" 
                               id="username"
                               placeholder="Masukkan username" 
                               required>
                    </div>
                    <div class="form-hint">Username untuk login (tanpa spasi)</div>
                </div>

                <div class="form-group">
                    <label>
                        Password <span class="required">*</span>
                    </label>
                    <div class="input-with-icon">
                        <span class="input-icon">🔒</span>
                        <input type="password" 
                               name="password" 
                               id="password"
                               placeholder="Masukkan password" 
                               required>
                    </div>
                    <div class="form-hint">Password minimal 6 karakter</div>
                </div>

                <div class="form-group">
                    <label>
                        Level Akses <span class="required">*</span>
                    </label>
                    <select name="level" id="level" required>
                        <option value="">-- Pilih Level --</option>
                        <?php
                        $query_level = mysqli_query($koneksi, "SELECT * FROM level_pengguna ORDER BY id_level");
                        while ($level = mysqli_fetch_assoc($query_level)) {
                            echo "<option value='{$level['id_level']}'>{$level['nama_level']}</option>";
                        }
                        ?>
                    </select>
                    <div class="form-hint">Tentukan hak akses pengguna</div>
                </div>

                <div class="btn-group">
                    <button type="submit" name="simpan" class="btn btn-simpan">
                        💾 Simpan Data
                    </button>
                    <a href="anggota_data.php" class="btn btn-kembali">
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

function validateForm() {
    const nama = document.getElementById('nama').value.trim();
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;
    const level = document.getElementById('level').value;

    if (nama === '') {
        alert('Nama lengkap harus diisi!');
        return false;
    }

    if (username === '') {
        alert('Username harus diisi!');
        return false;
    }

    if (username.includes(' ')) {
        alert('Username tidak boleh mengandung spasi!');
        return false;
    }

    if (password.length < 6) {
        alert('Password minimal 6 karakter!');
        return false;
    }

    if (level === '') {
        alert('Pilih level akses!');
        return false;
    }

    return true;
}

// Auto focus ke input pertama
window.addEventListener('load', () => {
    document.getElementById('nama').focus();
});
</script>

</body>
</html>