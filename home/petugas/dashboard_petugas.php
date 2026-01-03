<?php
session_start();
include "../../inc/koneksi.php";
include "../../inc/sidebar.php";
/* PROTEKSI LOGIN */
if (!isset($_SESSION['ses_username'])) {
    header("Location: ../../login.php");
    exit;
}

/* PROTEKSI LEVEL */
if ($_SESSION['ses_level'] != 'Petugas') {
    header("Location: ../../index.php");
    exit;
}

$nama = $_SESSION['ses_nama'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Dashboard Petugas | Perpustakaan</title>

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f4f6f9;
}
.header {
    background: #3c8dbc;
    color: white;
    padding: 15px 25px;
    display: flex;
    justify-content: space-between;
}
.sidebar {
    width: 220px;
    background: #222d32;
    position: fixed;
    top: 60px;
    bottom: 0;
}
.sidebar a {
    display: block;
    padding: 12px 20px;
    color: #b8c7ce;
    text-decoration: none;
}
.sidebar a:hover {
    background: #1e282c;
    color: white;
}
.content {
    margin-left: 220px;
    padding: 30px;
}
.card {
    background: white;
    padding: 20px;
    border-radius: 6px;
}
.logout {
    color: white;
    text-decoration: none;
}
.btn {
    padding: 6px 10px;
    background: #3c8dbc;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    font-size: 13px;
}
table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}
th, td {
    border: 1px solid #ccc;
    padding: 8px;
}
th {
    background: #eee;
}
img {
    width: 60px;
}
</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
    <b>SI Perpustakaan</b>
    <div>
        <?= $nama ?> (Petugas) |
        <a href="../../logout.php" class="logout">Logout</a>
    </div>
</div>

<!-- SIDEBAR -->
<div class="sidebar">
    <a href="../../index.php">🏠 Dashboard Utama</a>
    <a href="dashboard_petugas.php">📘 Kelola Buku</a>
</div>

<!-- CONTENT -->
<div class="content">
<div class="card">

<h2>📘 Data Buku</h2>
<a class="btn" href="../../admin/buku/buku_tambah.php">➕ Tambah Buku</a>

<table>
<tr>
    <th>No</th>
    <th>Cover</th>
    <th>Judul</th>
    <th>Pengarang</th>
    <th>Status</th>
    <th>Aksi</th>
</tr>

<?php
$no = 1;
$q = mysqli_query($koneksi, "SELECT * FROM buku");
while ($b = mysqli_fetch_assoc($q)) {
?>
<tr>
    <td><?= $no++ ?></td>
    <td>
        <img src="../../assets/cover/<?= $b['cover'] ?>">
    </td>
    <td><?= $b['judul'] ?></td>
    <td><?= $b['pengarang'] ?></td>
    <td><?= $b['status'] ?></td>
    <td>
        <a class="btn" href="../../admin/buku/buku_edit.php?id=<?= $b['id_buku'] ?>">Edit</a>
        <a class="btn" style="background:#dd4b39"
           href="../../admin/buku/buku_hapus.php?id=<?= $b['id_buku'] ?>"
           onclick="return confirm('Hapus buku ini?')">Hapus</a>
    </td>
</tr>
<?php } ?>

</table>

</div>
</div>

</body>
</html>
