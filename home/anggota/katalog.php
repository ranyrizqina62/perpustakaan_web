<?php
session_start();
include "../../inc/koneksi.php";

/* PROTEKSI LOGIN */
if (!isset($_SESSION['ses_username']) || $_SESSION['ses_level'] != 'Anggota') {
    header("Location: ../../login.php");
    exit;
}

$nama = $_SESSION['ses_nama'];
$id_user = $_SESSION['ses_id'];

/* SEARCH */
$cari = isset($_GET['q']) ? mysqli_real_escape_string($koneksi, $_GET['q']) : "";

/* PROSES PINJAM */
if (isset($_POST['pinjam'])) {
    $id_buku = (int)$_POST['id_buku'];

    // Cek apakah user sudah pinjam buku ini
    $cek_user = mysqli_query($koneksi, "
        SELECT id_peminjaman 
        FROM peminjaman 
        WHERE id_anggota='$id_user' AND id_buku='$id_buku' AND status='dipinjam'
    ");

    if (mysqli_num_rows($cek_user) > 0) {
        $pesan = "error_sudah";
    } else {
        // Cek apakah buku masih tersedia
        $cek = mysqli_query($koneksi, "
            SELECT id_peminjaman 
            FROM peminjaman 
            WHERE id_buku='$id_buku' AND status='dipinjam'
        ");

        if (mysqli_num_rows($cek) == 0) {
            $tgl_pinjam = date('Y-m-d');
            $tgl_kembali = date('Y-m-d', strtotime('+7 days'));

            mysqli_query($koneksi, "
                INSERT INTO peminjaman 
                (id_anggota, id_buku, tanggal_pinjam, tanggal_kembali, status, denda)
                VALUES 
                ('$id_user', '$id_buku', '$tgl_pinjam', '$tgl_kembali', 'dipinjam', 0)
            ");

            mysqli_query($koneksi, "
                UPDATE buku SET status='dipinjam' WHERE id_buku='$id_buku'
            ");

            header("Location: katalog.php?pinjam=sukses");
            exit;
        } else {
            $pesan = "error_dipinjam";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Buku | SI Perpustakaan</title>
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
            padding-top: 60px;
        }

        /* Override untuk sidebar header yang fixed */
        .sidebar {
            padding-top: 0 !important;
            top: 60px !important;
        }

        .main-content {
            margin-left: 230px;
            padding: 20px;
            transition: margin-left 0.3s;
            min-height: calc(100vh - 60px);
        }

        .main-content.full {
            margin-left: 0;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Page Header */
        .page-header {
            background: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            position: relative;
        }

        .page-header h2 {
            color: #2c3e50;
            font-size: 28px;
            margin-bottom: 5px;
        }

        .page-header p {
            color: #7f8c8d;
            font-size: 14px;
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideDown 0.4s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        /* Filter Section */
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .filter-row {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-box {
            position: relative;
            flex: 1;
            min-width: 300px;
        }

        .search-box input {
            width: 100%;
            padding: 14px 20px 14px 50px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            outline: none;
        }

        .search-box input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .search-box::before {
            content: '🔍';
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
        }

        .btn-search {
            padding: 14px 28px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
        }

        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }

        /* Sort & Filter */
        .sort-filter {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .sort-filter select {
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
            outline: none;
        }

        /* Book Grid */
        .buku-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }

        .buku-item {
            background: white;
            border-radius: 15px;
            padding: 0;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: all 0.4s ease;
            overflow: hidden;
            position: relative;
        }

        .buku-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }

        .buku-cover {
            position: relative;
            overflow: hidden;
            height: 320px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .buku-cover img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .buku-item:hover .buku-cover img {
            transform: scale(1.1);
        }

        .status-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .status-tersedia {
            background: rgba(40, 167, 69, 0.95);
            color: white;
        }

        .status-dipinjam {
            background: rgba(220, 53, 69, 0.95);
            color: white;
        }

        .buku-info {
            padding: 20px;
        }

        .buku-item h4 {
            color: #2c3e50;
            font-size: 17px;
            margin-bottom: 8px;
            min-height: 44px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            line-height: 1.3;
        }

        .buku-item .pengarang {
            color: #7f8c8d;
            font-size: 13px;
            margin-bottom: 12px;
            display: block;
        }

        .buku-item .kode {
            background: #f8f9fa;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            color: #6c757d;
            display: inline-block;
            margin-bottom: 15px;
        }

        .buku-item .btn-pinjam {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-pinjam-tersedia {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            box-shadow: 0 3px 8px rgba(40, 167, 69, 0.3);
        }

        .btn-pinjam-tersedia:hover {
            background: linear-gradient(135deg, #20c997 0%, #28a745 100%);
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
        }

        .btn-pinjam-disabled {
            background: #e0e0e0;
            color: #999;
            cursor: not-allowed;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            grid-column: 1 / -1;
        }

        .empty-state-icon {
            font-size: 80px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 24px;
        }

        .empty-state p {
            color: #7f8c8d;
            font-size: 16px;
        }

        /* Result Count */
        .result-count {
            color: white;
            font-size: 14px;
            margin-bottom: 15px;
            font-weight: 500;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body {
                padding-top: 60px;
            }
            
            .main-content { 
                margin-left: 0;
                padding: 10px; 
            }
            
            .buku-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                gap: 15px;
            }
            
            .filter-row {
                flex-direction: column;
            }
            
            .search-box {
                width: 100%;
            }
            
            .btn-search {
                width: 100%;
            }
            
            .buku-cover {
                height: 240px;
            }
        }
    </style>
</head>
<body>

<?php include "../../inc/sidebar.php"; ?>

<div class="main-content" id="main">
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h2>📚 Katalog Buku Perpustakaan</h2>
            <p>Temukan dan pinjam buku favorit Anda</p>
        </div>

        <!-- Alert Messages -->
        <?php if (isset($_GET['pinjam']) && $_GET['pinjam'] == 'sukses') { ?>
        <div class="alert alert-success">
            <span style="font-size: 24px;">✅</span>
            <div>
                <strong>Berhasil!</strong> Buku berhasil dipinjam. Jangan lupa kembalikan tepat waktu ya!
            </div>
        </div>
        <?php } ?>

        <?php if (isset($pesan) && $pesan == 'error_dipinjam') { ?>
        <div class="alert alert-error">
            <span style="font-size: 24px;">❌</span>
            <div>
                <strong>Gagal!</strong> Buku sedang dipinjam oleh anggota lain.
            </div>
        </div>
        <?php } ?>

        <?php if (isset($pesan) && $pesan == 'error_sudah') { ?>
        <div class="alert alert-error">
            <span style="font-size: 24px;">⚠️</span>
            <div>
                <strong>Perhatian!</strong> Anda sudah meminjam buku ini.
            </div>
        </div>
        <?php } ?>

        <!-- Filter Section -->
        <div class="filter-section">
            <form method="get" class="filter-row">
                <div class="search-box">
                    <input type="text" 
                           name="q" 
                           placeholder="Cari judul buku atau pengarang..." 
                           value="<?= htmlspecialchars($cari) ?>">
                </div>
                <button type="submit" class="btn-search">
                    🔍 Cari Buku
                </button>
            </form>
        </div>

        <?php
        $sql = "SELECT * FROM buku WHERE 1=1";
        
        if ($cari != "") {
            $sql .= " AND (judul LIKE '%$cari%' OR pengarang LIKE '%$cari%')";
        }
        
        $sql .= " ORDER BY id_buku DESC";
        
        $query = mysqli_query($koneksi, $sql);
        $total_hasil = mysqli_num_rows($query);
        ?>

        <!-- Result Count -->
        <?php if ($total_hasil > 0) { ?>
        <div class="result-count">
            📚 Menampilkan <?= $total_hasil ?> buku <?= $cari ? "untuk pencarian: <strong>\"" . htmlspecialchars($cari) . "\"</strong>" : "" ?>
        </div>
        <?php } ?>

        <!-- Book Grid -->
        <div class="buku-grid">
            <?php
            if ($total_hasil == 0) {
                echo '<div class="empty-state">
                        <div class="empty-state-icon">🔍</div>
                        <h3>Buku Tidak Ditemukan</h3>
                        <p>Coba kata kunci pencarian yang lain</p>
                      </div>';
            }

            while ($buku = mysqli_fetch_assoc($query)) {
                // Cek status real dari peminjaman
                $cek_pinjam = mysqli_query($koneksi, "
                    SELECT id_peminjaman 
                    FROM peminjaman 
                    WHERE id_buku='{$buku['id_buku']}' AND status='dipinjam'
                ");
                $sedang_dipinjam = mysqli_num_rows($cek_pinjam) > 0;
            ?>
            <div class="buku-item">
                <div class="buku-cover">
                    <?php if (!empty($buku['cover'])) { ?>
                        <img src="../../assets/cover/<?= $buku['cover'] ?>" alt="<?= htmlspecialchars($buku['judul']) ?>">
                    <?php } else { ?>
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; font-size: 64px;">
                            📖
                        </div>
                    <?php } ?>
                    <span class="status-badge <?= $sedang_dipinjam ? 'status-dipinjam' : 'status-tersedia' ?>">
                        <?= $sedang_dipinjam ? '🔴 Dipinjam' : '🟢 Tersedia' ?>
                    </span>
                </div>
                <div class="buku-info">
                    <h4><?= htmlspecialchars($buku['judul']) ?></h4>
                    <span class="pengarang">📝 <?= htmlspecialchars($buku['pengarang']) ?></span>
                    <span class="kode">🏷️ <?= htmlspecialchars($buku['kode_buku']) ?></span>
                    
                    <form method="post">
                        <input type="hidden" name="id_buku" value="<?= $buku['id_buku'] ?>">
                        <button type="submit" 
                                name="pinjam" 
                                class="btn-pinjam <?= $sedang_dipinjam ? 'btn-pinjam-disabled' : 'btn-pinjam-tersedia' ?>"
                                <?= $sedang_dipinjam ? 'disabled' : '' ?>
                                <?= !$sedang_dipinjam ? 'onclick="return confirm(\'Pinjam buku ini?\')"' : '' ?>>
                            <?= $sedang_dipinjam ? '🔒 Tidak Tersedia' : '📚 Pinjam Sekarang' ?>
                        </button>
                    </form>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
function toggleSidebar(){
    document.querySelector('.sidebar').classList.toggle('hide');
    document.getElementById('main').classList.toggle('full');
}

// Auto hide alert after 5 seconds
window.addEventListener('load', () => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-20px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});
</script>

</body>
</html>