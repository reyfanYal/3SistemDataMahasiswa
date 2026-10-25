<?php
// ==============================================================================
// RESTful API ENDPOINT MAHASISWA (MILESTONE 5)
// Direktori: api/mahasiswa.php
// Arsitektur: Native PHP, MySQLi Prepared Statement, Pure JSON Output
// ==============================================================================

require_once dirname(__DIR__) . '/koneksi.php';

// 1. Set Header HTTP untuk Output JSON Murni & CORS Support
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

// Tangani Preflight Request CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Hanya perbolehkan metode HTTP GET untuk pembacaan data
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'code' => 405,
        'message' => 'Metode HTTP tidak diizinkan. Endpoint ini hanya mendukung metode GET.'
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}

// 2. Fungsi Helper Format Entitas Mahasiswa
function format_mahasiswa_record($row) {
    $has_foto = !empty($row['foto']) && file_exists(dirname(__DIR__) . '/uploads/foto/' . $row['foto']);
    $has_doc = !empty($row['dokumen']) && file_exists(dirname(__DIR__) . '/uploads/dokumen/' . $row['dokumen']);

    // Tentukan Base URL dinamis
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
    $script_dir = dirname(dirname($_SERVER['SCRIPT_NAME']));
    $base_app_url = rtrim($protocol . $host . $script_dir, '/\\') . '/';

    return [
        'id' => (int)$row['id'],
        'nim' => $row['nim'],
        'nama' => $row['nama'],
        'jenis_kelamin' => $row['jenis_kelamin'],
        'prodi' => [
            'id' => (int)$row['id_prodi'],
            'nama_prodi' => $row['nama_prodi'] ?? 'Belum Ditentukan',
            'fakultas' => $row['fakultas'] ?? 'Fakultas Ilmu Komputer'
        ],
        'angkatan' => (int)($row['angkatan'] ?? 2026),
        'status_akademik' => $row['status_akademik'] ?? 'Aktif',
        'dpa' => $row['dpa'] ?? 'Dr. Muhtarom, M.Kom',
        'kontak' => [
            'email' => $row['email'],
            'no_hp' => $row['no_hp'],
            'alamat' => $row['alamat']
        ],
        'geospasial' => [
            'latitude' => $row['latitude'] !== null ? (float)$row['latitude'] : null,
            'longitude' => $row['longitude'] !== null ? (float)$row['longitude'] : null
        ],
        'berkas' => [
            'foto' => $row['foto'],
            'foto_url' => $has_foto ? $base_app_url . 'uploads/foto/' . rawurlencode($row['foto']) : null,
            'avatar_url' => get_mhs_avatar($row['nama'], $row['jenis_kelamin'], $row['nim'], $row['foto']),
            'dokumen' => $row['dokumen'],
            'dokumen_url' => $has_doc ? $base_app_url . 'uploads/dokumen/' . rawurlencode($row['dokumen']) : null,
            'is_lengkap' => ($has_foto && $has_doc)
        ],
        'is_deleted' => false,
        'created_at' => $row['created_at'],
        'updated_at' => $row['updated_at']
    ];
}

// -------------------------------------------------------------
// KASUS A: PENCARIAN TUNGGAL BY ID (`?id=X`)
// -------------------------------------------------------------
if (isset($_GET['id']) && trim($_GET['id']) !== '') {
    $id = (int)$_GET['id'];
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'code' => 400,
            'message' => 'Parameter ID mahasiswa tidak valid.'
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    $stmt = $koneksi->prepare("SELECT m.*, p.nama_prodi, p.fakultas 
                               FROM mahasiswa m 
                               LEFT JOIN prodi p ON m.id_prodi = p.id 
                               WHERE m.id = ? AND m.is_deleted = 0 
                               LIMIT 1");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if ($row) {
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'code' => 200,
            'data' => format_mahasiswa_record($row)
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } else {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'code' => 404,
            'message' => "Mahasiswa dengan ID #{$id} tidak ditemukan atau telah dinonaktifkan."
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    exit;
}

// -------------------------------------------------------------
// KASUS B: PENCARIAN TUNGGAL BY NIM (`?nim=X`)
// -------------------------------------------------------------
if (isset($_GET['nim']) && trim($_GET['nim']) !== '') {
    $nim = trim($_GET['nim']);

    $stmt = $koneksi->prepare("SELECT m.*, p.nama_prodi, p.fakultas 
                               FROM mahasiswa m 
                               LEFT JOIN prodi p ON m.id_prodi = p.id 
                               WHERE LOWER(m.nim) = LOWER(?) AND m.is_deleted = 0 
                               LIMIT 1");
    $stmt->bind_param("s", $nim);
    $stmt->execute();
    $res = $stmt->get_result();
    $row = $res->fetch_assoc();
    $stmt->close();

    if ($row) {
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'code' => 200,
            'data' => format_mahasiswa_record($row)
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    } else {
        http_response_code(404);
        echo json_encode([
            'status' => 'error',
            'code' => 404,
            'message' => "Mahasiswa dengan NIM '{$nim}' tidak ditemukan atau telah dinonaktifkan."
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    exit;
}

// -------------------------------------------------------------
// KASUS C: AMBIL DATA SELURUH MAHASISWA AKTIF (`is_deleted = 0`)
// -------------------------------------------------------------
$where_clauses = ["m.is_deleted = 0"];
$params = [];
$types = "";

// Filter Tambahan Opsional (Search, Prodi, Status)
$search = trim($_GET['search'] ?? $_GET['q'] ?? '');
if ($search !== '') {
    $where_clauses[] = "(m.nama LIKE ? OR m.nim LIKE ? OR m.email LIKE ? OR m.alamat LIKE ? OR m.no_hp LIKE ?)";
    $like = "%" . $search . "%";
    for ($i = 0; $i < 5; $i++) {
        $params[] = $like;
        $types .= "s";
    }
}

$id_prodi = isset($_GET['prodi']) ? (int)$_GET['prodi'] : (isset($_GET['id_prodi']) ? (int)$_GET['id_prodi'] : 0);
if ($id_prodi > 0) {
    $where_clauses[] = "m.id_prodi = ?";
    $params[] = $id_prodi;
    $types .= "i";
}

$status = trim($_GET['status'] ?? '');
if ($status !== '' && in_array($status, ['Aktif', 'Cuti', 'Lulus', 'DO'])) {
    $where_clauses[] = "m.status_akademik = ?";
    $params[] = $status;
    $types .= "s";
}

$where_sql = implode(" AND ", $where_clauses);
$sql = "SELECT m.*, p.nama_prodi, p.fakultas 
        FROM mahasiswa m 
        LEFT JOIN prodi p ON m.id_prodi = p.id 
        WHERE {$where_sql} 
        ORDER BY m.id DESC";

$stmt = $koneksi->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$res = $stmt->get_result();

$mahasiswa_list = [];
while ($row = $res->fetch_assoc()) {
    $mahasiswa_list[] = format_mahasiswa_record($row);
}
$stmt->close();

// 3. Render Respons JSON Murni
http_response_code(200);
echo json_encode([
    'status' => 'success',
    'code' => 200,
    'endpoint' => 'API Sistem Informasi Biodata Mahasiswa Advance',
    'version' => '1.0',
    'total' => count($mahasiswa_list),
    'data' => $mahasiswa_list,
    'timestamp' => date('c')
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
exit;

