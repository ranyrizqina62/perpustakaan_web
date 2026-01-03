<?php
session_start();
include "../../inc/koneksi.php";

if (!isset($_SESSION['ses_username'])) {
    header("Location: ../../login.php");
    exit;
}

$id = mysqli_real_escape_string($koneksi, $_POST['id_buku']);
$kode = mysqli_real_escape_string($koneksi, $_POST['kode_buku']);
$judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
$pengarang = mysqli_real_escape_string($koneksi, $_POST['pengarang']);
$cover_lama = $_POST['cover_lama'];

// Cek apakah ada cover baru
if ($_FILES['cover']['name'] != "") {
    $nama_file = $_FILES['cover']['name'];
    $tmp = $_FILES['cover']['tmp_name'];
    
    // Rename dengan timestamp
    $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_file = time() . '_' . $kode . '.' . $ext;

    move_uploaded_file($tmp, "../../assets/cover/" . $nama_file);

    // Hapus cover lama jika ada
    if ($cover_lama != "" && file_exists("../../assets/cover/" . $cover_lama)) {
        unlink("../../assets/cover/" . $cover_lama);
    }

    // Update dengan cover baru
    $query = mysqli_query($koneksi, "
        UPDATE buku SET
        kode_buku='$kode',
        judul='$judul',
        pengarang='$pengarang',
        cover='$nama_file'
        WHERE id_buku='$id'
    ");
} else {
    // Update tanpa ganti cover
    $query = mysqli_query($koneksi, "
        UPDATE buku SET
        kode_buku='$kode',
        judul='$judul',
        pengarang='$pengarang'
        WHERE id_buku='$id'
    ");
}

if ($query) {
    header("Location: buku_data.php?alert=success_edit");
} else {
    header("Location: buku_data.php?alert=error");
}
?>