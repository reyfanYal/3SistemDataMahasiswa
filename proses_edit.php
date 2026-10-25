<?php
require_once __DIR__ . '/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

// 1. Verifikasi Keamanan CSRF Token
verify_csrf_token($_POST['csrf_token'] ?? '');

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    set_flash('error', 'ID Mahasiswa tidak valid.');
    header("Location: index.php");
    exit;
}

// 2. Ambil Record Eksisting untuk Cek Validitas
$stmt_cur = $koneksi->prepare("SELECT * FROM mahasiswa WHERE id = ? AND is_deleted = 0 LIMIT 1");
$stmt_cur->bind_param("i", $id);
$stmt_cur->execute();
$current = $stmt_cur->get_result()->fetch_assoc();
$stmt_cur->close();

if (!$current) {
    set_flash('error', 'Data mahasiswa tidak ditemukan atau telah dinonaktifkan.');
    header("Location: index.php");
    exit;
}

// 3. Sanitasi Input
$nim = trim($_POST['nim'] ?? '');
$nama = trim($_POST['nama'] ?? '');
$id_prodi = (int)($_POST['id_prodi'] ?? 0);
$jenis_kelamin = in_array($_POST['jenis_kelamin'] ?? '', ['Laki-laki', 'Perempuan']) ? $_POST['jenis_kelamin'] : 'Laki-laki';
$angkatan = (int)($_POST['angkatan'] ?? date('Y'));
$status_akademik = in_array($_POST['status_akademik'] ?? '', ['Aktif', 'Cuti', 'Lulus', 'DO']) ? $_POST['status_akademik'] : 'Aktif';
$dpa = trim($_POST['dpa'] ?? 'Dr. Muhtarom, M.Kom');
$email = trim($_POST['email'] ?? '');
$no_hp = trim($_POST['no_hp'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');

$lat_raw = trim($_POST['latitude'] ?? '');
$lng_raw = trim($_POST['longitude'] ?? '');
$latitude = ($lat_raw !== '') ? (float)$lat_raw : null;
$longitude = ($lng_raw !== '') ? (float)$lng_raw : null;

if (empty($nim) || empty($nama) || empty($id_prodi) || empty($email) || empty($no_hp) || empty($alamat)) {
    set_flash('error', 'Semua kolom yang bertanda bintang (*) wajib diisi.');
    header("Location: edit.php?id={$id}");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    set_flash('error', 'Format alamat email tidak valid.');
    header("Location: edit.php?id={$id}");
    exit;
}

// 4. Cek Keunikan NIM Terhadap Record Lain
$stmt_dup = $koneksi->prepare("SELECT id, nama FROM mahasiswa WHERE LOWER(nim) = LOWER(?) AND id != ? LIMIT 1");
$stmt_dup->bind_param("si", $nim, $id);
$stmt_dup->execute();
$res_dup = $stmt_dup->get_result();
if ($dup = $res_dup->fetch_assoc()) {
    set_flash('error', "NIM {$nim} telah digunakan oleh mahasiswa lain (" . htmlspecialchars($dup['nama']) . ").");
    $stmt_dup->close();
    header("Location: edit.php?id={$id}");
    exit;
}
$stmt_dup->close();

// 5. Penanganan Penggantian Pasfoto (JPG/PNG maks 2MB, Verifikasi MIME Biner)
$foto_final = $current['foto'];
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $mime = get_file_binary_mime($_FILES['foto']['tmp_name']);
    $allowed_mimes = ['image/jpeg', 'image/png'];
    $file_ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));

    if (!$mime || !in_array($mime, $allowed_mimes) || !in_array($file_ext, ['jpg', 'jpeg', 'png'])) {
        set_flash('error', 'Format pasfoto baru harus berupa berkas JPG atau PNG dengan struktur biner valid.');
        header("Location: edit.php?id={$id}");
        exit;
    }

    if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
        set_flash('error', 'Ukuran file pasfoto baru melebihi batas maksimal 2 MB.');
        header("Location: edit.php?id={$id}");
        exit;
    }

    $upload_foto_dir = __DIR__ . '/uploads/foto/';
    if (!is_dir($upload_foto_dir)) {
        mkdir($upload_foto_dir, 0755, true);
    }

    $safe_foto = 'foto_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $file_ext;
    if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_foto_dir . $safe_foto)) {
        // Hapus file foto lama jika ada
        if (!empty($current['foto']) && file_exists($upload_foto_dir . $current['foto'])) {
            @unlink($upload_foto_dir . $current['foto']);
        }
        $foto_final = $safe_foto;
    }
}

// 6. Penanganan Penggantian Dokumen Berkas PDF (Maks 5MB, Verifikasi MIME Biner)
$dokumen_final = $current['dokumen'];
if (isset($_FILES['dokumen']) && $_FILES['dokumen']['error'] === UPLOAD_ERR_OK) {
    $mime = get_file_binary_mime($_FILES['dokumen']['tmp_name']);
    $file_ext = strtolower(pathinfo($_FILES['dokumen']['name'], PATHINFO_EXTENSION));

    if (!$mime || $mime !== 'application/pdf' || $file_ext !== 'pdf') {
        set_flash('error', 'Format dokumen berkas akademik harus berupa berkas PDF asli dengan header biner valid.');
        header("Location: edit.php?id={$id}");
        exit;
    }

    if ($_FILES['dokumen']['size'] > 5 * 1024 * 1024) {
        set_flash('error', 'Ukuran file dokumen PDF baru melebihi batas maksimal 5 MB.');
        header("Location: edit.php?id={$id}");
        exit;
    }

    $upload_doc_dir = __DIR__ . '/uploads/dokumen/';
    if (!is_dir($upload_doc_dir)) {
        mkdir($upload_doc_dir, 0755, true);
    }

    $safe_doc = 'doc_' . time() . '_' . bin2hex(random_bytes(4)) . '.pdf';
    if (move_uploaded_file($_FILES['dokumen']['tmp_name'], $upload_doc_dir . $safe_doc)) {
        // Hapus file dokumen lama jika ada
        if (!empty($current['dokumen']) && file_exists($upload_doc_dir . $current['dokumen'])) {
            @unlink($upload_doc_dir . $current['dokumen']);
        }
        $dokumen_final = $safe_doc;
    }
}

// 7. Eksekusi UPDATE dengan Prepared Statement
$update_sql = "UPDATE mahasiswa SET 
    nim = ?, nama = ?, id_prodi = ?, jenis_kelamin = ?, angkatan = ?, status_akademik = ?, dpa = ?, email = ?, no_hp = ?, alamat = ?, latitude = ?, longitude = ?, foto = ?, dokumen = ?
    WHERE id = ?";

$stmt_up = $koneksi->prepare($update_sql);
$stmt_up->bind_param(
    "ssisisssssddssi",
    $nim,
    $nama,
    $id_prodi,
    $jenis_kelamin,
    $angkatan,
    $status_akademik,
    $dpa,
    $email,
    $no_hp,
    $alamat,
    $latitude,
    $longitude,
    $foto_final,
    $dokumen_final,
    $id
);

if ($stmt_up->execute()) {
    // 8. Catat Riwayat Aktivitas ke audit_logs
    write_audit_log(
        $koneksi,
        1, // user_id admin
        'UPDATE',
        'mahasiswa',
        $id,
        "Memperbarui biodata mahasiswa {$nama} (NIM: {$nim})"
    );

    set_flash('success', "Biodata mahasiswa {$nama} (NIM: {$nim}) berhasil diperbarui.");
    $stmt_up->close();
    header("Location: index.php");
    exit;
} else {
    set_flash('error', "Gagal memperbarui database: " . $stmt_up->error);
    $stmt_up->close();
    header("Location: edit.php?id={$id}");
    exit;
}

