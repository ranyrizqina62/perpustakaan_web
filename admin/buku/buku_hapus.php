<?php
session_start();
include '../../inc/koneksi.php';

if (!isset($_SESSION['ses_username'])) {
    header("Location: ../../login.php");
    exit;
}

$id = $_GET['id'];

// Ambil data cover untuk dihapus
$data = mysqli_query($koneksi, "SELECT cover FROM buku WHERE id_buku='$id'");
$buku = mysqli_fetch_assoc($data);

// Hapus file cover jika ada