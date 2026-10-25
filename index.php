<?php
$page_title = "Pangkalan Data & Registrasi Mahasiswa";
require_once __DIR__ . '/header.php';

// -------------------------------------------------------------
// 1. QUERY METRIK DASHBOARD UTAMA
// -------------------------------------------------------------
$total_mhs = 0;
$total_aktif = 0;
$total_l = 0;
$total_p = 0;
$total_prodi = 0;

$q_metric = mysqli_query($koneksi, "
    SELECT 
        COUNT(*) AS total,
        SUM(CASE WHEN status_akademik = 'Aktif' THEN 1 ELSE 0 END) AS aktif,
        SUM(CASE WHEN jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END) AS l,
        SUM(CASE WHEN jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END) AS p
    FROM mahasiswa 
    WHERE is_deleted = 0
");
if ($q_metric && $row_metric = mysqli_fetch_assoc($q_metric)) {
    $total_mhs = (int)$row_metric['total'];
    $total_aktif = (int)$row_metric['aktif'];
    $total_l = (int)$row_metric['l'];
    $total_p = (int)$row_metric['p'];
}

$q_prodi_count = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM prodi");
if ($q_prodi_count && $r_prodi = mysqli_fetch_assoc($q_prodi_count)) {
    $total_prodi = (int)$r_prodi['total'];
}

$pct_l = ($total_mhs > 0) ? round(($total_l / $total_mhs) * 100) : 50;
$pct_p = ($total_mhs > 0) ? (100 - $pct_l) : 50;
$pct_aktif = ($total_mhs > 0) ? round(($total_aktif / $total_mhs) * 100, 1) : 100;

// -------------------------------------------------------------
// 2. QUERY DAFTAR PRODI UNTUK FILTER DROPDOWN
// -------------------------------------------------------------
$prodi_options = [];
$res_prodi = mysqli_query($koneksi, "SELECT id, nama_prodi, fakultas FROM prodi ORDER BY nama_prodi ASC");
if ($res_prodi) {
    while ($row = mysqli_fetch_assoc($res_prodi)) {
        $prodi_options[] = $row;
    }
}
?>

<!-- ============================================================= -->
<!-- INTEGRASI PIHAK KETIGA VIA CDN (MILESTONE 4 - TANPA NPM) -->
<!-- ============================================================= -->
<!-- SheetJS (xlsx) CDN -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>

<style>
    /* Styling Custom Leaflet Popups agar sesuai tema Warm Editorial Stitch */
    .leaflet-popup-content-wrapper {
        border-radius: 14px;
        box-shadow: 0 10px 25px -5px rgba(24, 29, 38, 0.15);
        border: 1px solid #dee2ed;
        background: #ffffff;
        color: #171c23;
        padding: 4px;
    }
    .leaflet-popup-tip {
        background: #ffffff;
    }
    .custom-marker-pin {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #af3003;
        color: #ffffff;
        box-shadow: 0 4px 10px rgba(175, 48, 3, 0.4);
        border: 2px solid #ffffff;
        font-family: 'Space Grotesk', sans-serif;
        font-weight: bold;
        font-size: 11px;
    }
    .campus-marker-pin {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #181d26;
        color: #ffffff;
        box-shadow: 0 4px 12px rgba(24, 29, 38, 0.4);
        border: 2px solid #ffffff;
    }
</style>

<div class="max-w-[1240px] mx-auto px-margin py-space-xl">
    <div class="flex flex-col w-full space-y-space-lg font-body-md text-on-surface">

        <!-- 1. Header Breadcrumb, Page Title & Quick Actions -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-space-md pb-space-xs">
            <div class="space-y-space-xs">
                <nav class="flex items-center gap-space-xs font-badge-label text-badge-label text-on-surface-variant uppercase tracking-wider">
                    <a href="index.php" class="hover:text-primary transition-colors">Beranda</a>
                    <span class="text-outline-variant">/</span>
                    <span>Master Data</span>
                    <span class="text-outline-variant">/</span>
                    <span class="text-primary font-bold">Direktori Biodata Mahasiswa</span>
                </nav>
                <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight font-black">
                    Pangkalan Data &amp; Registrasi Mahasiswa
                </h1>
                <p class="font-body-md text-body-md text-on-surface-variant max-w-2xl">
                    Manajemen arsip biodata, verifikasi berkas, pemetaan Leaflet GIS, dan penerbitan KTM terintegrasi.
                </p>
            </div>

            <div class="flex items-center gap-space-sm shrink-0">
                <div class="flex items-center gap-space-xs bg-surface-container-lowest px-space-md py-space-xs rounded-full shadow-sm text-on-surface border border-outline-variant/30">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="font-label-field text-label-field tracking-wide">Leaflet &bull; SheetJS &bull; QR Code Ready</span>
                </div>
                <a href="tambah.php" class="bg-primary text-on-primary px-space-md py-space-sm rounded-xl font-label-field text-label-field hover:bg-primary-container transition-all flex items-center gap-1.5 shadow-sm active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">add_circle</span>
                    <span>Registrasi Baru</span>
                </a>
            </div>
        </div>

        <!-- 2. Dashboard Metrics Row (4 Color-coded Accent Cards) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-space-md">
            
            <!-- Metric 1: Total Mahasiswa -->
            <div class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm relative overflow-hidden flex flex-col justify-between group hover:shadow-md transition-shadow border border-outline-variant/20">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-secondary"></div>
                <div class="flex items-center justify-between mb-space-sm">
                    <span class="font-label-field text-label-field uppercase tracking-wider text-on-surface-variant">Total Terdaftar</span>
                    <span class="p-1.5 rounded-lg bg-surface-container text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">groups</span>
                    </span>
                </div>
                <div>
                    <div class="flex items-baseline gap-space-xs">
                        <span class="font-headline-lg text-headline-lg text-primary font-black tracking-tight" id="metric-total-mhs"><?= number_format($total_mhs, 0, ',', '.') ?></span>
                        <span class="font-label-field text-label-field text-secondary font-bold">Mahasiswa</span>
                    </div>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">Arsip aktif dalam pangkalan data</span>
                </div>
                <div class="mt-space-md pt-space-xs border-t border-outline-variant/20 flex items-center justify-between text-body-sm text-on-surface-variant">
                    <span>Basis Data Terarsip</span>
                    <span class="font-code-nim text-code-nim text-primary">DB-OK</span>
                </div>
            </div>

            <!-- Metric 2: Mahasiswa Aktif -->
            <div class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm relative overflow-hidden flex flex-col justify-between group hover:shadow-md transition-shadow border border-outline-variant/20">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-tertiary"></div>
                <div class="flex items-center justify-between mb-space-sm">
                    <span class="font-label-field text-label-field uppercase tracking-wider text-on-surface-variant">Mahasiswa Aktif</span>
                    <span class="p-1.5 rounded-lg bg-surface-container text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">how_to_reg</span>
                    </span>
                </div>
                <div>
                    <div class="flex items-baseline gap-space-xs">
                        <span class="font-headline-lg text-headline-lg text-primary font-black tracking-tight"><?= number_format($total_aktif, 0, ',', '.') ?></span>
                        <span class="font-badge-label text-badge-label bg-emerald-50 text-emerald-800 px-2 py-0.5 rounded-full font-semibold"><?= $pct_aktif ?>% Aktif</span>
                    </div>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">Status KRS &amp; Registrasi Berjalan</span>
                </div>
                <div class="mt-space-md pt-space-xs border-t border-outline-variant/20 flex items-center justify-between text-body-sm text-on-surface-variant">
                    <span>Validasi Akademik</span>
                    <span class="text-secondary font-semibold flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span> Siap Verifikasi
                    </span>
                </div>
            </div>

            <!-- Metric 3: Rasio Gender -->
            <div class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm relative overflow-hidden flex flex-col justify-between group hover:shadow-md transition-shadow border border-outline-variant/20">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-on-tertiary-container"></div>
                <div class="flex items-center justify-between mb-space-sm">
                    <span class="font-label-field text-label-field uppercase tracking-wider text-on-surface-variant">Rasio Gender</span>
                    <span class="p-1.5 rounded-lg bg-surface-container text-on-tertiary-container flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">wc</span>
                    </span>
                </div>
                <div>
                    <div class="flex items-baseline justify-between mb-1">
                        <span class="font-headline-md text-headline-md text-primary font-bold"><?= $pct_l ?>% L</span>
                        <span class="font-headline-md text-headline-md text-on-surface-variant font-bold"><?= $pct_p ?>% P</span>
                    </div>
                    <div class="w-full h-2 rounded-full bg-surface-container overflow-hidden flex">
                        <div class="h-full bg-on-tertiary-container transition-all" style="width: <?= $pct_l ?>%;"></div>
                        <div class="h-full bg-secondary-container transition-all" style="width: <?= $pct_p ?>%;"></div>
                    </div>
                </div>
                <div class="mt-space-md pt-space-xs border-t border-outline-variant/20 flex items-center justify-between text-body-sm text-on-surface-variant">
                    <span><?= $total_l ?> Laki-laki</span>
                    <span><?= $total_p ?> Perempuan</span>
                </div>
            </div>

            <!-- Metric 4: Program Studi Terdaftar -->
            <div class="bg-surface-container-lowest p-space-lg rounded-xl shadow-sm relative overflow-hidden flex flex-col justify-between group hover:shadow-md transition-shadow border border-outline-variant/20">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-secondary-container"></div>
                <div class="flex items-center justify-between mb-space-sm">
                    <span class="font-label-field text-label-field uppercase tracking-wider text-on-surface-variant">Program Studi</span>
                    <span class="p-1.5 rounded-lg bg-surface-container text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">school</span>
                    </span>
                </div>
                <div>
                    <div class="flex items-baseline gap-space-xs">
                        <span class="font-headline-lg text-headline-lg text-primary font-black tracking-tight"><?= $total_prodi ?></span>
                        <span class="font-label-field text-label-field text-secondary-container font-bold">Prodi Terakreditasi</span>
                    </div>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">Fakultas Ilmu Komputer (FIKOM)</span>
                </div>
                <div class="mt-space-md pt-space-xs border-t border-outline-variant/20 flex items-center justify-between text-body-sm text-on-surface-variant">
                    <span>Tahun Akademik</span>
                    <span class="font-code-nim text-code-nim text-primary">2024/2025</span>
                </div>
            </div>

        </div>

        <!-- ============================================================= -->
        <!-- 3. PEMETAAN GEOSPASIAL SEBARAN MAHASISWA (LEAFLET.JS GIS CARD) -->
        <!-- ============================================================= -->
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-space-lg space-y-space-md border border-outline-variant/20">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-md pb-space-sm border-b border-outline-variant/20">
                <div class="flex items-center gap-space-sm">
                    <div class="w-10 h-10 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center shadow-inner">
                        <span class="material-symbols-outlined text-[24px]">explore</span>
                    </div>
                    <div class="flex flex-col">
                        <div class="flex items-center gap-space-sm">
                            <h2 class="font-headline-sm text-headline-sm text-primary font-bold">Peta Sebaran Domisili Mahasiswa</h2>
                            <span id="map-plotted-count" class="font-badge-label text-badge-label bg-secondary-fixed text-on-secondary-fixed px-2.5 py-0.5 rounded-full font-bold uppercase">
                                Memuat Titik...
                            </span>
                        </div>
                        <span class="font-body-sm text-body-sm text-on-surface-variant">Visualisasi spasial GIS berbasis koordinat latitude &amp; longitude OpenStreetMap</span>
                    </div>
                </div>

                <!-- Kontrol Peta Cepat -->
                <div class="flex items-center gap-2 flex-wrap">
                    <button id="btn-map-fit" type="button" class="px-3.5 py-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary font-label-field text-xs font-bold transition-all inline-flex items-center gap-1.5 shadow-sm cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">fit_screen</span>
                        <span>Fokus Semua Titik</span>
                    </button>
                    <button id="btn-map-campus" type="button" class="px-3.5 py-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary font-label-field text-xs font-bold transition-all inline-flex items-center gap-1.5 shadow-sm cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">account_balance</span>
                        <span>Pusatkan Kampus UDB</span>
                    </button>
                </div>
            </div>

            <!-- Map Container -->
            <div class="relative w-full h-[400px] rounded-xl overflow-hidden shadow-inner border border-outline-variant/30">
                <div id="map-mahasiswa" class="w-full h-full z-10"></div>
                
                <!-- Floating Legend -->
                <div class="absolute bottom-4 left-4 z-20 bg-surface-container-lowest/95 backdrop-blur-md border border-outline-variant/30 p-3 rounded-xl shadow-lg text-xs space-y-1.5 hidden sm:block">
                    <p class="font-bold text-primary font-headline-sm text-[11px] uppercase tracking-wider">Legenda Peta Spasial</p>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-secondary inline-block"></span>
                        <span class="text-on-surface">Titik Domisili Mahasiswa</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-primary inline-block"></span>
                        <span class="text-on-surface">Kampus Universitas Duta Bangsa</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ============================================================= -->
        <!-- 4. MAIN RELATIONAL DATA REGISTRY CARD & ACTIONS -->
        <!-- ============================================================= -->
        <div class="bg-surface-container-lowest rounded-2xl shadow-sm p-space-lg space-y-space-md border border-outline-variant/20">
            
            <!-- Card Toolbar Header with SheetJS Excel Export -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-md pb-space-sm border-b border-outline-variant/20">
                <div class="flex items-center gap-space-sm">
                    <h2 class="font-headline-sm text-headline-sm text-primary font-bold">Direktori Biodata Terdaftar</h2>
                    <span id="badge-total-records" class="font-badge-label text-badge-label bg-surface-container px-2.5 py-1 rounded-full text-on-surface-variant font-bold uppercase transition-all">
                        Memuat data...
                    </span>
                </div>

                <div class="flex items-center gap-space-sm flex-wrap">
                    <!-- Tombol Ekspor Excel (SheetJS via CDN) -->
                    <button id="btn-export-excel" type="button" class="bg-surface-container-lowest px-4 py-2.5 rounded-xl text-primary font-label-field text-label-field hover:bg-surface-container transition-all flex items-center gap-1.5 shadow-sm border border-outline-variant/30 active:scale-95 cursor-pointer">
                        <span class="material-symbols-outlined text-[18px] text-emerald-600">table_view</span>
                        <span>Ekspor Excel (.xlsx)</span>
                    </button>

                    <a href="tambah.php" class="bg-primary text-on-primary px-5 py-2.5 rounded-xl font-label-field text-label-field hover:bg-primary-container transition-all flex items-center gap-2 shadow-sm active:scale-95">
                        <span class="material-symbols-outlined text-[18px]">add_circle</span>
                        <span>+ Tambah Data Mahasiswa</span>
                    </a>
                </div>
            </div>

            <!-- Interactive AJAX Filter Bar -->
            <div class="bg-surface-container p-space-md rounded-xl flex flex-wrap items-center justify-between gap-space-md">
                <div class="flex flex-wrap items-center gap-space-sm flex-1">
                    
                    <!-- Search Input with Live Spinner -->
                    <div class="relative min-w-[240px] flex-1 max-w-xs">
                        <span id="search-icon" class="material-symbols-outlined absolute left-3 top-2.5 text-on-surface-variant text-[18px] pointer-events-none transition-transform">search</span>
                        <input 
                            id="table-search-input" 
                            type="text"
                            class="w-full bg-surface-container-lowest pl-9 pr-8 py-2 rounded-lg font-body-sm text-body-sm text-on-surface placeholder:text-on-surface-variant/70 focus:outline-none focus:ring-1 focus:ring-primary shadow-sm" 
                            placeholder="Cari nama, NIM, atau domisili..." 
                        />
                        <button id="search-clear-btn" type="button" class="absolute right-2.5 top-2.5 text-on-surface-variant hover:text-primary hidden">
                            <span class="material-symbols-outlined text-[16px]">cancel</span>
                        </button>
                    </div>

                    <!-- Prodi Filter Select -->
                    <select id="filter-prodi" class="bg-surface-container-lowest px-3 py-2 rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none shadow-sm cursor-pointer border-0">
                        <option value="0">Semua Program Studi</option>
                        <?php foreach ($prodi_options as $po): ?>
                            <option value="<?= $po['id'] ?>">
                                <?= htmlspecialchars($po['nama_prodi']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <!-- Status Filter Select -->
                    <select id="filter-status" class="bg-surface-container-lowest px-3 py-2 rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none shadow-sm cursor-pointer border-0">
                        <option value="">Semua Status Akademik</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Cuti">Cuti</option>
                        <option value="Lulus">Lulus</option>
                        <option value="DO">DO / Drop Out</option>
                    </select>

                    <!-- Limit per page -->
                    <select id="filter-limit" class="bg-surface-container-lowest px-3 py-2 rounded-lg font-body-sm text-body-sm text-on-surface focus:outline-none shadow-sm cursor-pointer border-0">
                        <option value="5" selected>5 per hal</option>
                        <option value="10">10 per hal</option>
                        <option value="25">25 per hal</option>
                    </select>
                </div>

                <button id="btn-reset-filters" type="button" class="font-label-field text-label-field text-secondary hover:underline flex items-center gap-1 cursor-pointer">
                    <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                    <span>Reset Filter</span>
                </button>
            </div>

            <!-- Relational Data Table Container -->
            <div class="overflow-x-auto rounded-xl border border-outline-variant/30 shadow-sm relative min-h-[220px]">
                
                <!-- Loading Overlay Indicator -->
                <div id="table-loading-overlay" class="absolute inset-0 bg-surface-container-lowest/70 backdrop-blur-[1px] flex items-center justify-center z-10 hidden transition-opacity">
                    <div class="flex items-center gap-2 bg-surface-container-lowest px-4 py-2 rounded-xl shadow-md border border-outline-variant/40">
                        <span class="material-symbols-outlined text-secondary animate-spin text-[20px]">progress_activity</span>
                        <span class="font-label-field text-primary text-xs font-bold">Memuat data dari server...</span>
                    </div>
                </div>

                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container text-on-surface-variant font-label-field text-[11px] uppercase tracking-wider">
                            <th class="py-3 px-4">Mahasiswa</th>
                            <th class="py-3 px-4">NIM</th>
                            <th class="py-3 px-4">Program Studi &amp; Fakultas</th>
                            <th class="py-3 px-4">Angkatan</th>
                            <th class="py-3 px-4">Status</th>
                            <th class="py-3 px-4">Kelengkapan Arsip</th>
                            <th class="py-3 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-mahasiswa-body" class="divide-y divide-outline-variant/10 text-body-sm">
                        <!-- Konten dimuat secara dinamis via Fetch API (api_ambil_data.php) -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination Footer Matching Reference 1:1 -->
            <div id="pagination-container" class="flex flex-col sm:flex-row sm:items-center justify-between gap-space-md pt-space-sm border-t border-outline-variant/20 font-body-sm text-body-sm text-on-surface-variant">
                <div>
                    Menampilkan <span class="font-bold text-primary" id="page-showing">0 - 0</span> dari <span class="font-bold text-primary" id="page-total">0</span> mahasiswa terdaftar
                </div>

                <div class="flex items-center gap-space-xs">
                    <!-- Tombol Sebelumnya -->
                    <button id="btn-prev-page" type="button" class="px-3 py-1.5 rounded-lg bg-surface-container text-primary hover:bg-surface-container-high transition-colors disabled:opacity-40 disabled:cursor-not-allowed font-label-field text-label-field flex items-center gap-1 cursor-pointer">
                        <span class="material-symbols-outlined text-[16px]">chevron_left</span> 
                        <span>Sebelumnya</span>
                    </button>

                    <!-- Nomor Halaman Dinamis -->
                    <div id="pagination-pages" class="flex items-center gap-1">
                        <!-- Nomor halaman di-render oleh JS -->
                    </div>

                    <!-- Tombol Berikutnya -->
                    <button id="btn-next-page" type="button" class="px-3 py-1.5 rounded-lg bg-surface-container text-primary hover:bg-surface-container-high transition-colors disabled:opacity-40 disabled:cursor-not-allowed font-label-field text-label-field flex items-center gap-1 cursor-pointer">
                        <span>Berikutnya</span> 
                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    </button>
                </div>
            </div>

            <!-- Footer summary note -->
            <div class="flex flex-col sm:flex-row items-center justify-between text-body-sm text-on-surface-variant pt-space-xs text-xs">
                <span>Filter aktif mengecualikan data soft-delete (<code class="font-code-nim">is_deleted = 0</code>).</span>
                <span class="font-code-nim">Leaflet.js &bull; SheetJS &bull; Fetch API &bull; MySQLi</span>
            </div>

        </div>

    </div>
</div>

<!-- ============================================================================== -->
<!-- SCRIPT MILESTONE 3 & 4: FETCH API, LEAFLET GIS MAP, & SHEETJS EXCEL EXPORT -->
<!-- ============================================================================== -->
<script>
(function() {
    // 1. State Manajer Aplikasi
    const state = {
        search: '',
        prodi: 0,
        status: '',
        limit: 5,
        page: 1,
        totalPages: 1,
        totalRecords: 0,
        isLoading: false,
        csrfToken: '<?= $csrf_token ?>',
        allPlottedStudents: []
    };

    // 2. DOM Elements
    const searchInput = document.getElementById('table-search-input');
    const headerSearchInput = document.getElementById('header-search-input');
    const searchClearBtn = document.getElementById('search-clear-btn');
    const prodiSelect = document.getElementById('filter-prodi');
    const statusSelect = document.getElementById('filter-status');
    const limitSelect = document.getElementById('filter-limit');
    const resetBtn = document.getElementById('btn-reset-filters');
    const btnExportExcel = document.getElementById('btn-export-excel');

    const tableBody = document.getElementById('tabel-mahasiswa-body');
    const loadingOverlay = document.getElementById('table-loading-overlay');
    const badgeTotal = document.getElementById('badge-total-records');
    const pageShowing = document.getElementById('page-showing');
    const pageTotal = document.getElementById('page-total');
    const paginationPages = document.getElementById('pagination-pages');
    const btnPrev = document.getElementById('btn-prev-page');
    const btnNext = document.getElementById('btn-next-page');

    const mapPlottedCount = document.getElementById('map-plotted-count');
    const btnMapFit = document.getElementById('btn-map-fit');
    const btnMapCampus = document.getElementById('btn-map-campus');

    // Helper Fungsi Sanitasi XSS
    function escapeHtml(str) {
        if (!str) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    // =============================================================
    // 3. INISIALISASI PETA LEAFLET.JS (MILESTONE 4)
    // =============================================================
    let leafletMap = null;
    let markersLayer = null;
    const campusCoords = [-7.5581, 110.8271]; // Kampus UDB Surakarta

    function initLeafletMap() {
        const mapContainer = document.getElementById('map-mahasiswa');
        if (!mapContainer || typeof L === 'undefined') return;

        leafletMap = L.map('map-mahasiswa', {
            center: campusCoords,
            zoom: 13,
            zoomControl: true
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(leafletMap);

        // Marker Kampus Utama
        const campusIcon = L.divIcon({
            className: '',
            html: '<div class="campus-marker-pin"><span class="material-symbols-outlined text-[20px]">account_balance</span></div>',
            iconSize: [36, 36],
            iconAnchor: [18, 18],
            popupAnchor: [0, -18]
        });

        L.marker(campusCoords, { icon: campusIcon })
            .addTo(leafletMap)
            .bindPopup(`
                <div style="font-family: Inter, sans-serif; min-width: 200px; padding: 4px;">
                    <span style="font-size: 10px; font-weight: bold; color: #af3003; text-transform: uppercase;">Kampus Utama</span>
                    <strong style="font-size: 13px; color: #181d26; display: block; font-family: 'Space Grotesk', sans-serif; margin-top: 2px;">Universitas Duta Bangsa</strong>
                    <p style="margin: 4px 0 0 0; font-size: 11px; color: #45474b;">Jl. Bhayangkara No. 55, Surakarta, Jawa Tengah</p>
                </div>
            `);

        markersLayer = L.featureGroup().addTo(leafletMap);

        if (btnMapFit) {
            btnMapFit.addEventListener('click', () => {
                if (markersLayer && markersLayer.getLayers().length > 0) {
                    leafletMap.fitBounds(markersLayer.getBounds().pad(0.15));
                }
            });
        }

        if (btnMapCampus) {
            btnMapCampus.addEventListener('click', () => {
                leafletMap.flyTo(campusCoords, 14, { duration: 1.2 });
            });
        }
    }

    // Update marker posisi mahasiswa di Leaflet map
    async function updateMapMarkers() {
        if (!leafletMap || !markersLayer) return;

        try {
            // Ambil seluruh data mahasiswa yang cocok dengan filter aktif
            const params = new URLSearchParams({
                all: '1',
                search: state.search,
                id_prodi: state.prodi,
                status: state.status
            });

            const res = await fetch('api_ambil_data.php?' + params.toString());
            const json = await res.json();
            const students = json.data || [];

            markersLayer.clearLayers();

            const plotted = [];
            students.forEach((m, idx) => {
                const lat = parseFloat(m.latitude);
                const lng = parseFloat(m.longitude);

                if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
                    plotted.push(m);

                    const markerHtml = `
                        <div class="custom-marker-pin">
                            ${m.nim ? escapeHtml(m.nim.slice(-2)) : (idx + 1)}
                        </div>
                    `;

                    const customIcon = L.divIcon({
                        className: '',
                        html: markerHtml,
                        iconSize: [32, 32],
                        iconAnchor: [16, 16],
                        popupAnchor: [0, -16]
                    });

                    const popupContent = `
                        <div style="font-family: Inter, sans-serif; min-width: 230px; padding: 4px;">
                            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                                <img src="${escapeHtml(m.avatar_url)}" style="width: 38px; height: 38px; border-radius: 8px; object-fit: cover; border: 1px solid #dee2ed;" onerror="this.src='${escapeHtml(m.fallback_avatar)}'" />
                                <div style="min-width: 0;">
                                    <strong style="font-size: 13px; color: #171c23; display: block; font-family: 'Space Grotesk', sans-serif; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">${escapeHtml(m.nama)}</strong>
                                    <span style="font-family: 'Space Mono', monospace; font-size: 11px; font-weight: bold; color: #af3003;">#${escapeHtml(m.nim)}</span>
                                </div>
                            </div>
                            <p style="margin: 0 0 4px 0; font-size: 11px; color: #45474b;">
                                <strong>${escapeHtml(m.nama_prodi)}</strong> &bull; Angkatan ${escapeHtml(m.angkatan || '2026')}
                            </p>
                            <p style="margin: 0 0 8px 0; font-size: 11px; color: #76777c; line-height: 1.4;">
                                📍 ${escapeHtml(m.alamat)}
                            </p>
                            <div style="display: flex; gap: 6px; border-top: 1px solid #eaeef8; padding-top: 6px;">
                                <a href="cetak_ktm.php?id=${m.id}" style="font-size: 10px; font-weight: bold; padding: 4px 8px; background: #181d26; color: #ffffff; text-decoration: none; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                    <span>Cetak KTM</span>
                                </a>
                                <a href="edit.php?id=${m.id}" style="font-size: 10px; font-weight: bold; padding: 4px 8px; background: #eff4fe; color: #171c23; text-decoration: none; border-radius: 6px;">
                                    <span>Edit</span>
                                </a>
                            </div>
                        </div>
                    `;

                    L.marker([lat, lng], { icon: customIcon })
                        .addTo(markersLayer)
                        .bindPopup(popupContent);
                }
            });

            if (mapPlottedCount) {
                mapPlottedCount.textContent = `${plotted.length} Titik Terpetakan`;
            }

            if (plotted.length > 0) {
                markersLayer.addLayer(L.marker(campusCoords, { opacity: 0 })); // Include campus in bounds
                leafletMap.fitBounds(markersLayer.getBounds().pad(0.15));
            }
        } catch (err) {
            console.error('Failed to update map markers:', err);
        }
    }

    // =============================================================
    // 4. EKSPOR EXCEL MENGGUNAKAN SHEETJS VIA CDN (MILESTONE 4)
    // =============================================================
    async function exportExcel() {
        if (!btnExportExcel || typeof XLSX === 'undefined') return;

        const originalHtml = btnExportExcel.innerHTML;
        btnExportExcel.innerHTML = `
            <span class="material-symbols-outlined text-[18px] animate-spin text-emerald-600">progress_activity</span>
            <span>Mengekspor...</span>
        `;
        btnExportExcel.disabled = true;

        try {
            // Ambil seluruh data mahasiswa sesuai filter yang sedang aktif
            const params = new URLSearchParams({
                all: '1',
                search: state.search,
                id_prodi: state.prodi,
                status: state.status
            });

            const res = await fetch('api_ambil_data.php?' + params.toString());
            const json = await res.json();
            const records = json.data || [];

            if (records.length === 0) {
                alert('Tidak ada data mahasiswa yang dapat diekspor!');
                return;
            }

            // Susun kolom-kolom spreadsheet yang informatif & rapi
            const excelRows = records.map((m, idx) => ({
                'No': idx + 1,
                'NIM': m.nim,
                'Nama Lengkap': m.nama,
                'Jenis Kelamin': m.jenis_kelamin,
                'Program Studi': m.nama_prodi,
                'Fakultas': m.fakultas,
                'Angkatan': m.angkatan || 2026,
                'Status Akademik': m.status_akademik,
                'Dosen Penasihat (DPA)': m.dpa || 'Dr. Muhtarom, M.Kom',
                'Email Institusi': m.email,
                'Nomor Telepon / WA': m.no_hp,
                'Alamat Domisili Sesuai KTP': m.alamat,
                'Latitude': m.latitude ? parseFloat(m.latitude) : '',
                'Longitude': m.longitude ? parseFloat(m.longitude) : '',
                'Status Pasfoto': m.has_foto ? 'Tersedia' : 'Belum Ada',
                'Status Dokumen PDF': m.has_doc ? 'Tersedia' : 'Belum Ada',
                'Tanggal Registrasi': m.created_at
            }));

            // Buat Worksheet & Workbook SheetJS
            const ws = XLSX.utils.json_to_sheet(excelRows);

            // Tentukan lebar kolom yang optimal
            ws['!cols'] = [
                { wch: 6 },   // No
                { wch: 15 },  // NIM
                { wch: 26 },  // Nama
                { wch: 14 },  // Jenis Kelamin
                { wch: 25 },  // Prodi
                { wch: 25 },  // Fakultas
                { wch: 10 },  // Angkatan
                { wch: 15 },  // Status
                { wch: 24 },  // DPA
                { wch: 28 },  // Email
                { wch: 18 },  // No. HP
                { wch: 38 },  // Alamat
                { wch: 14 },  // Lat
                { wch: 14 },  // Lng
                { wch: 16 },  // Foto
                { wch: 18 },  // Dokumen
                { wch: 20 }   // Tanggal
            ];

            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Data Mahasiswa");

            const today = new Date().toISOString().slice(0, 10);
            XLSX.writeFile(wb, `Data_Mahasiswa_FIKOM_UDB_${today}.xlsx`);
        } catch (err) {
            console.error('Export error:', err);
            alert('Terjadi kesalahan saat memproses ekspor Excel: ' + err.message);
        } finally {
            btnExportExcel.innerHTML = originalHtml;
            btnExportExcel.disabled = false;
        }
    }

    if (btnExportExcel) {
        btnExportExcel.addEventListener('click', exportExcel);
    }

    // =============================================================
    // 5. FETCH API: AMBIL DATA MAHASISWA & RENDER TABEL
    // =============================================================
    async function loadMahasiswa() {
        if (state.isLoading) return;
        state.isLoading = true;
        if (loadingOverlay) loadingOverlay.classList.remove('hidden');

        try {
            const params = new URLSearchParams({
                search: state.search,
                id_prodi: state.prodi,
                status: state.status,
                limit: state.limit,
                page: state.page
            });

            const res = await fetch('api_ambil_data.php?' + params.toString(), {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });

            if (!res.ok) {
                throw new Error('Gagal mengambil data dari server (Status ' + res.status + ')');
            }

            const data = await res.json();
            if (data.status === 'success') {
                if (data.csrf_token) {
                    state.csrfToken = data.csrf_token;
                }
                state.totalRecords = data.pagination.total_records;
                state.totalPages = data.pagination.total_pages;
                state.page = data.pagination.current_page;

                renderTable(data.data);
                renderPagination(data.pagination);
                updateMapMarkers();
            } else {
                renderError('Gagal memuat data mahasiswa.');
            }
        } catch (err) {
            console.error('Fetch error:', err);
            renderError('Terjadi kesalahan jaringan atau server MySQL.');
        } finally {
            state.isLoading = false;
            if (loadingOverlay) loadingOverlay.classList.add('hidden');
        }
    }

    // Render Baris Tabel
    function renderTable(mahasiswaList) {
        if (!tableBody) return;

        if (mahasiswaList.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="7" class="py-12 text-center bg-surface-container-lowest">
                        <div class="max-w-sm mx-auto flex flex-col items-center gap-3">
                            <div class="w-12 h-12 rounded-full bg-surface-container text-on-surface-variant flex items-center justify-center">
                                <span class="material-symbols-outlined text-[28px]">search_off</span>
                            </div>
                            <div>
                                <h4 class="font-headline-sm text-primary font-bold">Tidak Ada Data Ditemukan</h4>
                                <p class="text-body-sm text-on-surface-variant text-sm mt-1">
                                    Tidak ada data mahasiswa yang cocok dengan filter atau kata kunci pencarian.
                                </p>
                            </div>
                            <button type="button" onclick="document.getElementById('btn-reset-filters').click()" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-label-field text-sm hover:bg-primary-container transition-all inline-flex items-center gap-1.5 cursor-pointer">
                                <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                                <span>Reset Filter</span>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        let html = '';
        mahasiswaList.forEach(m => {
            const namaEsc = escapeHtml(m.nama);
            const nimEsc = escapeHtml(m.nim);
            const emailEsc = escapeHtml(m.email);
            const prodiEsc = escapeHtml(m.nama_prodi);
            const fakultasEsc = escapeHtml(m.fakultas);
            const angkatanEsc = escapeHtml(m.angkatan || '2024');
            const jkEsc = escapeHtml(m.jenis_kelamin);
            const statusEsc = escapeHtml(m.status_akademik || 'Aktif');

            // Status Badge
            let statusBadgeHtml = '';
            if (statusEsc === 'Aktif') {
                statusBadgeHtml = `
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span> Aktif
                    </span>
                `;
            } else if (statusEsc === 'Cuti') {
                statusBadgeHtml = `
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-600"></span> Cuti
                    </span>
                `;
            } else if (statusEsc === 'Lulus') {
                statusBadgeHtml = `
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-blue-50 text-blue-800">
                        <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span> Lulus
                    </span>
                `;
            } else {
                statusBadgeHtml = `
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[11px] font-bold bg-error-container text-on-error-container">
                        <span class="w-1.5 h-1.5 rounded-full bg-error"></span> DO
                    </span>
                `;
            }

            // Kelengkapan Berkas
            let arsipHtml = '';
            if (m.has_foto && m.has_doc) {
                arsipHtml = `
                    <span class="font-badge-label text-badge-label text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px] text-emerald-600">verified</span> Lengkap
                    </span>
                `;
            } else if (m.has_foto || m.has_doc) {
                arsipHtml = `
                    <span class="font-badge-label text-badge-label text-amber-800 bg-amber-50 px-2 py-0.5 rounded font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px] text-amber-600">timelapse</span> Sebagian
                    </span>
                `;
            } else {
                arsipHtml = `
                    <span class="font-badge-label text-badge-label text-on-surface-variant bg-surface-container px-2 py-0.5 rounded font-bold flex items-center gap-1">
                        <span class="material-symbols-outlined text-[14px]">warning</span> Belum Ada
                    </span>
                `;
            }

            if (m.has_doc && m.dokumen_url) {
                arsipHtml += `
                    <a href="${escapeHtml(m.dokumen_url)}" target="_blank" class="p-1 rounded bg-surface-container text-secondary hover:text-primary transition-colors" title="Lihat Berkas PDF">
                        <span class="material-symbols-outlined text-[15px]">picture_as_pdf</span>
                    </a>
                `;
            }

            html += `
                <tr class="hover:bg-surface-container-low transition-colors group">
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-space-sm">
                            <img 
                                class="w-10 h-10 rounded-full object-cover shrink-0 shadow-sm border border-outline-variant/40" 
                                src="${escapeHtml(m.avatar_url)}" 
                                alt="${namaEsc}"
                                onerror="this.src='${escapeHtml(m.fallback_avatar)}'"
                            />
                            <div class="flex flex-col min-w-0">
                                <span class="font-label-field text-label-field text-primary group-hover:text-secondary transition-colors font-bold truncate">
                                    ${namaEsc}
                                </span>
                                <span class="text-[11px] text-on-surface-variant truncate">
                                    ${emailEsc}
                                </span>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4 font-code-nim text-code-nim text-primary font-bold">
                        ${nimEsc}
                    </td>
                    <td class="py-3 px-4">
                        <span class="font-semibold text-primary block">${prodiEsc}</span>
                        <span class="text-[11px] text-on-surface-variant">${fakultasEsc}</span>
                    </td>
                    <td class="py-3 px-4">
                        <span class="font-label-field text-label-field text-on-surface">${angkatanEsc}</span>
                        <span class="text-[11px] text-on-surface-variant block">${jkEsc}</span>
                    </td>
                    <td class="py-3 px-4">
                        ${statusBadgeHtml}
                    </td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-2 flex-wrap">
                            ${arsipHtml}
                        </div>
                    </td>
                    <td class="py-3 px-4 text-right">
                        <div class="inline-flex items-center gap-1">
                            <!-- Tombol Cetak KTM (Milestone 4) -->
                            <a 
                                href="cetak_ktm.php?id=${m.id}" 
                                class="p-1.5 rounded-lg hover:bg-surface-container text-secondary transition-colors cursor-pointer" 
                                title="Cetak Kartu Tanda Mahasiswa (KTM) & QR Code"
                            >
                                <span class="material-symbols-outlined text-[18px]">badge</span>
                            </a>

                            <!-- Tombol Edit -->
                            <a 
                                href="edit.php?id=${m.id}" 
                                class="p-1.5 rounded-lg hover:bg-surface-container text-on-surface-variant hover:text-primary transition-colors cursor-pointer" 
                                title="Edit Biodata"
                            >
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>

                            <!-- Tombol Soft Delete -->
                            <button 
                                type="button" 
                                onclick="openDeleteModal('${m.id}', '${namaEsc.replace(/'/g, "\\'")}', '${nimEsc}', '${state.csrfToken}')" 
                                class="p-1.5 rounded-lg hover:bg-error-container text-error transition-colors cursor-pointer" 
                                title="Nonaktifkan (Soft Delete)"
                            >
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        });

        tableBody.innerHTML = html;
    }

    // Render Pagination Controls
    function renderPagination(pg) {
        if (badgeTotal) {
            badgeTotal.textContent = `${pg.total_records} Mahasiswa Terdaftar`;
        }
        if (pageShowing) {
            pageShowing.textContent = `${pg.showing_from} - ${pg.showing_to}`;
        }
        if (pageTotal) {
            pageTotal.textContent = pg.total_records;
        }

        if (btnPrev) {
            btnPrev.disabled = (pg.current_page <= 1);
        }
        if (btnNext) {
            btnNext.disabled = (pg.current_page >= pg.total_pages || pg.total_pages === 0);
        }

        if (!paginationPages) return;
        let pagesHtml = '';
        const current = pg.current_page;
        const total = pg.total_pages;

        if (total <= 7) {
            for (let i = 1; i <= total; i++) {
                pagesHtml += makePageBtn(i, i === current);
            }
        } else {
            pagesHtml += makePageBtn(1, current === 1);
            if (current > 3) {
                pagesHtml += `<span class="text-on-surface-variant px-1 font-bold text-xs">&hellip;</span>`;
            }
            const start = Math.max(2, current - 1);
            const end = Math.min(total - 1, current + 1);
            for (let i = start; i <= end; i++) {
                pagesHtml += makePageBtn(i, i === current);
            }
            if (current < total - 2) {
                pagesHtml += `<span class="text-on-surface-variant px-1 font-bold text-xs">&hellip;</span>`;
            }
            pagesHtml += makePageBtn(total, current === total);
        }

        paginationPages.innerHTML = pagesHtml;
    }

    function makePageBtn(num, isActive) {
        if (isActive) {
            return `<span class="w-8 h-8 rounded-lg bg-primary text-on-primary font-bold flex items-center justify-center font-code-nim text-code-nim text-xs shadow-sm">${num}</span>`;
        }
        return `<button type="button" onclick="window.goToPage(${num})" class="w-8 h-8 rounded-lg hover:bg-surface-container text-on-surface font-semibold flex items-center justify-center font-code-nim text-code-nim text-xs cursor-pointer transition-colors">${num}</button>`;
    }

    function renderError(msg) {
        if (!tableBody) return;
        tableBody.innerHTML = `
            <tr>
                <td colspan="7" class="py-8 text-center text-error font-body-md text-sm">
                    <div class="flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">warning</span>
                        <span>${escapeHtml(msg)}</span>
                    </div>
                </td>
            </tr>
        `;
    }

    // Global Pagination Handler
    window.goToPage = function(p) {
        if (p < 1 || p > state.totalPages || p === state.page) return;
        state.page = p;
        loadMahasiswa();
    };

    if (btnPrev) {
        btnPrev.addEventListener('click', () => {
            if (state.page > 1) {
                state.page--;
                loadMahasiswa();
            }
        });
    }

    if (btnNext) {
        btnNext.addEventListener('click', () => {
            if (state.page < state.totalPages) {
                state.page++;
                loadMahasiswa();
            }
        });
    }

    // Event Listeners Filter & Search
    let searchTimer = null;
    function handleSearchInput(val) {
        state.search = val;
        state.page = 1;
        if (searchClearBtn) {
            if (val.length > 0) searchClearBtn.classList.remove('hidden');
            else searchClearBtn.classList.add('hidden');
        }
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            loadMahasiswa();
        }, 250);
    }

    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            handleSearchInput(e.target.value.trim());
        });
    }

    if (headerSearchInput) {
        headerSearchInput.addEventListener('input', (e) => {
            const val = e.target.value.trim();
            if (searchInput) searchInput.value = val;
            handleSearchInput(val);
        });
    }

    if (searchClearBtn) {
        searchClearBtn.addEventListener('click', () => {
            if (searchInput) searchInput.value = '';
            if (headerSearchInput) headerSearchInput.value = '';
            handleSearchInput('');
        });
    }

    if (prodiSelect) {
        prodiSelect.addEventListener('change', (e) => {
            state.prodi = parseInt(e.target.value, 10) || 0;
            state.page = 1;
            loadMahasiswa();
        });
    }

    if (statusSelect) {
        statusSelect.addEventListener('change', (e) => {
            state.status = e.target.value;
            state.page = 1;
            loadMahasiswa();
        });
    }

    if (limitSelect) {
        limitSelect.addEventListener('change', (e) => {
            state.limit = parseInt(e.target.value, 10) || 5;
            state.page = 1;
            loadMahasiswa();
        });
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', () => {
            if (searchInput) searchInput.value = '';
            if (headerSearchInput) headerSearchInput.value = '';
            if (searchClearBtn) searchClearBtn.classList.add('hidden');
            if (prodiSelect) prodiSelect.value = '0';
            if (statusSelect) statusSelect.value = '';
            if (limitSelect) limitSelect.value = '5';

            state.search = '';
            state.prodi = 0;
            state.status = '';
            state.limit = 5;
            state.page = 1;
            loadMahasiswa();
        });
    }

    // Inisialisasi awal saat halaman siap
    function startup() {
        initLeafletMap();
        loadMahasiswa();
    }

    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        startup();
    } else {
        document.addEventListener('DOMContentLoaded', startup);
    }
})();
</script>

<?php require_once __DIR__ . '/footer.php'; ?>
