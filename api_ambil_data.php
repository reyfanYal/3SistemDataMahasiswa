<?php
// ==============================================================================
// ADVANCED UI AJAX ENDPOINT: api_ambil_data.php
// Live Search, Cross-Filter (Prodi & Status), & Server-Side Pagination
// Framework: Native PHP, MySQLi Prepared Statement
// ==============================================================================

require_once __DIR__ . '/koneksi.php';

header('Content-Type: application/json; charset=utf-8');

// 1. Sanitasi & Pengambilan Parameter Filter
$search = trim($_GET['search'] ?? $_GET['q'] ?? '');
$id_prodi = isset($_GET['id_prodi']) ? (int)$_GET['id_prodi'] : (isset($_GET['prodi']) ? (int)$_GET['prodi'] : 0);
$status = trim($_GET['status'] ?? '');
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = isset($_GET['limit']) ? min(1000, max(1, (int)$_GET['limit'])) : 5;
if (isset($_GET['all']) && $_GET['all'] == '1') {
    $limit = 1000;
}
$offset = ($page - 1) * $limit;

// 2. Bangun Klausa WHERE dengan Prepared Statement
$where_clauses = ["m.is_deleted = 0"];
$params = [];
$types = "";

if ($search !== '') {
    $where_clauses[] = "(m.nama LIKE ? OR m.nim LIKE ? OR m.email LIKE ? OR m.alamat LIKE ? OR m.no_hp LIKE ?)";
    $like = "%" . $search . "%";
    for ($i = 0; $i < 5; $i++) {
        $params[] = $like;
        $types .= "s";
    }
}

if ($id_prodi > 0) {
    $where_clauses[] = "m.id_prodi = ?";
    $params[] = $id_prodi;
    $types .= "i";
}

if ($status !== '' && in_array($status, ['Aktif', 'Cuti', 'Lulus', 'DO'])) {
    $where_clauses[] = "m.status_akademik = ?";
    $params[] = $status;
    $types .= "s";
}

$where_sql = implode(" AND ", $where_clauses);

// 3. Hitung Total Data (Count Query) dengan Prepared Statement
$count_sql = "SELECT COUNT(*) as total FROM mahasiswa m WHERE {$where_sql}";
$stmt_count = $koneksi->prepare($count_sql);
if (!empty($params)) {
    $stmt_count->bind_param($types, ...$params);
}
$stmt_count->execute();
$count_res = $stmt_count->get_result();
$total_records = (int)($count_res->fetch_assoc()['total'] ?? 0);
$stmt_count->close();

$total_pages = $total_records > 0 ? (int)ceil($total_records / $limit) : 1;
if ($page > $total_pages && $total_records > 0) {
    $page = $total_pages;
    $offset = ($page - 1) * $limit;
}

// 4. Ambil Record Halaman Ini dengan Prepared Statement
$data_sql = "SELECT m.*, p.nama_prodi, p.fakultas 
             FROM mahasiswa m 
             LEFT JOIN prodi p ON m.id_prodi = p.id 
             WHERE {$where_sql} 
             ORDER BY m.id DESC 
             LIMIT ? OFFSET ?";

$data_types = $types . "ii";
$data_params = array_merge($params, [$limit, $offset]);

$stmt_data = $koneksi->prepare($data_sql);
$stmt_data->bind_param($data_types, ...$data_params);
$stmt_data->execute();
$result = $stmt_data->get_result();

$mahasiswa_list = [];
while ($row = $result->fetch_assoc()) {
    $has_foto = !empty($row['foto']) && file_exists(__DIR__ . '/uploads/foto/' . $row['foto']);
    $has_doc = !empty($row['dokumen']) && file_exists(__DIR__ . '/uploads/dokumen/' . $row['dokumen']);

    $row['avatar_url'] = get_mhs_avatar($row['nama'], $row['jenis_kelamin'], $row['nim'], $row['foto']);
    $row['fallback_avatar'] = generate_avatar_svg($row['nama'], $row['jenis_kelamin'], $row['nim']);
    $row['has_foto'] = $has_foto;
    $row['has_doc'] = $has_doc;
    $row['dokumen_url'] = $has_doc ? 'uploads/dokumen/' . rawurlencode($row['dokumen']) : null;
    $row['nama_prodi'] = $row['nama_prodi'] ?? 'Belum Ditentukan';
    $row['fakultas'] = $row['fakultas'] ?? 'Fakultas Ilmu Komputer';

    $mahasiswa_list[] = $row;
}
$stmt_data->close();

// 5. Output Respons JSON Terstruktur
$response = [
    'status' => 'success',
    'data' => $mahasiswa_list,
    'pagination' => [
        'total_records' => $total_records,
        'total_pages' => $total_pages,
        'current_page' => $page,
        'limit' => $limit,
        'offset' => $offset,
        'showing_from' => $total_records > 0 ? $offset + 1 : 0,
        'showing_to' => min($offset + $limit, $total_records),
    ],
    'csrf_token' => generate_csrf_token()
];

echo json_encode($response, JSON_UNESCAPED_UNICODE);
exit;

