<?php
if(!isset($_SESSION)) session_start();
$nama  = $_SESSION['ses_nama'] ?? '';
$level = $_SESSION['ses_level'] ?? '';
?>
<link rel="stylesheet" href="/perpustakaan/assets/css/sidebar.css">

<div class="header">
  <div class="header-left">
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <b>📚 SI Perpustakaan</b>
  </div>

  <div class="header-right">
    <div class="search-box">
      <input type="text" placeholder="Cari...">
    </div>
    <div><?= date('d M Y') ?></div>
    <div><?= $nama ?> (<?= $level ?>)</div>
    <a href="/perpustakaan/logout.php" style="color:#fff;text-decoration:none">Logout</a>
  </div>
</div>
