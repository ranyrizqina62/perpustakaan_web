<?php
/**
 * RESTful API Perpustakaan - SIMPLE VERSION
 * Hanya 1 file, langsung jalan
 */

// ==================== KONFIGURASI ====================
header('Content-Type: application/json');

// ==================== KONEKSI DATABASE ====================
$koneksi = new mysqli("localhost", "root", "", "perpustakaan");

if ($koneksi->connect_error) {
    echo json_encode(["error" => "Koneksi gagal"]);
    exit;
}

// ==================== AMBIL PARAMETER ====================
$method = $_SERVER['REQUEST_METHOD'];
$endpoint = $_GET['endpoint'] ?? '';

// ==================== API ENDPOINTS ====================

// ENDPOINT 1: GET /api/buku
if ($endpoint == 'buku' && $method == 'GET') {
    $result = $koneksi->query("SELECT * FROM buku");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode([
        "status" => "success",
        "data" => $data
    ]);
}

// ENDPOINT 2: POST /api/buku
elseif ($endpoint == 'buku' && $method == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (empty($input['judul'])) {
        echo json_encode(["error" => "Judul harus diisi"]);
        exit;
    }
    
    $judul = $koneksi->real_escape_string($input['judul']);
    $pengarang = $koneksi->real_escape_string($input['pengarang'] ?? '');
    $penerbit = $koneksi->real_escape_string($input['penerbit'] ?? '');
    
    $sql = "INSERT INTO buku (judul, pengarang, penerbit) VALUES ('$judul', '$pengarang', '$penerbit')";
    
    if ($koneksi->query($sql)) {
        echo json_encode([
            "status" => "success",
            "message" => "Buku berhasil ditambahkan"
        ]);
    } else {
        echo json_encode(["error" => "Gagal: " . $koneksi->error]);
    }
}

// ENDPOINT 3: GET /api/anggota
elseif ($endpoint == 'anggota' && $method == 'GET') {
    $result = $koneksi->query("SELECT * FROM anggota");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode([
        "status" => "success",
        "data" => $data
    ]);
}

// ENDPOINT 4: GET /api/peminjaman
elseif ($endpoint == 'peminjaman' && $method == 'GET') {
    $result = $koneksi->query("SELECT * FROM peminjaman LIMIT 10");
    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode([
        "status" => "success",
        "data" => $data
    ]);
}

// ENDPOINT 5: GET /api/stats
elseif ($endpoint == 'stats' && $method == 'GET') {
    $buku = $koneksi->query("SELECT COUNT(*) as total FROM buku")->fetch_assoc();
    $anggota = $koneksi->query("SELECT COUNT(*) as total FROM anggota")->fetch_assoc();
    
    echo json_encode([
        "total_buku" => $buku['total'],
        "total_anggota" => $anggota['total']
    ]);
}

// DEFAULT: Info API
else {
    echo json_encode([
        "api_name" => "Perpustakaan API",
        "version" => "1.0",
        "endpoints" => [
            "GET /api/buku" => "Data buku",
            "POST /api/buku" => "Tambah buku",
            "GET /api/anggota" => "Data anggota",
            "GET /api/peminjaman" => "Data peminjaman",
            "GET /api/stats" => "Statistik"
        ],
        "usage" => "http://localhost/perpustakaan/api/buku"
    ]);
}

$koneksi->close();
?>