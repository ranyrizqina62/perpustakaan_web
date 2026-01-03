<?php
session_start();
if (!isset($_SESSION['ses_username'])) {
    header("Location: ../../login.php");
    exit;
}

include "../../inc/koneksi.php";

if (!isset($_GET['id']) || !isset($_GET['buku'])) {
    header("Location: peminjaman_data.php");
    exit;
}

$id_peminjaman = (int) $_GET['id'];
$id_buku = (int) $_GET['buku'];

$query = mysqli_query($koneksi, "
    SELECT tanggal_kembali
    FROM peminjaman
    WHERE id_peminjaman = $id_peminjaman
");

$data = mysqli_fetch_assoc($query);
$tgl_kembali = $data['tanggal_kembali'];
$tgl_sekarang = date('Y-m-d');

$selisih = (strtotime($tgl_sekarang) - strtotime($tgl_kembali)) / 86400;
$hari_telat = $selisih > 0 ? floor($selisih) : 0;

$tarif_denda = 1000;
$total_denda = $hari_telat * $tarif_denda;

mysqli_query($koneksi, "
    UPDATE peminjaman SET
        status = 'kembali',
        tanggal_dikembalikan = '$tgl_sekarang',
        denda = $total_denda
    WHERE id_peminjaman = $id_peminjaman
");

mysqli_query($koneksi, "
    UPDATE buku SET status = 'tersedia'
    WHERE id_buku = $id_buku
");

header("Location: peminjaman_data.php");
exit;
?>
