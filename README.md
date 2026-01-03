# SISTEM MANAJEMEN PERPUSTAKAAN

## 📌 INFORMASI PROYEK
- **Nama Sistem**: Perpustakaan Digital  
- **Versi**: 1.0  
- **Teknologi**: PHP, MySQL, Bootstrap 5  
- **Fitur**: CRUD, REST API, Laporan, Multi-user  

---

## 👥 ANGGOTA KELOMPOK
- Elga Khusnia Maharani (2413030078)
- Revalina Banowati Putri Sutomo (2413030080)
- Rizqina Kautsar Rany (2413030090)
- Delia Selvi Angie (2413030084)
- Nabella Putri Kumalla (2413030093)

---

## 🚀 FITUR UTAMA

### 📚 Manajemen Buku
- Tambah, Edit, Hapus, Lihat Buku  
- Upload Cover Buku  
- Pencarian Buku  
- Kategori dan ISBN  

### 👤 Manajemen Anggota
- Pendaftaran Anggota Baru  
- Update Data Anggota  
- Status Keanggotaan  
- Riwayat Peminjaman  

### 🔄 Sistem Peminjaman
- Peminjaman Buku  
- Pengembalian Buku  
- Denda Keterlambatan  
- Riwayat Peminjaman  

### 📊 Laporan
- Laporan Peminjaman Harian/Bulanan  
- Statistik Penggunaan  
- Export ke PDF/Excel  
- Dashboard Statistik  

### 🌐 RESTful API
- Endpoint: `/api/buku`, `/api/anggota`, `/api/peminjaman`  
- Format Response: JSON  
- Support: GET, POST, PUT, DELETE  
- CORS Enabled  

### 👥 Multi-user System
- **Admin**: Full Access  
- **Petugas**: Manage Peminjaman  
- **Anggota**: View Only  

---

## ⚙️ INSTALASI

### Persyaratan Sistem
- PHP 7.4 atau lebih tinggi  
- MySQL 5.7 atau lebih tinggi  
- Apache Web Server  
- mod_rewrite enabled  

### Langkah Instalasi

#### 1️⃣ Clone Repository
```bash
git clone https://github.com/ranyrizqina62/perpustakaan_web.git
cd perpustakaan
````

#### 2️⃣ Import Database

```bash
mysql -u root -p perpustakaan < database/db_perpustakaan.sql
```

#### 3️⃣ Konfigurasi Database

Edit file `inc/koneksi.php`:

```php
<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "db_perpustakaan";
?>
```

#### 4️⃣ Akses Aplikasi

```
http://localhost/perpustakaan/
```

**Login Default**

* Admin: `admin / admin123`
* Petugas: `petugas / petugas123`

---

## 📡 RESTful API DOKUMENTASI

### Base URL

```
http://localhost/perpustakaan/api/
```

### Endpoint

| Method | Endpoint      | Deskripsi       |
| ------ | ------------- | --------------- |
| GET    | /buku         | Get semua buku  |
| GET    | /buku?id=1    | Get buku by ID  |
| POST   | /buku         | Tambah buku     |
| PUT    | /buku?id=1    | Update buku     |
| DELETE | /buku?id=1    | Hapus buku      |
| GET    | /anggota      | Data anggota    |
| GET    | /peminjaman   | Data peminjaman |
| GET    | /stats        | Statistik       |
| GET    | /search?q=php | Pencarian buku  |

---

## 🔧 CONTOH PENGGUNAAN API

### JavaScript

```js
fetch('http://localhost/perpustakaan/api/buku')
  .then(res => res.json())
  .then(data => console.log(data));
```

### PHP

```php
<?php
$data = json_decode(file_get_contents(
'http://localhost/perpustakaan/api/buku'), true);
foreach ($data['data'] as $buku) {
  echo $buku['judul_buku'];
}
?>
```

### Python

```python
import requests
data = requests.get(
'http://localhost/perpustakaan/api/buku').json()
for buku in data['data']:
    print(buku['judul_buku'])
```

### cURL

```bash
curl -X GET http://localhost/perpustakaan/api/buku
```

---

## 🗄️ STRUKTUR DATABASE

### Tabel: buku

```sql
CREATE TABLE buku (
  id_buku INT AUTO_INCREMENT PRIMARY KEY,
  judul_buku VARCHAR(255),
  pengarang VARCHAR(100),
  penerbit VARCHAR(100),
  tahun_terbit YEAR,
  isbn VARCHAR(20),
  jumlah INT,
  lokasi VARCHAR(50),
  cover VARCHAR(255),
  tanggal_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Tabel: anggota

```sql
CREATE TABLE anggota (
  id_anggota INT AUTO_INCREMENT PRIMARY KEY,
  nama VARCHAR(100),
  alamat TEXT,
  telepon VARCHAR(15),
  email VARCHAR(100),
  tanggal_daftar DATE
);
```

### Tabel: peminjaman

```sql
CREATE TABLE peminjaman (
  id_peminjaman INT AUTO_INCREMENT PRIMARY KEY,
  id_buku INT,
  id_anggota INT,
  tanggal_pinjam DATE,
  tanggal_kembali DATE,
  status ENUM('Dipinjam','Dikembalikan')
);
```

---

## 🛠️ TROUBLESHOOTING

### API Tidak Berjalan

* Pastikan folder `api` ada
* Cek `.htaccess`
* Enable mod_rewrite

### Koneksi Database Error

* Cek `inc/koneksi.php`
* Pastikan MySQL running

### Upload File Gagal

* Permission folder `assets/cover/`
* Cek `php.ini`

---

## 📞 SUPPORT

Repository GitHub:
[https://github.com/ranyrizqina62/perpustakaan_web](https://github.com/ranyrizqina62/perpustakaan_web)

Dokumentasi API:
[http://localhost/perpustakaan/api/](http://localhost/perpustakaan/api/)

````


