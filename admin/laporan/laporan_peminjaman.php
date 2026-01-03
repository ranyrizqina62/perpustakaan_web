<?php
session_start();
if (!isset($_SESSION['ses_username'])) {
    header("Location: ../../login.php");
    exit;
}
include "../../inc/koneksi.php";

// Set timezone Indonesia
date_default_timezone_set('Asia/Jakarta');

// Set locale Indonesia untuk format tanggal
setlocale(LC_TIME, 'id_ID.utf8', 'id_ID', 'Indonesian');

$jenis = $_GET['jenis'] ?? 'semua';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Peminjaman Buku | Sistem Informasi Perpustakaan</title>
    <link rel="stylesheet" href="../../assets/css/sidebar.css">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Quicksand', 'Segoe UI', Tahoma, Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            background-attachment: fixed;
            min-height: 100vh;
            padding-top: 60px;
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
        }

        .page-header {
            background: white;
            padding: 30px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            text-align: center;
            border-bottom: 3px solid #667eea;
        }

        .page-header h2 {
            color: #2c3e50;
            font-size: 26px;
            margin-bottom: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .page-header p {
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 5px;
        }

        .filter-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-form {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .filter-form label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }

        select {
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            outline: none;
            cursor: pointer;
            min-width: 220px;
        }

        .btn {
            padding: 10px 20px;
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

        .btn-filter {
            background: #3498db;
            color: white;
        }

        .btn-filter:hover {
            background: #2980b9;
        }

        .btn-print {
            background: #27ae60;
            color: white;
        }

        .btn-print:hover {
            background: #229954;
        }

        .btn-back {
            background: #95a5a6;
            color: white;
        }

        .btn-back:hover {
            background: #7f8c8d;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-title {
            padding: 15px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 16px;
            font-weight: 600;
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
            text-align: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }

        tbody tr:hover {
            background: #f8f9fa;
        }

        td {
            padding: 12px;
            font-size: 14px;
            color: #2c3e50;
            text-align: center;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
        }

        .badge-dipinjam {
            background: #fff3cd;
            color: #856404;
        }

        .badge-dikembalikan {
            background: #d4edda;
            color: #155724;
        }

        .badge-terlambat {
            background: #f8d7da;
            color: #721c24;
        }

        .total-row {
            background: linear-gradient(135deg, #ffeaa7 0%, #fdcb6e 100%);
            font-weight: bold;
            font-size: 15px;
        }

        .total-row th {
            padding: 15px;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            padding: 20px;
            background: white;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .summary-item {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            background: #f8f9fa;
            border: 2px solid #e9ecef;
        }

        .summary-item h3 {
            font-size: 28px;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .summary-item p {
            color: #7f8c8d;
            font-size: 13px;
            font-weight: 500;
        }

        /* Print Header - Hanya untuk Print */
        .print-header {
            display: none;
        }

        /* Print Styles */
        @media print {
            /* Reset semua styling */
            * {
                margin: 0 !important;
                padding: 0 !important;
            }
            
            body {
                background: white !important;
                font-family: 'Quicksand', sans-serif;
            }
            
            /* Hide SEMUA yang tidak perlu */
            .sidebar,
            .page-header,
            .filter-card,
            .summary-grid,
            .card-title,
            .no-print,
            .btn,
            nav,
            header,
            footer,
            aside,
            button,
            select,
            form,
            .main-content > *:not(.container),
            body > *:not(.main-content) {
                display: none !important;
                visibility: hidden !important;
            }
            
            /* Show hanya yang diperlukan */
            .main-content,
            .container,
            .print-header,
            .card,
            table,
            thead,
            tbody,
            tfoot,
            tr,
            th,
            td {
                display: block !important;
                visibility: visible !important;
            }
            
            table {
                display: table !important;
            }
            
            thead, tbody, tfoot {
                display: table-row-group !important;
            }
            
            tr {
                display: table-row !important;
            }
            
            th, td {
                display: table-cell !important;
            }
            
            .main-content {
                margin: 0 !important;
                padding: 20px !important;
                width: 100% !important;
            }
            
            .container {
                max-width: 100% !important;
            }
            
            /* Show Print Header */
            .print-header {
                text-align: center;
                margin-top: 30px !important;
                margin-bottom: 20px !important;
            }
            
            .print-header h3 {
                font-size: 18px;
                font-weight: bold;
                margin-bottom: 5px !important;
                font-family: 'Quicksand', sans-serif;
            }
            
            .print-header h4 {
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 15px !important;
            }
            
            /* Card tanpa styling */
            .card {
                box-shadow: none !important;
                border-radius: 0 !important;
                margin: 0 !important;
                background: white !important;
                page-break-inside: avoid;
            }
            
            /* Table Styling untuk Print */
            table {
                width: 100%;
                border-collapse: collapse;
                border: 1px solid #ddd;
                margin: 0 !important;
            }
            
            thead {
                background-color: #f9f9f9 !important;
            }
            
            th {
                background-color: #f9f9f9 !important;
                color: #000 !important;
                border: 1px solid #ddd;
                padding: 8px !important;
                text-align: center;
                font-weight: bold;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            td {
                border: 1px solid #ddd;
                padding: 8px !important;
                color: #000;
            }
            
            tbody tr:nth-child(odd) {
                background-color: #f9f9f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            tbody tr:hover {
                background: transparent !important;
            }
            
            /* Badge untuk Print */
            .badge {
                background: none !important;
                color: #000 !important;
                padding: 0 !important;
                border: none !important;
                font-size: inherit !important;
            }
            
            /* Total Row */
            .total-row {
                background-color: #f0f0f0 !important;
                font-weight: bold;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            .total-row th {
                background-color: #f0f0f0 !important;
                border: 1px solid #ddd;
                padding: 8px !important;
                color: #000 !important;
            }
            
            /* Page Settings */
            @page {
                margin: 2cm;
                size: A4 landscape;
            }
        }

        @media (max-width: 768px) {
            .main-content { 
                margin-left: 0; 
                padding: 10px;
            }
            table {
                font-size: 12px;
            }
            th, td {
                padding: 8px 5px;
            }
        }
    </style>
</head>
<body>

<?php include "../../inc/sidebar.php"; ?>

<div class="main-content" id="main">
    <div class="container">
        
        <?php
        // Function untuk format tanggal Indonesia
        function tanggal_indo($tanggal) {
            $bulan = array(
                1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            );
            $pecahkan = explode('-', date('Y-m-d', strtotime($tanggal)));
            return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
        }

        function hari_indo($tanggal) {
            $hari = array(
                'Sunday' => 'Minggu', 'Monday' => 'Senin', 'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu'
            );
            return $hari[date('l', strtotime($tanggal))];
        }

        $tanggal_sekarang = date('Y-m-d');
        $waktu_sekarang = date('H:i');
        ?>
        
        <!-- Header Web View -->
        <div class="page-header">
            <h2>Laporan Data Peminjaman Buku</h2>
            <p style="margin-top: 8px; font-weight: 500;">Sistem Informasi Perpustakaan</p>
            <p style="font-size: 13px; margin-top: 8px;">
                <?= hari_indo($tanggal_sekarang) ?>, <?= tanggal_indo($tanggal_sekarang) ?> | Pukul <?= $waktu_sekarang ?> WIB
            </p>
        </div>

        <!-- Print Header - Only visible when printing -->
        <div class="print-header">
            <h3>.:: Laporan Perpustakaan ::.</h3>
            <h4>Laporan Data Peminjaman Buku
            <?php
            $judul_print = [
                'semua' => ' - Seluruh Data Peminjaman',
                'dipinjam' => ' - Status Sedang Dipinjam',
                'dikembalikan' => ' - Status Sudah Dikembalikan',
                'terlambat' => ' - Keterlambatan Pengembalian',
                'denda' => ' - Denda Keterlambatan'
            ];
            echo $judul_print[$jenis];
            ?>
            </h4>
        </div>

        <!-- Filter & Actions -->
        <div class="filter-card no-print">
            <form class="filter-form" method="get">
                <label>Kategori Laporan:</label>
                <select name="jenis" id="jenis">
                    <option value="semua" <?= $jenis == 'semua' ? 'selected' : '' ?>>Seluruh Data Peminjaman</option>
                    <option value="dipinjam" <?= $jenis == 'dipinjam' ? 'selected' : '' ?>>Status Sedang Dipinjam</option>
                    <option value="dikembalikan" <?= $jenis == 'dikembalikan' ? 'selected' : '' ?>>Status Sudah Dikembalikan</option>
                    <option value="terlambat" <?= $jenis == 'terlambat' ? 'selected' : '' ?>>Keterlambatan Pengembalian</option>
                    <option value="denda" <?= $jenis == 'denda' ? 'selected' : '' ?>>Denda Keterlambatan</option>
                </select>
                <button type="submit" class="btn btn-filter">Tampilkan Data</button>
            </form>
            
            <div style="display: flex; gap: 10px;">
                <button onclick="window.print()" class="btn btn-print">Cetak Laporan</button>
                <a href="/perpustakaan/index.php" class="btn btn-back">Kembali</a>
            </div>
        </div>

        <?php
        // Query berdasarkan jenis
        $sql = "SELECT p.*, a.nama as nama_anggota, b.judul, b.pengarang, b.kode_buku
                FROM peminjaman p
                LEFT JOIN anggota a ON p.id_anggota = a.id_anggota
                LEFT JOIN buku b ON p.id_buku = b.id_buku
                WHERE 1=1";

        if ($jenis == 'dipinjam') {
            $sql .= " AND (p.status = 'dipinjam' OR p.status = 'Dipinjam')";
        } elseif ($jenis == 'dikembalikan') {
            $sql .= " AND (p.status = 'dikembalikan' OR p.status = 'Dikembalikan' OR p.status = 'kembali' OR p.status = 'Kembali')";
        } elseif ($jenis == 'terlambat') {
            $sql .= " AND (p.status = 'dipinjam' OR p.status = 'Dipinjam') AND p.tanggal_kembali < CURDATE()";
        } elseif ($jenis == 'denda') {
            $sql .= " AND p.denda > 0";
        }

        $sql .= " ORDER BY p.tanggal_pinjam DESC";
        
        $query = mysqli_query($koneksi, $sql);
        $total = mysqli_num_rows($query);

        // Hitung total denda
        $total_denda = 0;
        $query_denda = mysqli_query($koneksi, str_replace("SELECT p.*, a.nama as nama_anggota, b.judul, b.pengarang, b.kode_buku", "SELECT SUM(p.denda) as total_denda", $sql));
        $row_denda = mysqli_fetch_assoc($query_denda);
        $total_denda = $row_denda['total_denda'] ?? 0;
        ?>

        <!-- Summary Statistics -->
        <div class="summary-grid">
            <div class="summary-item">
                <h3><?= $total ?></h3>
                <p>Total Data Peminjaman</p>
            </div>
            <div class="summary-item">
                <h3>Rp <?= number_format($total_denda, 0, ',', '.') ?></h3>
                <p>Total Denda Keterlambatan</p>
            </div>
        </div>

        <!-- Table -->
        <div class="card">
            <div class="card-title">
                Data Laporan: 
                <?php
                $judul = [
                    'semua' => 'Seluruh Data Peminjaman',
                    'dipinjam' => 'Status Sedang Dipinjam',
                    'dikembalikan' => 'Status Sudah Dikembalikan',
                    'terlambat' => 'Keterlambatan Pengembalian',
                    'denda' => 'Denda Keterlambatan'
                ];
                echo $judul[$jenis];
                ?>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th style="text-align: center;">No</th>
                        <th style="text-align: center;">Kode Buku</th>
                        <th style="text-align: center;">Judul Buku</th>
                        <th style="text-align: center;">Nama Peminjam</th>
                        <th style="text-align: center;">Tanggal Pinjam</th>
                        <th style="text-align: center;">Tanggal Kembali</th>
                        <th style="text-align: center;">Status</th>
                        <th style="text-align: center;">Denda (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($total == 0) {
                        echo '<tr><td colspan="8" style="padding: 40px; text-align: center; color: #999;">
                                <div style="font-size: 16px; font-weight: 500;">Tidak terdapat data untuk kategori yang dipilih</div>
                              </td></tr>';
                    } else {
                        $no = 1;
                        mysqli_data_seek($query, 0);
                        while ($data = mysqli_fetch_assoc($query)) {
                            $status_badge = '';
                            $status_lower = strtolower($data['status']);
                            
                            if ($status_lower == 'dikembalikan' || $status_lower == 'kembali') {
                                $status_badge = '<span class="badge badge-dikembalikan">Dikembalikan</span>';
                            } else {
                                $tgl_kembali = strtotime($data['tanggal_kembali']);
                                $tgl_sekarang = strtotime(date('Y-m-d'));
                                
                                if ($tgl_sekarang > $tgl_kembali) {
                                    $status_badge = '<span class="badge badge-terlambat">Terlambat</span>';
                                } else {
                                    $status_badge = '<span class="badge badge-dipinjam">Dipinjam</span>';
                                }
                            }
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $data['kode_buku'] ?></td>
                        <td style="text-align: left;"><?= $data['judul'] ?></td>
                        <td style="text-align: left;"><?= $data['nama_anggota'] ?></td>
                        <td><?= date('d/m/Y', strtotime($data['tanggal_pinjam'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($data['tanggal_kembali'])) ?></td>
                        <td><?= $status_badge ?></td>
                        <td style="text-align: right; font-weight: 600;">
                            <?= number_format($data['denda'], 0, ',', '.') ?>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <?php if ($total > 0) { ?>
                <tfoot>
                    <tr class="total-row">
                        <th colspan="8" style="text-align:right; padding-right:1.5cm;">
                            Total Denda: Rp. <?= number_format($total_denda, 0, ',', '.') ?>
                        </th>
                    </tr>
                </tfoot>
                <?php } ?>
            </table>
        </div>

    </div>
</div>

<script>
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('hide');
    document.getElementById('main').classList.toggle('full');
}
</script>

</body>
</html>