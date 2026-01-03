<?php
include "../../inc/koneksi.php";
include "../../inc/sidebar.php";

$id = $_GET['id'];
mysqli_query($koneksi,"DELETE FROM peminjaman WHERE id_peminjaman='$id'");
header("Location: peminjaman_data.php");
