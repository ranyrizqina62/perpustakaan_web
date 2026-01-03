<?php
session_start();
include "../../inc/koneksi.php";

if (!isset($_SESSION['ses_username']) || $_SESSION['ses_level'] != 'Anggota') {
    header("Location: ../../login.php");
    exit;
}

$nama = $_SESSION['ses_nama'];
$id = $_SESSION['ses_id'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peminjaman Saya | SI Perpustakaan</title>
    <link rel="stylesheet" href="../../assets/css/sidebar.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .header h2 {
            color: #2c3e50;
            font-size: 28px;
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

        .btn-katalog {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-katalog:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }

        /* Stats Cards */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
        }

        .stat-card .icon {
            font-size: 32px;
            margin-bottom: 10px;
        }

        .stat-card .number {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
        }

        .stat-card .label {
            font-size: 13px;
            color: #7f8c8d;
            margin-top: 5px;
        }

        /* Tabs */
        .tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .tab {
            padding: 12px 24px;
            background: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .tab:hover:not(.active) {
            background: #f8f9fa;
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

        .status-terlambat {
            background: #f8d7da;
            color: #721c24;
        }

        /* Denda Highlight */
        .denda {
            font-weight: bold;
            color: #dc3545;
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

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content { margin-left: 0; padding: 10px; }
            .top-bar { flex-direction: column; align-items: stretch; }
            th, td { padding: 10px 8px; font-size: 13px; }
            .tabs { flex-wrap: wrap; }
        }
    </style>
</head>
<body>

<?php include "../../inc/sidebar.php"; ?>

<div class="main-content" id="main">
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h2>📖 Peminjaman Saya</h2>
            <p>Kelola riwayat peminjaman buku Anda</p>
        </div>

        <!-- Top Bar -->
        <div class="top-bar">
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="../../index.php" class="btn btn-dashboard">
                    ← Dashboard
                </a>
                <a href="katalog.php" class="btn btn-katalog">
                    📚 Katalog Buku
                </a>
            </div>
        </div>

        <!-- Stats -->
        <?php
        $total_pinjam = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE id_anggota='$id'"))['total'];
        $sedang_dipinjam = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE id_anggota='$id' AND status='dipinjam'"))['total'];
        $sudah_kembali = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM peminjaman WHERE id_anggota='$id' AND status='kembali'"))['total'];
        $total_denda = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COALESCE(SUM(denda), 0) as total FROM peminjaman WHERE id_anggota='$id'"))['total'];
        ?>
        <div class="stats-row">
            <div class="stat-card">
                <div class="icon">📚</div>
                <div class="number"><?= $total_pinjam ?></div>
                <div class="label">Total Peminjaman</div>
            </div>
            <div class="stat-card">
                <div class="icon">📖</div>
                <div class="number"><?= $sedang_dipinjam ?></div>
                <div class="label">Sedang Dipinjam</div>
            </div>
            <div class="stat-card">
                <div class="icon">✅</div>
                <div class="number"><?= $sudah_kembali ?></div>
                <div class="label">Sudah Dikembalikan</div>
            </div>
            <div class="stat-card">
                <div class="icon">💰</div>
                <div class="number">Rp <?= number_format($total_denda, 0, ',', '.') ?></div>
                <div class="label">Total Denda</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab active" onclick="showTab('semua')">📚 Semua</button>
            <button class="tab" onclick="showTab('dipinjam')">📖 Sedang Dipinjam</button>
            <button class="tab" onclick="showTab('kembali')">✅ Sudah Kembali</button>
        </div>

        <!-- Tab Content: Semua -->
        <div id="semua" class="tab-content active">
            <div class="card">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Judul Buku</th>
                                <th style="width: 120px;">Tgl Pinjam</th>
                                <th style="width: 120px;">Tgl Kembali</th>
                                <th style="width: 120px;">Tgl Dikembalikan</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 120px;">Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($koneksi, "
                                SELECT peminjaman.*, buku.judul
                                FROM peminjaman
                                JOIN buku ON peminjaman.id_buku = buku.id_buku
                                WHERE peminjaman.id_anggota = '$id'
                                ORDER BY peminjaman.id_peminjaman DESC
                            ");

                            if (mysqli_num_rows($query) == 0) {
                                echo '<tr><td colspan="7" class="empty-state">
                                        <div class="empty-state-icon">📭</div>
                                        <p>Belum ada riwayat peminjaman</p>
                                      </td></tr>';
                            }

                            while ($row = mysqli_fetch_assoc($query)) {
                                $status_class = $row['status'] == 'dipinjam' ? 'status-dipinjam' : 'status-kembali';
                                
                                // Cek keterlambatan
                                if ($row['status'] == 'dipinjam' && strtotime(date('Y-m-d')) > strtotime($row['tanggal_kembali'])) {
                                    $status_class = 'status-terlambat';
                                }
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars($row['judul']) ?></strong></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal_kembali'])) ?></td>
                                <td><?= $row['tanggal_dikembalikan'] ? date('d/m/Y', strtotime($row['tanggal_dikembalikan'])) : '-' ?></td>
                                <td>
                                    <span class="status-badge <?= $status_class ?>">
                                        <?= strtoupper($row['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($row['denda'] > 0) { ?>
                                        <span class="denda">Rp <?= number_format($row['denda'], 0, ',', '.') ?></span>
                                    <?php } else { ?>
                                        -
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab Content: Sedang Dipinjam -->
        <div id="dipinjam" class="tab-content">
            <div class="card">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Judul Buku</th>
                                <th style="width: 120px;">Tgl Pinjam</th>
                                <th style="width: 120px;">Tgl Kembali</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 120px;">Sisa Hari</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($koneksi, "
                                SELECT peminjaman.*, buku.judul
                                FROM peminjaman
                                JOIN buku ON peminjaman.id_buku = buku.id_buku
                                WHERE peminjaman.id_anggota = '$id' AND peminjaman.status = 'dipinjam'
                                ORDER BY peminjaman.tanggal_kembali ASC
                            ");

                            if (mysqli_num_rows($query) == 0) {
                                echo '<tr><td colspan="6" class="empty-state">
                                        <div class="empty-state-icon">✅</div>
                                        <p>Tidak ada buku yang sedang dipinjam</p>
                                      </td></tr>';
                            }

                            while ($row = mysqli_fetch_assoc($query)) {
                                $today = strtotime(date('Y-m-d'));
                                $tgl_kembali = strtotime($row['tanggal_kembali']);
                                $selisih = ($tgl_kembali - $today) / 86400;
                                $sisa_hari = floor($selisih);
                                
                                $status_class = 'status-dipinjam';
                                if ($sisa_hari < 0) {
                                    $status_class = 'status-terlambat';
                                }
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars($row['judul']) ?></strong></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal_kembali'])) ?></td>
                                <td>
                                    <span class="status-badge <?= $status_class ?>">
                                        <?= $sisa_hari < 0 ? 'TERLAMBAT' : 'DIPINJAM' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($sisa_hari < 0) { ?>
                                        <span class="denda"><?= abs($sisa_hari) ?> hari terlambat</span>
                                    <?php } else { ?>
                                        <?= $sisa_hari ?> hari lagi
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab Content: Sudah Kembali -->
        <div id="kembali" class="tab-content">
            <div class="card">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Judul Buku</th>
                                <th style="width: 120px;">Tgl Pinjam</th>
                                <th style="width: 120px;">Tgl Kembali</th>
                                <th style="width: 120px;">Tgl Dikembalikan</th>
                                <th style="width: 120px;">Denda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $query = mysqli_query($koneksi, "
                                SELECT peminjaman.*, buku.judul
                                FROM peminjaman
                                JOIN buku ON peminjaman.id_buku = buku.id_buku
                                WHERE peminjaman.id_anggota = '$id' AND peminjaman.status = 'kembali'
                                ORDER BY peminjaman.tanggal_dikembalikan DESC
                            ");

                            if (mysqli_num_rows($query) == 0) {
                                echo '<tr><td colspan="6" class="empty-state">
                                        <div class="empty-state-icon">📚</div>
                                        <p>Belum ada buku yang dikembalikan</p>
                                      </td></tr>';
                            }

                            while ($row = mysqli_fetch_assoc($query)) {
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= htmlspecialchars($row['judul']) ?></strong></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal_pinjam'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal_kembali'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($row['tanggal_dikembalikan'])) ?></td>
                                <td>
                                    <?php if ($row['denda'] > 0) { ?>
                                        <span class="denda">Rp <?= number_format($row['denda'], 0, ',', '.') ?></span>
                                    <?php } else { ?>
                                        <span style="color: #28a745; font-weight: bold;">Tepat Waktu ✅</span>
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
</div>

<script>
function toggleSidebar(){
    document.querySelector('.sidebar').classList.toggle('hide');
    document.getElementById('main').classList.toggle('full');
}

function showTab(tabName) {
    // Hide all tabs
    const contents = document.querySelectorAll('.tab-content');
    contents.forEach(content => content.classList.remove('active'));
    
    // Remove active from all tab buttons
    const tabs = document.querySelectorAll('.tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    // Show selected tab
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}
</script>

</body>
</html>