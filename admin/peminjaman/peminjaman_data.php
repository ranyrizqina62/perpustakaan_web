<?php
session_start();
include "../../inc/koneksi.php";

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
    <title>Data Peminjaman | SI Perpustakaan</title>
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
            background: #28a745;
            color: white;
        }

        .btn-tambah:hover {
            background: #218838;
        }

        .btn-laporan {
            background: #17a2b8;
            color: white;
        }

        .btn-laporan:hover {
            background: #138496;
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
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-tambah-large {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            font-size: 15px;
            padding: 12px 24px;
            box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
        }

        .btn-tambah-large:hover {
            background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
            box-shadow: 0 6px 16px rgba(40, 167, 69, 0.4);
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

        /* Status Badge */
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .status-dipinjam {
            background: #fff3cd;
            color: #856404;
        }

        .status-kembali {
            background: #d4edda;
            color: #155724;
        }

        /* Action Buttons */
        .btn-kembali {
            background: #dc3545;
            color: white;
            padding: 6px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-kembali:hover {
            background: #c82333;
            transform: scale(1.05);
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
        }
    </style>
</head>
<body>

<?php include "../../inc/sidebar.php"; ?>

<div class="main-content" id="main">
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h2>📖 Data Peminjaman Buku</h2>
            <p>Kelola data peminjaman dan pengembalian buku</p>
        </div>

        <!-- Top Bar -->
        <div class="top-bar">
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="../../index.php" class="btn btn-dashboard">
                    ← Kembali
                </a>
                <a href="laporan_peminjaman.php" class="btn btn-laporan">
                    📊 Lihat Laporan
                </a>
            </div>

            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Cari peminjaman..." onkeyup="searchTable()">
                </div>
                <a href="peminjaman_tambah.php" class="btn btn-tambah">
                    ➕ Tambah Peminjaman
                </a>
            </div>
        </div>

        <!-- Table Card -->
        <div class="card">
            <!-- Tambah Peminjaman Button di atas tabel -->
            <div class="table-header-action">
                <a href="peminjaman_tambah.php" class="btn btn-tambah-large">
                    ➕ Tambah Peminjaman Baru
                </a>
            </div>
            
            <div class="table-container">
                <table id="peminjamanTable">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Anggota</th>
                            <th>Judul Buku</th>
                            <th style="width: 120px;">Tgl Pinjam</th>
                            <th style="width: 120px;">Tgl Kembali</th>
                            <th style="width: 100px;">Status</th>
                            <th style="width: 120px;">Denda</th>
                            <th style="width: 120px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($koneksi, "
                            SELECT p.*, a.nama, b.judul
                            FROM peminjaman p
                            JOIN anggota a ON p.id_anggota = a.id_anggota
                            JOIN buku b ON p.id_buku = b.id_buku
                            ORDER BY p.id_peminjaman DESC
                        ");

                        if (mysqli_num_rows($query) == 0) {
                            echo '<tr><td colspan="8" class="empty-state">
                                    <div class="empty-state-icon">📭</div>
                                    <p>Belum ada data peminjaman</p>
                                  </td></tr>';
                        }

                        while ($row = mysqli_fetch_assoc($query)) {
                            $status_class = $row['status'] == 'dipinjam' ? 'status-dipinjam' : 'status-kembali';
                        ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama']) ?></td>
                            <td><?= htmlspecialchars($row['judul']) ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])) ?></td>
                            <td><?= date('d/m/Y', strtotime($row['tanggal_kembali'])) ?></td>
                            <td>
                                <span class="status-badge <?= $status_class ?>">
                                    <?= strtoupper($row['status']) ?>
                                </span>
                            </td>
                            <td><strong>Rp <?= number_format($row['denda'], 0, ',', '.') ?></strong></td>
                            <td style="text-align: center;">
                                <?php if ($row['status'] == 'dipinjam') { ?>
                                    <a href="peminjaman_kembali.php?id=<?= $row['id_peminjaman'] ?>&buku=<?= $row['id_buku'] ?>" 
                                       class="btn-kembali"
                                       onclick="return confirm('Kembalikan buku ini?')">
                                       Kembalikan
                                    </a>
                                <?php } else { ?>
                                    <span style="color: #999;">-</span>
                                <?php } ?>
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
function toggleSidebar(){
    document.querySelector('.sidebar').classList.toggle('hide');
    document.getElementById('main').classList.toggle('full');
}

function searchTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase();
    const table = document.getElementById('peminjamanTable');
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

window.addEventListener('load', () => {
    const rows = document.querySelectorAll('tbody tr');
    rows.forEach((row, index) => {
        row.style.animationDelay = `${index * 0.05}s`;
    });
});
</script>

</body>
</html>
