<?php
session_start();
include "../../inc/koneksi.php";

/* CEK LOGIN ADMIN / PETUGAS */
if (!isset($_SESSION['ses_username'])) {
    header("Location: ../../login.php");
    exit;
}

$level = $_SESSION['ses_level'];
$nama = $_SESSION['ses_nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Buku | SI Perpustakaan</title>
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
            max-width: 1400px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Header */
        .header {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
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

        /* Top Bar */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            gap: 15px;
            flex-wrap: wrap;
        }

        .btn {
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .btn-dashboard {
            background: #6c757d;
            color: white;
        }

        .btn-dashboard:hover {
            background: #5a6268;
        }

        .btn-tambah {
            background: #3498db;
            color: white;
        }

        .btn-tambah:hover {
            background: #2980b9;
        }

        /* Search Box */
        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 15px 10px 40px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            width: 280px;
            transition: all 0.3s;
            outline: none;
        }

        .search-box input:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
        }

        .search-box::before {
            content: '🔍';
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
        }

        /* Card Container */
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Table Header Action */
        .table-header-action {
            padding: 20px;
            border-bottom: 2px solid #f0f0f0;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        .btn-tambah-large {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 15px;
            padding: 12px 24px;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
        }

        .btn-tambah-large:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        /* Table */
        .table-container {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        th {
            padding: 15px 12px;
            text-align: left;
            color: white;
            font-weight: 600;
            font-size: 14px;
            white-space: nowrap;
        }

        tbody tr {
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.3s ease;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        td {
            padding: 15px 12px;
            font-size: 14px;
            color: #2c3e50;
        }

        .cover-img {
            width: 60px;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s;
        }

        .cover-img:hover {
            transform: scale(1.5);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
            z-index: 10;
        }

        /* Status Badge */
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-tersedia {
            background: #d4edda;
            color: #155724;
        }

        .status-dipinjam {
            background: #fff3cd;
            color: #856404;
        }

        /* Action Buttons */
        .aksi {
            display: flex;
            gap: 8px;
        }

        .aksi a {
            text-decoration: none;
            font-size: 20px;
            transition: all 0.3s;
            display: inline-block;
        }

        .aksi a:hover {
            transform: scale(1.3);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #7f8c8d;
        }

        .empty-state-icon {
            font-size: 64px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content { margin-left: 0; padding: 10px; }
            .top-bar { flex-direction: column; align-items: stretch; }
            .search-box input { width: 100%; }
            th, td { padding: 10px 8px; font-size: 13px; }
            .cover-img { width: 50px; height: 65px; }
        }
    </style>
</head>
<body>

<?php include "../../inc/sidebar.php"; ?>

<div class="main-content" id="main">
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h2>📚 Data Buku Perpustakaan</h2>
            <p>Kelola koleksi buku perpustakaan dengan mudah</p>
        </div>

        <!-- Top Bar -->
        <div class="top-bar">
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="../../index.php" class="btn btn-dashboard">
                    ← Kembali
                </a>
            </div>

            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Cari buku..." onkeyup="searchTable()">
                </div>
                <a href="buku_tambah.php" class="btn btn-tambah">
                    ➕ Tambah Buku
                </a>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card">
            <!-- Tambah Buku Button di atas tabel -->
            <div class="table-header-action">
                <a href="buku_tambah.php" class="btn btn-tambah-large">
                    ➕ Tambah Buku Baru
                </a>
            </div>
            
            <div class="table-container">
                <table id="bukuTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th style="width: 80px;">Cover</th>
                            <th style="width: 120px;">Kode Buku</th>
                            <th>Judul Buku</th>
                            <th>Pengarang</th>
                            <th style="width: 120px;">Status</th>
                            <th style="width: 100px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($koneksi, "SELECT * FROM buku ORDER BY id_buku DESC");

                        if (mysqli_num_rows($query) == 0) {
                            echo '<tr><td colspan="7" class="empty-state">
                                    <div class="empty-state-icon">📭</div>
                                    <p>Belum ada data buku</p>
                                  </td></tr>';
                        }

                        while ($row = mysqli_fetch_assoc($query)) {
                            $status_class = $row['status'] == 'tersedia' ? 'status-tersedia' : 'status-dipinjam';
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td>
                                <?php if (!empty($row['cover'])) { ?>
                                    <img src="../../assets/cover/<?= $row['cover'] ?>" 
                                         class="cover-img" 
                                         alt="<?= htmlspecialchars($row['judul']) ?>">
                                <?php } else { ?>
                                    <div style="width: 60px; height: 80px; background: #e0e0e0; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #999;">📖</div>
                                <?php } ?>
                            </td>
                            <td><strong><?= htmlspecialchars($row['kode_buku']) ?></strong></td>
                            <td><?= htmlspecialchars($row['judul']) ?></td>
                            <td><?= htmlspecialchars($row['pengarang']) ?></td>
                            <td>
                                <span class="status-badge <?= $status_class ?>">
                                    <?= ucfirst($row['status']) ?>
                                </span>
                            </td>
                            <td>
                                <div class="aksi">
                                    <a href="buku_edit.php?id=<?= $row['id_buku'] ?>" title="Edit">✏️</a>
                                    <a href="buku_hapus.php?id=<?= $row['id_buku'] ?>" 
                                       onclick="return confirm('Yakin ingin menghapus buku ini?')"
                                       title="Hapus">🗑️</a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Toggle Sidebar (sesuaikan dengan index.php)
function toggleSidebar(){
    document.querySelector('.sidebar').classList.toggle('hide');
    document.getElementById('main').classList.toggle('full');
}

// Search Function
function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('bukuTable');
    const rows = table.getElementsByTagName('tr');

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        const cells = row.getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < cells.length; j++) {
            const cell = cells[j];
            if (cell) {
                const text = cell.textContent || cell.innerText;
                if (text.toLowerCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }

        row.style.display = found ? '' : 'none';
    }
}

// Smooth animation on load
window.addEventListener('load', () => {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.05}s`;
    });
});
</script>

</body>
</html>