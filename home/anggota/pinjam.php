<?php
session_start();
include "../../inc/koneksi.php";
include "../../inc/sidebar.php";
if (!isset($_SESSION['ses_username'])) {
    header("Location: ../../login.php");
    exit;
}

if ($_SESSION['ses_level'] != 'Anggota') {
    header("Location: ../../index.php");
    exit;
}

$id_anggota = $_SESSION['ses_id'];
$id_buku = $_GET['id'] ?? 0;

// cek buku
$buku = mysqli_query($koneksi, "
    SELECT * FROM buku 
    WHERE id_buku = '$id_buku' AND status = 'tersedia'
");

if (mysqli_num_rows($buku) == 0) {
    echo "Buku tidak tersedia";
    exit;
}

// simpan peminjaman
$tgl_pinjam  = date('Y-m-d');
$tgl_kembali = date('Y-m-d', strtotime('+7 days'));

mysqli_query($koneksi, "
    INSERT INTO peminjaman 
    (id_anggota, id_buku, tanggal_pinjam, tanggal_kembali)
    VALUES 
    ('$id_anggota', '$id_buku', '$tgl_pinjam', '$tgl_kembali')
");

// update status buku
mysqli_query($koneksi, "
    UPDATE buku SET status='dipinjam'
    WHERE id_buku='$id_buku'
");

header("Location: ../../index.php#pinjaman");
exit;
