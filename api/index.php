<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> API Dokumentasi - Sistem Perpustakaan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #333;
            line-height: 1.6;
            min-height: 100vh;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: white;
            border-radius: 15px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        header h1 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        
        header p {
            color: #7f8c8d;
            font-size: 1.2em;
            margin-bottom: 20px;
        }
        
        .badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: bold;
            margin: 5px;
            font-size: 0.9em;
        }
        
        .badge.rest {
            background: #3498db;
            color: white;
        }
        
        .badge.php {
            background: #787cb5;
            color: white;
        }
        
        .badge.mysql {
            background: #00758f;
            color: white;
        }
        
        .endpoints {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .endpoint-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s ease;
        }
        
        .endpoint-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .method {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 5px;
            font-weight: bold;
            color: white;
            margin-bottom: 15px;
            font-size: 0.9em;
        }
        
        .method.get {
            background: #28a745;
        }
        
        .method.post {
            background: #007bff;
        }
        
        .method.put {
            background: #ffc107;
            color: #000;
        }
        
        .method.delete {
            background: #dc3545;
        }
        
        .endpoint-card h3 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-size: 1.3em;
        }
        
        .endpoint-card p {
            color: #666;
            margin-bottom: 15px;
        }
        
        .url {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            margin: 15px 0;
            border-left: 4px solid #3498db;
            word-break: break-all;
        }
        
        .code-block {
            background: #2d3436;
            color: #dfe6e9;
            padding: 15px;
            border-radius: 8px;
            margin: 10px 0;
            overflow-x: auto;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
        
        .test-section {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-top: 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        
        .test-section h2 {
            color: #2c3e50;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }
        
        .btn {
            display: inline-block;
            background: #3498db;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin: 10px 5px;
            transition: background 0.3s ease;
        }
        
        .btn:hover {
            background: #2980b9;
        }
        
        .btn.test {
            background: #2ecc71;
        }
        
        .btn.test:hover {
            background: #27ae60;
        }
        
        .btn.docs {
            background: #9b59b6;
        }
        
        .btn.docs:hover {
            background: #8e44ad;
        }
        
        footer {
            text-align: center;
            margin-top: 40px;
            color: white;
            padding: 20px;
            font-size: 0.9em;
        }
        
        @media (max-width: 768px) {
            .endpoints {
                grid-template-columns: 1fr;
            }
            
            header {
                padding: 25px;
            }
            
            header h1 {
                font-size: 2em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1><i class="fas fa-book"></i> RESTful API Dokumentasi</h1>
            <p>Sistem Manajemen Perpustakaan - Tugas Akhir</p>
            <div>
                <span class="badge rest"><i class="fas fa-code"></i> REST API</span>
                <span class="badge php"><i class="fab fa-php"></i> PHP</span>
                <span class="badge mysql"><i class="fas fa-database"></i> MySQL</span>
                <span class="badge github"><i class="fab fa-github"></i> GitHub</span>
            </div>
        </header>
        
        <div class="endpoints">
            <!-- Endpoint Buku -->
            <div class="endpoint-card">
                <span class="method get">GET</span>
                <h3>Mendapatkan Data Buku</h3>
                <p>Ambil semua data buku atau buku spesifik berdasarkan ID</p>
                <div class="url">
                    GET http://localhost/perpustakaan/api/buku<br>
                    GET http://localhost/perpustakaan/api/buku?id=1
                </div>
                <a href="http://localhost/perpustakaan/api/buku" target="_blank" class="btn test">
                    <i class="fas fa-play"></i> Test Endpoint
                </a>
            </div>
            
            <div class="endpoint-card">
                <span class="method post">POST</span>
                <h3>Menambah Buku Baru</h3>
                <p>Tambah buku baru ke dalam sistem perpustakaan</p>
                <div class="code-block">
{
  "judul_buku": "Belajar REST API",
  "pengarang": "John Doe",
  "penerbit": "PT. API Indonesia",
  "tahun_terbit": "2024",
  "isbn": "978-123-456-789",
  "jumlah": 10
}
                </div>
            </div>
            
            <!-- Endpoint Anggota -->
            <div class="endpoint-card">
                <span class="method get">GET</span>
                <h3>Data Anggota</h3>
                <p>Ambil data semua anggota perpustakaan</p>
                <div class="url">
                    GET http://localhost/perpustakaan/api/anggota
                </div>
                <a href="http://localhost/perpustakaan/api/anggota" target="_blank" class="btn test">
                    <i class="fas fa-play"></i> Test Endpoint
                </a>
            </div>
            
            <div class="endpoint-card">
                <span class="method get">GET</span>
                <h3>Data Peminjaman</h3>
                <p>Lihat riwayat peminjaman buku</p>
                <div class="url">
                    GET http://localhost/perpustakaan/api/peminjaman
                </div>
                <a href="http://localhost/perpustakaan/api/peminjaman" target="_blank" class="btn test">
                    <i class="fas fa-play"></i> Test Endpoint
                </a>
            </div>
            
            <!-- Endpoint Stats -->
            <div class="endpoint-card">
                <span class="method get">GET</span>
                <h3>Statistik Perpustakaan</h3>
                <p>Dapatkan statistik lengkap perpustakaan</p>
                <div class="url">
                    GET http://localhost/perpustakaan/api/stats
                </div>
                <a href="http://localhost/perpustakaan/api/stats" target="_blank" class="btn test">
                    <i class="fas fa-chart-bar"></i> Test Endpoint
                </a>
            </div>
            
            <!-- Endpoint Search -->
            <div class="endpoint-card">
                <span class="method get">GET</span>
                <h3>Pencarian Buku</h3>
                <p>Cari buku berdasarkan judul, pengarang, atau penerbit</p>
                <div class="url">
                    GET http://localhost/perpustakaan/api/search?q=php
                </div>
                <a href="http://localhost/perpustakaan/api/search?q=php" target="_blank" class="btn test">
                    <i class="fas fa-search"></i> Test Endpoint
                </a>
            </div>
        </div>
        
        <div class="test-section">
            <h2><i class="fas fa-vial"></i> Cara Testing API</h2>
            
            <h3>1. Menggunakan Browser</h3>
            <p>Buka URL endpoint di browser untuk melihat data JSON</p>
            
            <h3>2. Menggunakan cURL</h3>
            <div class="code-block">
# Get semua buku
curl -X GET "http://localhost/perpustakaan/api/buku"

# Tambah buku baru
curl -X POST "http://localhost/perpustakaan/api/buku" \
  -H "Content-Type: application/json" \
  -d '{"judul_buku":"Buku Baru","pengarang":"Penulis"}'
            </div>
            
            <h3>3. Menggunakan JavaScript (Fetch API)</h3>
            <div class="code-block">
// Get data buku
fetch('http://localhost/perpustakaan/api/buku')
  .then(response => response.json())
  .then(data => console.log(data))
  .catch(error => console.error('Error:', error));

// Post data buku
fetch('http://localhost/perpustakaan/api/buku', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    judul_buku: 'Buku JavaScript',
    pengarang: 'Jane Doe'
  })
})
  .then(response => response.json())
  .then(data => console.log(data));
            </div>
            
            <h3>4. Menggunakan Postman</h3>
            <p>Import Postman Collection dari file yang tersedia</p>
            
            <div style="margin-top: 25px;">
                <a href="api" target="_blank" class="btn docs">
                    <i class="fas fa-code"></i> Lihat Raw API
                </a>
                <a href="buku" target="_blank" class="btn">
                    <i class="fas fa-book"></i> Test: Get Buku
                </a>
                <a href="stats" target="_blank" class="btn">
                    <i class="fas fa-chart-pie"></i> Test: Get Stats
                </a>
            </div>
        </div>
        
        
 
    
    <footer>
        <p>© 2026 Sistem Perpustakaan - Elga Khusnia Maharani, Revalina Banowati Putri Sutomo, Rizqina Kautsar Rany,
             Delia Selvi Angie, Nabella Putri Kumalla </p>
        <p>Tugas Akhir </p>
    </footer>
</body>
</html>