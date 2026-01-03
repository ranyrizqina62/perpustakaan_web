SISTEM MANAJEMEN
PERPUSTAKAAN

INFORMASI PROYEK
•	Nama Sistem: Perpustakaan Digital
•	Versi: 1.0
•	Teknologi: PHP, MySQL, Bootstrap 5
•	Fitur: CRUD, REST API, Laporan, Multi-user
ANGGOTA KELOMPOK
•	Elga Khusnia Maharani (2413030078)
•	Revalina Banowati Putri Sutomo (2413030080)
•	Rizqina Kautsar Rany (2413030090)
•	Delia Selvi Angie (2413030084)
•	Nabella Putri Kumalla (2413030093)

FITUR UTAMA
Manajemen Buku
•	Tambah, Edit, Hapus, Lihat Buku
•	Upload Cover Buku
•	Pencarian Buku
•	Kategori dan ISBN
Manajemen Anggota
•	Pendaftaran Anggota Baru
•	Update Data Anggota
•	Status Keanggotaan
•	Riwayat Peminjaman
Sistem Peminjaman
•	Peminjaman Buku
•	Pengembalian Buku
•	Denda Keterlambatan
•	Riwayat Peminjaman
Laporan
•	Laporan Peminjaman Harian/Bulanan
•	Statistik Penggunaan
•	Export ke PDF/Excel
•	Dashboard Statistik
RESTful API
•	Endpoint: /api/buku, /api/anggota, /api/peminjaman
•	Format Response: JSON
•	Support: GET, POST, PUT, DELETE
•	CORS Enabled
Multi-user System
•	Admin: Full Access
•	Petugas: Manage Peminjaman
•	Anggota: View Only

INSTALASI
Persyaratan Sistem:
•	PHP 7.4 atau lebih tinggi
•	MySQL 5.7 atau lebih tinggi
•	Apache Web Server
•	mod_rewrite enabled
Langkah Instalasi:
1. Clone Repository
git clone https://github.com/username/perpustakaan.git
cd perpustakaan
2. Import Database
-- Gunakan phpMyAdmin atau MySQL CLI
-- File: database/db_perpustakaan.sql
mysql -u root -p perpustakaan < database/db_perpustakaan.sql
3. Konfigurasi Database
Edit file inc/koneksi.php:
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "db_perpustakaan";
?>
4. Akses Aplikasi
http://localhost/perpustakaan/
Login Default:
•	Admin: admin / admin123
•	Petugas: petugas / petugas123

RESTful API DOKUMENTASI
Base URL:
http://localhost/perpustakaan/api/
Endpoints Tersedia:
Method	Endpoint	Deskripsi	Contoh
GET	/buku	Get semua buku	GET /api/buku
GET	/buku?id=1	Get buku by ID	GET /api/buku?id=1
POST	/buku	Tambah buku baru	POST /api/buku
PUT	/buku?id=1	Update buku	PUT /api/buku?id=1
DELETE	/buku?id=1	Hapus buku	DELETE /api/buku?id=1
GET	/anggota	Get semua anggota	GET /api/anggota
GET	/peminjaman	Get data peminjaman	GET /api/peminjaman
GET	/stats	Get statistik	GET /api/stats
GET	/search?q=php	Search buku	GET /api/search?q=php


Contoh Penggunaan API:
JavaScript:
// Get semua buku
fetch('http://localhost/perpustakaan/api/buku')
  .then(response => response.json())
  .then(data => {
    console.log('Data Buku:', data);
    // Tampilkan di website Anda
  });

// Tambah buku baru
fetch('http://localhost/perpustakaan/api/buku', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    judul_buku: 'Belajar REST API',
    pengarang: 'John Doe',
    penerbit: 'PT. API Indonesia'
  })
})
  .then(response => response.json())
  .then(data => console.log('Response:', data));
PHP:
<?php
// Get data buku
$url = 'http://localhost/perpustakaan/api/buku';
$data = json_decode(file_get_contents($url), true);

foreach ($data['data'] as $buku) {
    echo "Judul: " . $buku['judul_buku'] . "<br>";
    echo "Pengarang: " . $buku['pengarang'] . "<br><br>";
}
?>
Python:
import requests
import json

# Get data buku
response = requests.get('http://localhost/perpustakaan/api/buku')
data = response.json()

for buku in data['data']:
    print(f"Judul: {buku['judul_buku']}")
    print(f"Pengarang: {buku['pengarang']}")
    print()
cURL:
# Get semua buku
curl -X GET http://localhost/perpustakaan/api/buku

# Tambah buku baru
curl -X POST "http://localhost/perpustakaan/api/buku" \
  -H "Content-Type: application/json" \
  -d '{"judul_buku":"Buku Baru","pengarang":"Penulis"}'
Tabel: buku
CREATE TABLE buku (
    id_buku INT PRIMARY KEY AUTO_INCREMENT,
    judul_buku VARCHAR(255) NOT NULL,
    pengarang VARCHAR(100),
    penerbit VARCHAR(100),
    tahun_terbit YEAR,
    isbn VARCHAR(20),
    jumlah INT DEFAULT 0,
    lokasi VARCHAR(50),
    cover VARCHAR(255),
    tanggal_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
Tabel: anggota
CREATE TABLE anggota (
    id_anggota INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    alamat TEXT,
    telepon VARCHAR(15),
    email VARCHAR(100),
    tanggal_daftar DATE DEFAULT CURRENT_DATE
);
Tabel: peminjaman
CREATE TABLE peminjaman (
    id_peminjaman INT PRIMARY KEY AUTO_INCREMENT,
    id_buku INT,
    id_anggota INT,
    tanggal_pinjam DATE DEFAULT CURRENT_DATE,
    tanggal_kembali DATE,
    status ENUM('Dipinjam', 'Dikembalikan') DEFAULT 'Dipinjam',
    FOREIGN KEY (id_buku) REFERENCES buku(id_buku) ON DELETE CASCADE,
    FOREIGN KEY (id_anggota) REFERENCES anggota(id_anggota) ON DELETE CASCADE
);
Tabel: users
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    level ENUM('admin', 'petugas') DEFAULT 'petugas'
);

TROUBLESHOOTING
Masalah 1: API Tidak Berjalan
Solusi:
1. Pastikan folder 'api' ada
2. Cek file .htaccess di folder api
3. Enable mod_rewrite di Apache:
   sudo a2enmod rewrite
   sudo service apache2 restart
Masalah 2: Koneksi Database Error
Solusi:
1. Periksa inc/koneksi.php
2. Pastikan MySQL running
3. Cek username/password database
Masalah 3: Upload File Gagal
Solusi:
1. Cek permission folder assets/cover/
2. chmod 755 assets/cover/
3. Cek ukuran file di php.ini

SUPPORT & KONTAK
Repository GitHub:
https://github.com/ranyrisqina/perpustakaan

Dokumentasi Lengkap:
•	http://localhost/perpustakaan/api/
