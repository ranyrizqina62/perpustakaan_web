<?php
// Tidak perlu session_start() lagi karena sudah di index.php
$level = $_SESSION['ses_level'] ?? '';
$nama = $_SESSION['ses_nama'] ?? 'User';
?>

<!-- HEADER NAVIGASI ATAS (INI YANG HILANG!) -->
<div class="header">
  <div class="header-left">
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <span class="app-title">📚 SI Perpustakaan</span>
  </div>
  <div class="header-right">
    <span class="user-name"><?= $nama ?></span>
    <a href="/perpustakaan/logout.php" class="logout-btn">🚪 Logout</a>
  </div>
</div>

<!-- SIDEBAR MENU -->
<div class="sidebar" id="sidebar">
  <div class="sidebar-header">📚 Perpustakaan</div>

  <ul class="menu">

    <!-- SEMUA ROLE -->
    <li><a href="/perpustakaan/index.php">🏠 Dashboard</a></li>

    <!-- ================= ADMIN ================= -->
    <?php if ($level == 'Admin') { ?>
      <li><a href="/perpustakaan/admin/buku/buku_data.php">📘 Data Buku</a></li>
      <li><a href="/perpustakaan/admin/anggota/anggota_data.php">👥 Data Anggota</a></li>
      <li><a href="/perpustakaan/admin/peminjaman/peminjaman_data.php">📖 Peminjaman</a></li>
      <li><a href="/perpustakaan/admin/laporan/laporan_peminjaman.php">📊 Laporan Peminjaman</a></li>
    <?php } ?>

    <!-- ================= PETUGAS ================= -->
    <?php if ($level == 'Petugas') { ?>
      <li><a href="/perpustakaan/admin/buku/buku_data.php">📘 Data Buku</a></li>
      <li><a href="/perpustakaan/admin/peminjaman/peminjaman_data.php">📖 Peminjaman</a></li>
    <?php } ?>

    <!-- ================= ANGGOTA ================= -->
    <?php if ($level == 'Anggota') { ?>
      <li><a href="/perpustakaan/home/anggota/katalog.php">📚 Katalog Buku</a></li>
      <li><a href="/perpustakaan/home/anggota/peminjaman_saya.php">📖 Peminjaman Saya</a></li>
    <?php } ?>

  </ul>
</div>

<script>
// Fungsi toggle sidebar
function toggleSidebar() {
  const sidebar = document.getElementById('sidebar');
  const main = document.getElementById('main');
  
  sidebar.classList.toggle('hide');
  if (main) {
    main.classList.toggle('full');
  }
}
</script>