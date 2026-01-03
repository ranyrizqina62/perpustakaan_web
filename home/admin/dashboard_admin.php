<?php
session_start();
include "../inc/koneksi.php";
include "../inc/sidebar.php";

if (!isset($_SESSION['ses_username']) || $_SESSION['ses_level'] != 'Admin') {
    header("Location: ../login.php");
    exit;
}

$nama = $_SESSION['ses_nama'];

/* ===== DATA STATISTIK ===== */
$total_buku = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_buku FROM buku"));
$total_anggota = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_anggota FROM anggota"));
$total_pinjam = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_peminjaman FROM peminjaman"));
$buku_dipinjam = mysqli_num_rows(mysqli_query($koneksi, "SELECT id_buku FROM buku WHERE status='dipinjam'"));
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin | SI Perpustakaan</title>

<style>
*{box-sizing:border-box}
body{
    margin:0;
    font-family:'Segoe UI', Arial;
    background:#f4f6f9;
}

/* HEADER */
.header{
    background:#3f8efc;
    color:#fff;
    padding:15px 25px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

/* SIDEBAR */
.sidebar{
    width:230px;
    background:#1f2933;
    position:fixed;
    top:60px;
    bottom:0;
}
.sidebar a{
    display:block;
    padding:14px 22px;
    color:#cbd5e1;
    text-decoration:none;
}
.sidebar a:hover{
    background:#111827;
    color:#fff;
}

/* MAIN */
.main{
    margin-left:230px;
    padding:25px;
}

/* CARD */
.card{
    background:#fff;
    border-radius:14px;
    padding:22px;
    margin-bottom:20px;
}

/* STATS */
.stats{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
}
.stat-box{
    background:#ffffff;
    border-radius:14px;
    padding:20px;
    box-shadow:0 4px 10px rgba(0,0,0,.05);
}
.stat-box h2{
    margin:0;
    font-size:28px;
    color:#3f8efc;
}
.stat-box p{
    margin:6px 0 0;
    color:#555;
}

/* TABLE */
table{
    width:100%;
    border-collapse:collapse;
}
th,td{
    border:1px solid #e5e7eb;
    padding:12px;
}
th{
    background:#f1f5f9;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <b>📚 Dashboard Admin</b>
    <div><?= $nama ?> | <a href="../logout.php" style="color:white;text-decoration:none">Logout</a></div>
</div>

<!-- SIDEBAR -->
<div class="sidebar">
    <a href="dashboard_admin.php">🏠 Dashboard</a>
    <a href="buku/buku_data.php">📘 Data Buku</a>
    <a href="anggota/anggota_data.php">👥 Data Anggota</a>
    <a href="peminjaman/peminjaman_data.php">📖 Peminjaman</a>
    <a href="laporan/laporan_peminjaman.php">📊 Laporan</a>
</div>

<!-- MAIN -->
<div class="main">

<div class="card">
    <h2>👋 Selamat Datang, Admin</h2>
    <p>Kelola data perpustakaan dengan mudah.</p>
</div>

<!-- STATISTIK -->
<div class="stats">

<div class="stat-box">
    <h2><?= $total_buku ?></h2>
    <p>Total Buku</p>
</div>

<div class="stat-box">
    <h2><?= $total_anggota ?></h2>
    <p>Total Anggota</p>
</div>

<div class="stat-box">
    <h2><?= $total_pinjam ?></h2>
    <p>Total Peminjaman</p>
</div>

<div class="stat-box">
    <h2><?= $buku_dipinjam ?></h2>
    <p>Buku Sedang Dipinjam</p>
</div>

</div>

<!-- PEMINJAMAN TERBARU -->
<div class="card">
<h3>📖 Peminjaman Terbaru</h3>

<table>
<tr>
    <th>Anggota</th>
    <th>Judul Buku</th>
    <th>Tanggal Pinjam</th>
    <th>Status</th>
</tr>

<?php
$q = mysqli_query($koneksi,"
SELECT p.*, a.nama, b.judul
FROM peminjaman p
JOIN anggota a ON p.id_anggota=a.id_anggota
JOIN buku b ON p.id_buku=b.id_buku
ORDER BY p.id_peminjaman DESC
LIMIT 5
");

if (mysqli_num_rows($q)==0){
    echo "<tr><td colspan='4'>Belum ada peminjaman</td></tr>";
}

while($r=mysqli_fetch_assoc($q)){
?>
<tr>
    <td><?= $r['nama'] ?></td>
    <td><?= $r['judul'] ?></td>
    <td><?= $r['tanggal_pinjam'] ?></td>
    <td><?= $r['status'] ?></td>
</tr>
<?php } ?>
</table>
</div>

</div>

</body>
</html>
