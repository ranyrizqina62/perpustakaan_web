<?php
session_start();
include "../../inc/koneksi.php";

if (!isset($_SESSION['ses_username'])) {
    header("Location: ../../login.php");
    exit;
}

// Validasi parameter ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: anggota_data.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_GET['id']);

// Cek apakah anggota ada
$cek = mysqli_query($koneksi, "SELECT * FROM anggota WHERE id_anggota='$id'");
if (mysqli_num_rows($cek) == 0) {
    header("Location: anggota_data.php");
    exit;
}

// Hapus data anggota
mysqli_query($koneksi, "DELETE FROM anggota WHERE id_anggota='$id'");

// Redirect ke halaman data anggota dengan pesan sukses
header("Location: anggota_data.php?hapus=sukses");
exit;
?>