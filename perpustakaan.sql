-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2026 at 12:29 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `anggota`
--

CREATE TABLE `anggota` (
  `id_anggota` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_level` int(11) NOT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `anggota`
--

INSERT INTO `anggota` (`id_anggota`, `nama`, `username`, `password`, `id_level`, `dibuat_pada`) VALUES
(1, 'Admin Perpustakaan', 'admin', 'admin123', 1, '2025-12-27 03:53:54'),
(4, 'Delia Selvi Angie', '2413030084', 'unpkediri', 3, '2025-12-27 17:03:12'),
(5, 'Rizqina Kautsar Rany', '2413030090', '12345', 3, '2025-12-29 08:40:35'),
(6, 'Elga Khusnia Maharany', '2413030078', '12345', 3, '2025-12-29 08:42:24'),
(7, 'Revalina Banowati Putri Sutomo', '2413030080', '12345', 3, '2025-12-29 08:44:05'),
(8, 'Nabella Putri Kumalla', '2413030093', '12345', 3, '2025-12-29 08:45:13'),
(9, 'Petugas', 'petugas', 'petugas123', 2, '2025-12-29 10:13:01');

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `kode_buku` varchar(20) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `pengarang` varchar(100) DEFAULT NULL,
  `penerbit` varchar(100) DEFAULT NULL,
  `tahun_terbit` int(11) DEFAULT NULL,
  `id_kategori` int(11) DEFAULT NULL,
  `cover` varchar(100) DEFAULT NULL,
  `status` enum('tersedia','dipinjam') DEFAULT 'tersedia',
  `dibuat_pada` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `kode_buku`, `judul`, `pengarang`, `penerbit`, `tahun_terbit`, `id_kategori`, `cover`, `status`, `dibuat_pada`) VALUES
(2, '899.221', 'Laskar Pelangi', 'Andrea Hirata', 'Bentang Pustaka', 2005, 2, 'laskar pelangi.jpg', 'tersedia', '2025-12-27 03:53:54'),
(8, '899.221 TOE b', 'Bumi Manusia', 'Pramoedya Ananta Toer', NULL, 1980, NULL, 'bumi manusia.jpg', 'tersedia', '2026-01-02 08:53:32'),
(9, '899.221 FUA n', 'Negeri 5 Menara', 'Ahmad Fuadi', NULL, 2009, NULL, 'negeri 5 menara.jpg', 'tersedia', '2026-01-02 08:54:36'),
(10, '899.221 HIR s', 'Sang Pemimpi', 'Andrea Hirata', NULL, 2006, NULL, 'sang pemimpi.jpg', 'tersedia', '2026-01-02 08:55:22'),
(12, '899.221 LIY h', 'Hujan', 'Tere Liye', NULL, 2016, NULL, 'hujan.jpg', 'tersedia', '2026-01-02 08:57:14'),
(14, '899.221 DHI l', '5 cm', 'Donny Dhirgantoro', NULL, 2005, NULL, '55059013.jpg', 'tersedia', '2026-01-02 08:58:50'),
(15, '899.221 LIY b', 'Bumi', 'Tere Liye', NULL, 2014, NULL, 'bumi.jpg', 'tersedia', '2026-01-02 13:46:28'),
(16, '899.221 TOH r', 'Ronggeng Dukuh Paruh', 'Ahmad Tohari', NULL, 1982, NULL, 'ronggeng.jpg', 'tersedia', '2026-01-02 13:47:53');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_buku`
--

CREATE TABLE `kategori_buku` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori_buku`
--

INSERT INTO `kategori_buku` (`id_kategori`, `nama_kategori`) VALUES
(1, 'Teknologi'),
(2, 'Novel'),
(3, 'Sejarah'),
(4, 'Pendidikan'),
(5, 'Agama');

-- --------------------------------------------------------

--
-- Table structure for table `level_pengguna`
--

CREATE TABLE `level_pengguna` (
  `id_level` int(11) NOT NULL,
  `nama_level` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `level_pengguna`
--

INSERT INTO `level_pengguna` (`id_level`, `nama_level`) VALUES
(1, 'Admin'),
(2, 'Petugas'),
(3, 'Anggota');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `id_anggota` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `tanggal_pinjam` date NOT NULL,
  `tanggal_kembali` date NOT NULL,
  `tanggal_dikembalikan` date DEFAULT NULL,
  `denda` int(11) DEFAULT 0,
  `status` enum('dipinjam','kembali') DEFAULT 'dipinjam'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `id_anggota`, `id_buku`, `tanggal_pinjam`, `tanggal_kembali`, `tanggal_dikembalikan`, `denda`, `status`) VALUES
(5, 4, 8, '2026-01-03', '2026-01-10', '2026-01-03', 0, 'kembali'),
(6, 4, 15, '2025-12-01', '2026-01-10', '2026-01-03', 0, 'kembali');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `anggota`
--
ALTER TABLE `anggota`
  ADD PRIMARY KEY (`id_anggota`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD UNIQUE KEY `kode_buku` (`kode_buku`);

--
-- Indexes for table `kategori_buku`
--
ALTER TABLE `kategori_buku`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `level_pengguna`
--
ALTER TABLE `level_pengguna`
  ADD PRIMARY KEY (`id_level`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `anggota`
--
ALTER TABLE `anggota`
  MODIFY `id_anggota` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `kategori_buku`
--
ALTER TABLE `kategori_buku`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `level_pengguna`
--
ALTER TABLE `level_pengguna`
  MODIFY `id_level` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
