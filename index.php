<?php
session_start();
include "inc/koneksi.php";

if (!isset($_SESSION['ses_username'])) {
  header("Location: login.php");
  exit;
}

$nama     = $_SESSION['ses_nama'];
$level    = $_SESSION['ses_level'];
$tanggal  = date('d F Y');
$keyword  = isset($_GET['q']) ? mysqli_real_escape_string($koneksi, $_GET['q']) : '';
$keyword  = trim($keyword);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard | SI Perpustakaan</title>
<link rel="stylesheet" href="assets/css/sidebar.css">

<style>
*{box-sizing:border-box}
body{
  margin:0;
  font-family:'Segoe UI',Tahoma,Arial,sans-serif;
  background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  background-attachment:fixed;
  min-height:100vh;
}

/* ============ HEADER ============ */
.header{
  height:60px;
  background:rgba(37, 99, 235, 0.95);
  backdrop-filter:blur(10px);
  color:#fff;
  display:flex;
  align-items:center;
  justify-content:space-between;
  padding:0 20px;
  position:fixed;
  top:0; left:0; right:0;
  z-index:1200;
  box-shadow:0 4px 20px rgba(0,0,0,0.1);
  animation:slideDown 0.5s ease;
}

@keyframes slideDown{
  from{ transform:translateY(-100%); opacity:0; }
  to{ transform:translateY(0); opacity:1; }
}

.header-left{
  display:flex;
  align-items:center;
  gap:15px;
}

.toggle-btn{
  background:none;
  border:none;
  font-size:26px;
  color:#fff;
  cursor:pointer;
  transition:all 0.3s;
  padding:5px;
  border-radius:8px;
}

.toggle-btn:hover{
  background:rgba(255,255,255,0.2);
  transform:scale(1.1);
}

.search-box input{
  padding:8px 15px;
  border-radius:25px;
  border:none;
  outline:none;
  width:240px;
  transition:all 0.3s;
  box-shadow:0 2px 10px rgba(0,0,0,0.1);
}

.search-box input:focus{
  width:280px;
  box-shadow:0 4px 20px rgba(0,0,0,0.2);
}

.header-right{
  display:flex;
  align-items:center;
  gap:15px;
  font-size:14px;
}

.header-right span{
  padding:8px 15px;
  background:rgba(255,255,255,0.1);
  border-radius:20px;
  animation:fadeIn 0.8s ease;
}

@keyframes fadeIn{
  from{ opacity:0; }
  to{ opacity:1; }
}

.header-right a{
  color:#fff;
  text-decoration:none;
  font-weight:bold;
  padding:8px 20px;
  background:rgba(220, 38, 38, 0.8);
  border-radius:20px;
  transition:all 0.3s;
}

.header-right a:hover{
  background:rgba(220, 38, 38, 1);
  transform:scale(1.05);
  box-shadow:0 4px 15px rgba(220, 38, 38, 0.4);
}

/* ============ MAIN ============ */
.main{
  margin-left:230px;
  margin-top:60px;
  padding:25px;
  transition:margin-left 0.3s;
}

.main.full{ margin-left:0; }

/* ============ CARD ============ */
.card{
  background:rgba(255,255,255,0.95);
  backdrop-filter:blur(10px);
  border-radius:20px;
  padding:25px;
  margin-bottom:25px;
  box-shadow:0 8px 32px rgba(0,0,0,0.1);
  animation:fadeInUp 0.6s ease;
  transition:all 0.3s;
}

.card:hover{
  transform:translateY(-5px);
  box-shadow:0 12px 40px rgba(0,0,0,0.15);
}

@keyframes fadeInUp{
  from{
    opacity:0;
    transform:translateY(30px);
  }
  to{
    opacity:1;
    transform:translateY(0);
  }
}

.card h2{
  margin:0 0 10px;
  color:#1f2937;
  font-size:24px;
}

.card h3{
  margin:0 0 15px;
  color:#374151;
  font-size:20px;
}

/* ============ STATS ============ */
.stats{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
  gap:20px;
}

.stat{
  background:linear-gradient(135deg,#667eea,#764ba2);
  color:#fff;
  padding:25px;
  border-radius:20px;
  text-align:center;
  box-shadow:0 8px 25px rgba(102, 126, 234, 0.4);
  animation:scaleIn 0.5s ease;
  transition:all 0.3s;
  position:relative;
  overflow:hidden;
}

.stat::before{
  content:'';
  position:absolute;
  top:-50%;
  left:-50%;
  width:200%;
  height:200%;
  background:linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
  transform:rotate(45deg);
  animation:shine 3s infinite;
}

@keyframes shine{
  0%{ transform:translateX(-100%) rotate(45deg); }
  100%{ transform:translateX(100%) rotate(45deg); }
}

.stat:hover{
  transform:translateY(-8px) scale(1.02);
  box-shadow:0 12px 35px rgba(102, 126, 234, 0.5);
}

@keyframes scaleIn{
  from{
    opacity:0;
    transform:scale(0.8);
  }
  to{
    opacity:1;
    transform:scale(1);
  }
}

.stat b{
  font-size:36px;
  display:block;
  margin-top:10px;
  animation:numberCount 1s ease;
}

@keyframes numberCount{
  from{ opacity:0; transform:translateY(20px); }
  to{ opacity:1; transform:translateY(0); }
}

/* ============ BOOKS ============ */
.books{
  display:grid;
  grid-template-columns:repeat(auto-fit,minmax(180px,1fr));
  gap:20px;
}

.book{
  background:#fff;
  border-radius:16px;
  overflow:hidden;
  box-shadow:0 8px 25px rgba(0,0,0,0.1);
  transition:all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  text-decoration:none;
  color:#000;
  animation:fadeInUp 0.6s ease;
}

.book:hover{
  transform:translateY(-10px) scale(1.03);
  box-shadow:0 15px 40px rgba(0,0,0,0.2);
}

.book img{
  width:100%;
  height:220px;
  object-fit:cover;
  background:linear-gradient(135deg, #f1f5f9, #e2e8f0);
  transition:all 0.4s;
}

.book:hover img{
  transform:scale(1.1);
}

.book .info{
  padding:15px;
  background:#fff;
}

.book h3{
  margin:0 0 8px;
  font-size:15px;
  color:#1f2937;
}

.book small{
  color:#6b7280;
}

/* ============ DASHBOARD ANGGOTA STYLES ============ */
.card.warning{
  border-left:5px solid #f59e0b;
  background:linear-gradient(135deg, #fef3c7, #fde68a);
}

.card.pinjam{
  border-left:5px solid #3b82f6;
  background:linear-gradient(135deg, #dbeafe, #bfdbfe);
}

.card.aman{
  border-left:5px solid #10b981;
  background:linear-gradient(135deg, #d1fae5, #a7f3d0);
}

.card.terlambat{
  border-left:5px solid #dc2626;
  background:linear-gradient(135deg, #fee2e2, #fecaca);
  animation:shake 0.5s ease;
}

@keyframes shake{
  0%, 100%{ transform:translateX(0); }
  25%{ transform:translateX(-10px); }
  75%{ transform:translateX(10px); }
}

.card.urgent{
  border-left:5px solid #f59e0b;
  background:linear-gradient(135deg, #fef3c7, #fde68a);
  animation:pulse 2s infinite;
}

@keyframes pulse{
  0%, 100%{ box-shadow:0 8px 32px rgba(245, 158, 11, 0.2); }
  50%{ box-shadow:0 8px 32px rgba(245, 158, 11, 0.4); }
}

.card table{
  width:100%;
  border-collapse:collapse;
  margin-top:15px;
}

.card table th{
  background:linear-gradient(135deg, #f1f5f9, #e2e8f0);
  padding:12px;
  text-align:left;
  border-bottom:2px solid #cbd5e1;
  font-weight:600;
  color:#1f2937;
}

.card table td{
  padding:12px;
  border-bottom:1px solid #e5e7eb;
  transition:background 0.3s;
}

.card table tr:hover td{
  background:#f9fafb;
}

.card ul{
  list-style:none;
  padding:0;
}

.card ul li{
  padding:10px 0;
  border-bottom:1px solid #e5e7eb;
  transition:all 0.3s;
}

.card ul li:hover{
  padding-left:10px;
  color:#2563eb;
}

/* ============ NOTIFIKASI ============ */
.notif {
  padding:15px 20px;
  border-radius:12px;
  margin-bottom:20px;
  font-weight:500;
  animation:slideDown 0.5s ease, pulse 2s infinite;
  box-shadow:0 4px 15px rgba(0,0,0,0.1);
}

.notif.terlambat {
  background:linear-gradient(135deg, #fee2e2, #fecaca);
  border-left:5px solid #dc2626;
  color:#991b1b;
}

.notif.urgent {
  background:linear-gradient(135deg, #fef3c7, #fde68a);
  border-left:5px solid #f59e0b;
  color:#92400e;
}

.notif.warning {
  background:linear-gradient(135deg, #dbeafe, #bfdbfe);
  border-left:5px solid #3b82f6;
  color:#1e40af;
}

/* ============ STATS MINI ============ */
.stats-mini {
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));
  gap:20px;
  margin-bottom:25px;
}

.stat-mini {
  background:linear-gradient(135deg, #6366f1, #8b5cf6);
  color:#fff;
  padding:20px;
  border-radius:16px;
  text-align:center;
  box-shadow:0 8px 25px rgba(99, 102, 241, 0.4);
  animation:scaleIn 0.5s ease;
  transition:all 0.3s;
  position:relative;
  overflow:hidden;
}

.stat-mini::before{
  content:'';
  position:absolute;
  top:0;
  left:-100%;
  width:100%;
  height:100%;
  background:linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
  animation:slideRight 2s infinite;
}

@keyframes slideRight{
  0%{ left:-100%; }
  100%{ left:100%; }
}

.stat-mini:hover{
  transform:translateY(-5px) scale(1.02);
  box-shadow:0 12px 35px rgba(99, 102, 241, 0.5);
}

.stat-mini b {
  display:block;
  font-size:36px;
  margin-top:10px;
  animation:numberCount 1s ease;
}

/* ============ BUKU INFO ============ */
.buku-info {
  display:flex;
  gap:20px;
  align-items:center;
  margin-top:15px;
  padding:20px;
  background:rgba(255,255,255,0.8);
  border-radius:16px;
  animation:fadeInUp 0.6s ease;
  transition:all 0.3s;
}

.buku-info:hover{
  background:rgba(255,255,255,1);
  box-shadow:0 8px 20px rgba(0,0,0,0.1);
}

.buku-cover {
  width:110px;
  height:150px;
  object-fit:cover;
  border-radius:12px;
  box-shadow:0 8px 20px rgba(0,0,0,0.2);
  transition:all 0.4s;
}

.buku-cover:hover{
  transform:scale(1.05) rotate(2deg);
  box-shadow:0 12px 30px rgba(0,0,0,0.3);
}

.buku-detail {
  flex:1;
}

.buku-detail h4 {
  margin:0 0 12px;
  font-size:20px;
  color:#1f2937;
}

.info-row {
  display:flex;
  align-items:center;
  gap:8px;
  margin:10px 0;
  color:#4b5563;
  animation:fadeIn 0.8s ease;
}

.sisa-hari {
  display:inline-block;
  padding:6px 14px;
  border-radius:20px;
  font-weight:bold;
  font-size:14px;
  animation:bounce 2s infinite;
}

@keyframes bounce{
  0%, 100%{ transform:translateY(0); }
  50%{ transform:translateY(-5px); }
}

.sisa-hari.normal {
  background:linear-gradient(135deg, #d1fae5, #a7f3d0);
  color:#065f46;
}

.sisa-hari.warning {
  background:linear-gradient(135deg, #fef3c7, #fde68a);
  color:#92400e;
}

.sisa-hari.urgent {
  background:linear-gradient(135deg, #fee2e2, #fecaca);
  color:#991b1b;
  animation:pulse 1s infinite;
}

/* ============ MENU CEPAT ============ */
.menu-cepat {
  display:grid;
  grid-template-columns:repeat(auto-fit, minmax(250px, 1fr));
  gap:20px;
  margin-top:20px;
}

.menu-item {
  display:block;
  padding:20px;
  background:linear-gradient(135deg, #f3f4f6, #e5e7eb);
  border-radius:16px;
  text-decoration:none;
  color:#1f2937;
  transition:all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
  border-left:4px solid #2563eb;
  box-shadow:0 4px 15px rgba(0,0,0,0.1);
  position:relative;
  overflow:hidden;
}

.menu-item::before{
  content:'';
  position:absolute;
  top:0;
  left:-100%;
  width:100%;
  height:100%;
  background:linear-gradient(90deg, transparent, rgba(37, 99, 235, 0.1), transparent);
  transition:left 0.5s;
}

.menu-item:hover::before{
  left:100%;
}

.menu-item:hover {
  transform:translateY(-8px) scale(1.02);
  box-shadow:0 12px 30px rgba(37, 99, 235, 0.3);
  background:linear-gradient(135deg, #e5e7eb, #d1d5db);
}

.menu-item strong {
  display:block;
  font-size:18px;
  margin-bottom:8px;
  color:#1f2937;
}

.menu-item small {
  color:#6b7280;
}

/* ============ RESPONSIVE ============ */
@media (max-width:768px){
  .main{ margin-left:0; padding:15px; }
  .header-left{ gap:10px; }
  .search-box input{ width:150px; }
  .search-box input:focus{ width:180px; }
  .header-right span{ display:none; }
  .stats{ grid-template-columns:1fr; }
  .books{ grid-template-columns:repeat(auto-fit, minmax(140px, 1fr)); }
  .buku-info{ flex-direction:column; text-align:center; }
}

/* ============ LOADING ANIMATION ============ */
@keyframes gradientShift{
  0%{ background-position:0% 50%; }
  50%{ background-position:100% 50%; }
  100%{ background-position:0% 50%; }
}

.card, .stat, .book{
  background-size:200% 200%;
}
</style>
</head>

<body>

<div class="header">
  <div class="header-left">
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
    <b>📚 SI Perpustakaan</b>
    <div class="search-box">
      <form method="get">
        <input type="text" name="q" placeholder="Cari judul / pengarang..." value="<?= htmlspecialchars($keyword) ?>">
      </form>
    </div>
  </div>
  <div class="header-right">
    <span><?= $tanggal ?></span>
    <span><?= $nama ?> (<?= $level ?>)</span>
    <a href="logout.php">Logout</a>
  </div>
</div>

<div class="wrapper">
<?php include "inc/sidebar.php"; ?>

<div class="main" id="main">

<?php if($keyword!=''){ ?>
<!-- ================= SEARCH ================= -->
<div class="card">
  <h2>🔎 Hasil Pencarian</h2>
  <p>Kata kunci: <b><?= htmlspecialchars($keyword) ?></b></p>
</div>

<div class="books">
<?php
$q = mysqli_query($koneksi,"
  SELECT * FROM buku
  WHERE LOWER(judul) LIKE LOWER('%$keyword%')
     OR LOWER(pengarang) LIKE LOWER('%$keyword%')
");

if(mysqli_num_rows($q)==0){
  echo "<div class='card'>❌ Buku tidak ditemukan</div>";
}

while($b=mysqli_fetch_assoc($q)){
  $cover = $b['cover'] ?: 'default.jpg';

  if($level=='Admin' || $level=='Petugas'){
    $link = "admin/buku/buku_data.php";
  }else{
    $link = "home/anggota/katalog.php";
  }
?>
<a href="<?= $link ?>" class="book">
  <img src="assets/cover/<?= $cover ?>" alt="<?= htmlspecialchars($b['judul']) ?>">
  <div class="info">
    <h3><?= $b['judul'] ?></h3>
    <small>✍️ <?= $b['pengarang'] ?></small>
  </div>
</a>
<?php } ?>
</div>

<?php } else { ?>

<!-- ================= WELCOME (Admin & Petugas) ================= -->
<?php if($level != 'Anggota'){ ?>
<div class="card">
  <h2>👋 Selamat Datang, <?= $nama ?>!</h2>
  <p>Selamat datang di <b>Sistem Informasi Perpustakaan</b> 📖</p>
</div>
<?php } ?>

<?php
/* ================= ADMIN ================= */
if($level=='Admin'){
$total_buku = mysqli_num_rows(mysqli_query($koneksi,"SELECT id_buku FROM buku"));
$total_anggota = mysqli_num_rows(mysqli_query(
  $koneksi,
  "SELECT a.id_anggota FROM anggota a
   JOIN level_pengguna l ON a.id_level=l.id_level
   WHERE l.nama_level='Anggota'"
));
$dipinjam = mysqli_num_rows(mysqli_query(
  $koneksi,
  "SELECT id_peminjaman FROM peminjaman WHERE status='dipinjam'"
));
?>
<div class="stats">
  <div class="stat">📘 Total Buku<br><b><?= $total_buku ?></b></div>
  <div class="stat">👥 Total Anggota<br><b><?= $total_anggota ?></b></div>
  <div class="stat">📖 Sedang Dipinjam<br><b><?= $dipinjam ?></b></div>
</div>
<?php } ?>

<?php
/* ================= PETUGAS ================= */
if($level=='Petugas'){
$dipinjam = mysqli_num_rows(mysqli_query(
  $koneksi,
  "SELECT id_peminjaman FROM peminjaman WHERE status='dipinjam'"
));
?>
<div class="stats">
  <div class="stat">📖 Buku Sedang Dipinjam<br><b><?= $dipinjam ?></b></div>
</div>
<?php } ?>

<?php
/* ================= ANGGOTA ================= */
if($level=='Anggota'){
  include "home/anggota/dashboard_anggota.php";
}
?>

<?php } ?>

</div>
</div>

<script>
function toggleSidebar(){
  document.querySelector('.sidebar').classList.toggle('hide');
  document.getElementById('main').classList.toggle('full');
}

// Smooth scroll
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    document.querySelector(this.getAttribute('href')).scrollIntoView({
      behavior: 'smooth'
    });
  });
});

// Add stagger animation to cards
window.addEventListener('load', () => {
  const cards = document.querySelectorAll('.card, .stat, .book');
  cards.forEach((card, index) => {
    card.style.animationDelay = `${index * 0.1}s`;
  });
});
</script>

</body>
</html>