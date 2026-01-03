<?php
$id   = $_SESSION['ses_id'];
$nama = $_SESSION['ses_nama'];

$q = mysqli_query($koneksi, "
    SELECT peminjaman.*, buku.judul, buku.cover
    FROM peminjaman
    JOIN buku ON peminjaman.id_buku = buku.id_buku
    WHERE peminjaman.id_anggota = $id
    AND peminjaman.status = 'pinjam'
");
$data = mysqli_fetch_assoc($q);

$sisa_hari = null;
$status_class = 'aman';
$status_icon = '✅';
$status_text = 'Status Aman';
$notifikasi = '';

if ($data) {
    $today = new DateTime();
    $kembali = new DateTime($data['tanggal_kembali']);
    $interval = $today->diff($kembali);
    $sisa_hari = $interval->days;
    
    // Cek apakah sudah lewat tanggal kembali
    if ($today > $kembali) {
        $status_class = 'terlambat';
        $status_icon = '🚨';
        $status_text = 'Ups, Terlambat Nih!';
        $notifikasi = '<div class="notif terlambat">⚠️ Wah, bukunya sudah lewat ' . $sisa_hari . ' hari! Buruan kembalikan ya biar nggak kena denda. Let\'s go! 🏃‍♀️</div>';
    } elseif ($sisa_hari <= 1) {
        $status_class = 'urgent';
        $status_icon = '⏰';
        $status_text = 'Waktunya Hampir Habis!';
        $notifikasi = '<div class="notif urgent">🔔 Hei! Besok sudah deadline nih. Jangan sampai lupa ya, set alarm kalau perlu! ⏰</div>';
    } elseif ($sisa_hari <= 3) {
        $status_class = 'warning';
        $status_icon = '⚠️';
        $status_text = 'Reminder Nih!';
        $notifikasi = '<div class="notif warning">📌 Tinggal ' . $sisa_hari . ' hari lagi ya! Tandain kalender biar nggak kelewat 📅✨</div>';
    } else {
        $status_class = 'pinjam';
        $status_icon = '📖';
        $status_text = 'Lagi Asik Baca Nih!';
    }
}

// Hitung total peminjaman
$total_pinjam = mysqli_num_rows(mysqli_query($koneksi, "
    SELECT id_peminjaman FROM peminjaman 
    WHERE id_anggota = $id
"));

// Hitung buku yang sudah dikembalikan
$total_kembali = mysqli_num_rows(mysqli_query($koneksi, "
    SELECT id_peminjaman FROM peminjaman 
    WHERE id_anggota = $id AND status = 'kembali'
"));
?>

<style>
.dashboard-anggota {
    max-width: 1200px;
    margin: 0 auto;
}

.welcome-anggota {
    background: linear-gradient(135deg, rgba(255,255,255,0.95), rgba(255,255,255,0.9));
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 25px;
    margin-bottom: 25px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    animation: fadeInUp 0.6s ease;
    border-left: 5px solid #2563eb;
}

.welcome-anggota h2 {
    margin: 0 0 10px;
    color: #1f2937;
    font-size: 28px;
}

.welcome-anggota p {
    margin: 0;
    color: #6b7280;
    font-size: 16px;
}

.notif {
    padding: 15px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-weight: 500;
    animation: slideDown 0.5s ease, pulse 2s infinite;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.notif.terlambat {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    border-left: 5px solid #dc2626;
    color: #991b1b;
}

.notif.urgent {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    border-left: 5px solid #f59e0b;
    color: #92400e;
}

.notif.warning {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
    border-left: 5px solid #3b82f6;
    color: #1e40af;
}

.stats-mini {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.stat-mini {
    background: linear-gradient(135deg, #6366f1, #8b5cf6);
    color: #fff;
    padding: 20px;
    border-radius: 16px;
    text-align: center;
    box-shadow: 0 8px 25px rgba(99, 102, 241, 0.4);
    animation: scaleIn 0.5s ease;
    transition: all 0.3s;
    position: relative;
    overflow: hidden;
}

.stat-mini::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    animation: slideRight 2s infinite;
}

.stat-mini:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 12px 35px rgba(99, 102, 241, 0.5);
}

.stat-mini b {
    display: block;
    font-size: 36px;
    margin-top: 10px;
}

.buku-info {
    display: flex;
    gap: 20px;
    align-items: center;
    margin-top: 15px;
    padding: 20px;
    background: rgba(255,255,255,0.8);
    border-radius: 16px;
    animation: fadeInUp 0.6s ease;
    transition: all 0.3s;
}

.buku-info:hover {
    background: rgba(255,255,255,1);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.buku-cover {
    width: 110px;
    height: 150px;
    object-fit: cover;
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    transition: all 0.4s;
}

.buku-cover:hover {
    transform: scale(1.05) rotate(2deg);
    box-shadow: 0 12px 30px rgba(0,0,0,0.3);
}

.buku-detail {
    flex: 1;
}

.buku-detail h4 {
    margin: 0 0 12px;
    font-size: 20px;
    color: #1f2937;
}

.info-row {
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 10px 0;
    color: #4b5563;
}

.sisa-hari {
    display: inline-block;
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: bold;
    font-size: 14px;
    animation: bounce 2s infinite;
}

.sisa-hari.normal {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
    color: #065f46;
}

.sisa-hari.warning {
    background: linear-gradient(135deg, #fef3c7, #fde68a);
    color: #92400e;
}

.sisa-hari.urgent {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #991b1b;
    animation: pulse 1s infinite;
}

.menu-cepat {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.menu-item {
    display: block;
    padding: 20px;
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
    border-radius: 16px;
    text-decoration: none;
    color: #1f2937;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border-left: 4px solid #2563eb;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
}

.menu-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.1), transparent);
    transition: left 0.5s;
}

.menu-item:hover::before {
    left: 100%;
}

.menu-item:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 12px 30px rgba(37, 99, 235, 0.3);
    background: linear-gradient(135deg, #e5e7eb, #d1d5db);
}

.menu-item strong {
    display: block;
    font-size: 18px;
    margin-bottom: 8px;
    color: #1f2937;
}

.menu-item small {
    color: #6b7280;
}
</style>

<div class="dashboard-anggota">

<!-- WELCOME CARD -->
<div class="welcome-anggota">
    <h2>👋 Hai <?= $nama ?>, Selamat Datang Kembali!</h2>
    <p>Yuk cek koleksi buku terbaru dan lanjut petualangan bacamu! 🚀📚</p>
</div>

<!-- Notifikasi -->
<?php echo $notifikasi; ?>

<!-- Stats Mini -->
<div class="stats-mini">
    <div class="stat-mini">
        📚 Total Peminjaman
        <b><?= $total_pinjam ?></b>
    </div>
    <div class="stat-mini">
        ✅ Sudah Dikembalikan
        <b><?= $total_kembali ?></b>
    </div>
</div>

<!-- Status Peminjaman -->
<?php if ($data) { 
    $cover = $data['cover'] ?: 'default.jpg';
    $sisa_class = $sisa_hari <= 1 ? 'urgent' : ($sisa_hari <= 3 ? 'warning' : 'normal');
?>
<div class="card <?= $status_class ?>">
    <h3><?= $status_icon ?> <?= $status_text ?></h3>
    
    <div class="buku-info">
        <img src="assets/cover/<?= $cover ?>" alt="Cover" class="buku-cover">
        
        <div class="buku-detail">
            <h4>📖 <?= $data['judul'] ?></h4>
            
            <div class="info-row">
                📅 Mulai baca: <b><?= date('d M Y', strtotime($data['tanggal_pinjam'])) ?></b>
            </div>
            
            <div class="info-row">
                ⏰ Deadline: <b><?= date('d M Y', strtotime($data['tanggal_kembali'])) ?></b>
            </div>
            
            <div class="info-row">
                🔔 Sisa waktu: 
                <span class="sisa-hari <?= $sisa_class ?>">
                    <?= $sisa_hari ?> hari lagi
                </span>
            </div>
        </div>
    </div>
</div>
<?php } else { ?>
<div class="card aman">
    <h3>✅ All Clear! Kamu Lagi Bebas Nih!</h3>
    <p style="margin: 10px 0 0; color: #065f46;">
        Nggak ada buku yang lagi dipinjam sekarang. Waktunya berburu buku baru! Yuk jelajahi katalog dan temukan bacaan seru berikutnya! 🎯✨
    </p>
</div>
<?php } ?>

<!-- Menu Cepat -->
<div class="card">
    <h3>🚀 Mau Ngapain Hari Ini?</h3>
    <div class="menu-cepat">
        <a href="/perpustakaan/home/anggota/katalog.php" class="menu-item">
            <strong>📚 Eksplor Katalog Buku</strong>
            <small>Temukan buku seru yang belum kamu baca!</small>
        </a>
        <a href="/perpustakaan/home/anggota/peminjaman_saya.php" class="menu-item">
            <strong>📖 Lihat Riwayat</strong>
            <small>Cek semua petualangan bacamu di sini</small>
        </a>
    </div>
</div>

</div>