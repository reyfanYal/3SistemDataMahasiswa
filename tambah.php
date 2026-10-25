<?php
$page_title = "Registrasi Biodata Mahasiswa Baru";
require_once __DIR__ . '/header.php';

// Ambil daftar program studi dari database
$prodi_list = [];
$res_prodi = mysqli_query($koneksi, "SELECT id, nama_prodi, fakultas FROM prodi ORDER BY nama_prodi ASC");
if ($res_prodi) {
    while ($row = mysqli_fetch_assoc($res_prodi)) {
        $prodi_list[] = $row;
    }
}
?>

<div class="max-w-[1440px] mx-auto w-full px-margin py-space-lg flex flex-col gap-space-lg pb-36">

    <!-- Top Editorial Header & Breadcrumb Ribbon -->
    <div class="flex flex-col gap-space-sm pb-space-xs">
        <!-- Breadcrumb Bar -->
        <nav class="flex items-center gap-space-xs font-badge-label text-badge-label text-on-surface-variant uppercase tracking-wider">
            <a class="hover:text-primary transition-colors" href="index.php">Beranda</a>
            <span class="opacity-40">/</span>
            <a class="hover:text-primary transition-colors" href="index.php">Direktori</a>
            <span class="opacity-40">/</span>
            <span class="text-primary font-bold">Registrasi Biodata Baru</span>
        </nav>

        <!-- Main Header Row -->
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-space-md mt-space-xs">
            <div class="space-y-space-xs max-w-3xl">
                <div class="flex items-center gap-space-sm flex-wrap">
                    <span class="bg-primary text-on-primary font-code-nim text-code-nim px-space-sm py-0.5 rounded">REG-2024-ONLINE</span>
                    <span class="bg-surface-container font-badge-label text-badge-label text-on-surface-variant px-space-sm py-0.5 rounded-full flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-secondary"></span>
                        Formulir Registri Aktif
                    </span>
                </div>
                <h1 class="font-headline-lg text-headline-lg text-primary tracking-tight font-black">
                    Registrasi Biodata &amp; Verifikasi Berkas Mahasiswa
                </h1>
                <p class="font-body-md text-body-md text-on-surface-variant leading-relaxed">
                    Lengkapi identitas diri, informasi kurikulum, kontak domisili, dan unggah pasfoto serta berkas PDF terverifikasi.
                </p>
            </div>

            <!-- Quick Top Utility Action -->
            <div class="flex items-center gap-space-sm shrink-0 self-start lg:self-end">
                <a href="index.php" class="inline-flex items-center gap-space-xs bg-surface-container-lowest text-primary font-headline-sm text-[13px] px-space-md py-2.5 rounded-xl shadow-sm hover:bg-surface-container transition-all border border-outline-variant/30">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span>Kembali ke Direktori</span>
                </a>
            </div>
        </div>

        <!-- Stage & Progress Ribbon -->
        <div class="bg-surface-container-lowest rounded-xl p-space-md shadow-sm flex flex-col md:flex-row items-center justify-between gap-space-md mt-space-xs border border-outline-variant/20">
            <div class="flex items-center gap-space-md w-full md:w-auto">
                <div class="w-10 h-10 rounded-xl bg-primary-container text-on-primary flex items-center justify-center font-code-nim text-code-nim font-bold">
                    01
                </div>
                <div class="flex flex-col">
                    <span class="font-headline-sm text-[15px] text-primary font-bold">Milestone 1 &amp; 2: Fondasi Identitas &amp; CRUD Berkas</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant">Prepared Statement • CSRF Secured • Verifikasi MIME Biner</span>
                </div>
            </div>
            <div class="flex items-center gap-space-md w-full md:w-80">
                <div class="flex-1 bg-surface-container rounded-full h-2 overflow-hidden">
                    <div id="form-progress-bar" class="bg-secondary-container h-full rounded-full transition-all duration-300" style="width: 25%"></div>
                </div>
                <span id="form-progress-text" class="font-code-nim text-code-nim text-primary font-bold shrink-0">25% Terisi</span>
            </div>
        </div>
    </div>

    <!-- MAIN FORM WRAPPER -->
    <form id="form-mahasiswa" action="proses_tambah.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

        <!-- Master-Detail 2-Column Work Surface -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-space-lg items-start">
            
            <!-- LEFT / MAIN DOSSIER FORM (Col 8) -->
            <div class="lg:col-span-8 flex flex-col gap-space-lg">

                <!-- SEKSI 1: IDENTITAS DIRI & KEPEGAWAIAN/SIPIL -->
                <section class="bg-surface-container-lowest rounded-2xl p-space-lg md:p-space-xl shadow-sm relative overflow-hidden border border-outline-variant/20">
                    <div class="absolute top-0 left-0 w-2 h-full bg-primary"></div>
                    
                    <div class="flex items-center justify-between gap-space-md pb-space-md mb-space-lg border-b border-outline-variant/10">
                        <div class="flex items-center gap-space-sm">
                            <span class="material-symbols-outlined text-primary text-[24px]">badge</span>
                            <div class="flex flex-col">
                                <span class="font-badge-label text-badge-label text-on-surface-variant uppercase tracking-wider">Arsip Administrasi Sipil</span>
                                <h2 class="font-headline-md text-headline-md text-primary font-bold">Seksi 1: Identitas Diri &amp; Kependudukan</h2>
                            </div>
                        </div>
                        <span class="font-badge-label text-badge-label bg-surface-container-low text-primary px-space-sm py-1 rounded-full uppercase tracking-wider font-bold">Wajib Diisi *</span>
                    </div>

                    <div class="space-y-space-md">
                        <!-- Row 1: Nama Lengkap & NIM -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-space-md">
                            <div class="md:col-span-8 flex flex-col gap-1.5">
                                <label class="font-label-field text-label-field text-on-surface-variant flex items-center justify-between">
                                    <span>Nama Lengkap Mahasiswa <span class="text-secondary">*</span></span>
                                    <span class="text-[11px] font-normal text-on-surface-variant/70">Sesuai akta/ijazah resmi</span>
                                </label>
                                <input 
                                    id="input-nama" 
                                    name="nama" 
                                    required 
                                    class="w-full bg-surface-container-low px-4 py-3 rounded-xl font-body-md text-body-md text-on-surface font-semibold focus:outline-none focus:ring-1 focus:ring-primary shadow-inner border border-outline-variant/30" 
                                    placeholder="Masukkan nama lengkap tanpa singkatan" 
                                    type="text"
                                />
                            </div>

                            <div class="md:col-span-4 flex flex-col gap-1.5">
                                <label class="font-label-field text-label-field text-on-surface-variant flex items-center justify-between">
                                    <span>Nomor Induk Mahasiswa (NIM) <span class="text-secondary">*</span></span>
                                    <span class="font-badge-label text-[10px] bg-secondary-fixed text-on-secondary-fixed px-1.5 py-0.2 rounded font-bold">Unik</span>
                                </label>
                                <input 
                                    id="input-nim" 
                                    name="nim" 
                                    required 
                                    class="w-full bg-surface-container-low px-4 py-3 rounded-xl font-code-nim text-code-nim text-primary font-bold focus:outline-none focus:ring-1 focus:ring-primary shadow-inner border border-outline-variant/30" 
                                    placeholder="Contoh: 2024091001" 
                                    type="text"
                                />
                            </div>
                        </div>

                        <!-- Row 2: Jenis Kelamin & Angkatan -->
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-space-md pt-space-xs">
                            <!-- Jenis Kelamin Radio Pills -->
                            <div class="md:col-span-6 flex flex-col gap-1.5">
                                <label class="font-label-field text-label-field text-on-surface-variant">
                                    Jenis Kelamin <span class="text-secondary">*</span>
                                </label>
                                <div class="grid grid-cols-2 gap-space-xs bg-surface-container p-1 rounded-xl">
                                    <label id="label-gender-l" class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg bg-surface-container-lowest text-primary shadow-sm font-label-field text-label-field cursor-pointer transition-all">
                                        <input checked class="hidden" name="jenis_kelamin" type="radio" value="Laki-laki" onchange="updateGenderStyle(this)"/>
                                        <span class="material-symbols-outlined text-[16px] text-secondary">male</span>
                                        <span>Laki-Laki</span>
                                    </label>
                                    <label id="label-gender-p" class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg text-on-surface-variant hover:text-primary transition-all font-label-field text-label-field cursor-pointer">
                                        <input class="hidden" name="jenis_kelamin" type="radio" value="Perempuan" onchange="updateGenderStyle(this)"/>
                                        <span class="material-symbols-outlined text-[16px]">female</span>
                                        <span>Perempuan</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Tahun Angkatan -->
                            <div class="md:col-span-6 flex flex-col gap-1.5">
                                <label class="font-label-field text-label-field text-on-surface-variant">
                                    Tahun Angkatan Masuk <span class="text-secondary">*</span>
                                </label>
                                <input 
                                    name="angkatan" 
                                    type="number" 
                                    value="2026" 
                                    min="2018" 
                                    max="2030" 
                                    class="w-full bg-surface-container-low px-4 py-3 rounded-xl font-code-nim text-code-nim text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-inner border border-outline-variant/30"
                                />
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SEKSI 2: INFORMASI AKADEMIK & PROGRAM STUDI -->
                <section class="bg-surface-container-lowest rounded-2xl p-space-lg md:p-space-xl shadow-sm relative overflow-hidden border border-outline-variant/20">
                    <div class="absolute top-0 left-0 w-2 h-full bg-secondary-container"></div>
                    
                    <div class="flex items-center justify-between gap-space-md pb-space-md mb-space-lg border-b border-outline-variant/10">
                        <div class="flex items-center gap-space-sm">
                            <span class="material-symbols-outlined text-secondary text-[24px]">school</span>
                            <div class="flex flex-col">
                                <span class="font-badge-label text-badge-label text-on-surface-variant uppercase tracking-wider">Pemetaan Kurikulum</span>
                                <h2 class="font-headline-md text-headline-md text-primary font-bold">Seksi 2: Program Studi &amp; Status Akademik</h2>
                            </div>
                        </div>
                        <span class="font-badge-label text-badge-label bg-secondary-fixed text-on-secondary-fixed px-space-sm py-1 rounded-full uppercase tracking-wider font-bold">Kurikulum Terakreditasi</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                        <!-- Program Studi Pilihan -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-field text-label-field text-on-surface-variant">
                                Program Studi <span class="text-secondary">*</span>
                            </label>
                            <select 
                                id="select-prodi" 
                                name="id_prodi" 
                                required 
                                class="w-full bg-surface-container-low px-4 py-3 rounded-xl font-body-md text-body-md text-primary font-semibold focus:outline-none focus:ring-1 focus:ring-primary shadow-inner border border-outline-variant/30 cursor-pointer"
                            >
                                <?php foreach ($prodi_list as $idx => $p): ?>
                                    <option value="<?= $p['id'] ?>" data-fakultas="<?= htmlspecialchars($p['fakultas']) ?>" <?= $idx === 0 ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($p['nama_prodi']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Fakultas Autofill -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-field text-label-field text-on-surface-variant">
                                Fakultas Terkait (Otomatis)
                            </label>
                            <div class="flex items-center justify-between bg-surface-container px-4 py-3 rounded-xl border border-outline-variant/30">
                                <span class="font-body-md text-body-md text-primary font-medium" id="preview-fakultas-val">
                                    <?= !empty($prodi_list) ? htmlspecialchars($prodi_list[0]['fakultas']) : 'Fakultas Ilmu Komputer' ?>
                                </span>
                                <span class="material-symbols-outlined text-[18px] text-on-surface-variant">lock</span>
                            </div>
                        </div>

                        <!-- Status Akademik -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-field text-label-field text-on-surface-variant">
                                Status Akademik Awal <span class="text-secondary">*</span>
                            </label>
                            <select 
                                name="status_akademik" 
                                class="w-full bg-surface-container-low px-4 py-3 rounded-xl font-body-md text-body-md text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-inner border border-outline-variant/30 cursor-pointer"
                            >
                                <option value="Aktif" selected>Aktif (Terdaftar KRS)</option>
                                <option value="Cuti">Cuti Akademik</option>
                                <option value="Lulus">Lulus</option>
                                <option value="DO">DO (Drop Out)</option>
                            </select>
                        </div>

                        <!-- Dosen Penasihat Akademik (DPA) -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-field text-label-field text-on-surface-variant">
                                Dosen Penasihat Akademik (DPA)
                            </label>
                            <input 
                                name="dpa" 
                                type="text" 
                                value="Dr. Muhtarom, M.Kom" 
                                class="w-full bg-surface-container-low px-4 py-3 rounded-xl font-body-md text-body-md text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-inner border border-outline-variant/30"
                            />
                        </div>
                    </div>
                </section>

                <!-- SEKSI 3: KONTAK, DOMISILI & PEMETAAN GEOSPASIAL -->
                <section class="bg-surface-container-lowest rounded-2xl p-space-lg md:p-space-xl shadow-sm relative overflow-hidden border border-outline-variant/20">
                    <div class="absolute top-0 left-0 w-2 h-full bg-outline"></div>
                    
                    <div class="flex items-center justify-between gap-space-md pb-space-md mb-space-lg border-b border-outline-variant/10">
                        <div class="flex items-center gap-space-sm">
                            <span class="material-symbols-outlined text-primary text-[24px]">pin_drop</span>
                            <div class="flex flex-col">
                                <span class="font-badge-label text-badge-label text-on-surface-variant uppercase tracking-wider">Korespondensi &amp; Spasial</span>
                                <h2 class="font-headline-md text-headline-md text-primary font-bold">Seksi 3: Kontak, Domisili &amp; Koordinat</h2>
                            </div>
                        </div>
                        <span class="font-badge-label text-badge-label bg-surface-container text-on-surface-variant px-space-sm py-1 rounded-full uppercase tracking-wider font-bold">GIS Ready</span>
                    </div>

                    <div class="space-y-space-md">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                            <!-- Email -->
                            <div class="flex flex-col gap-1.5">
                                <label class="font-label-field text-label-field text-on-surface-variant">
                                    Alamat Email Aktif <span class="text-secondary">*</span>
                                </label>
                                <input 
                                    name="email" 
                                    type="email" 
                                    required 
                                    placeholder="nama@email.com" 
                                    class="w-full bg-surface-container-low px-4 py-3 rounded-xl font-body-md text-body-md text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-inner border border-outline-variant/30"
                                />
                            </div>

                            <!-- No HP -->
                            <div class="flex flex-col gap-1.5">
                                <label class="font-label-field text-label-field text-on-surface-variant">
                                    Nomor Telepon / WhatsApp <span class="text-secondary">*</span>
                                </label>
                                <input 
                                    name="no_hp" 
                                    type="tel" 
                                    required 
                                    placeholder="Contoh: 081234567890" 
                                    class="w-full bg-surface-container-low px-4 py-3 rounded-xl font-body-md text-body-md text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-inner border border-outline-variant/30"
                                />
                            </div>
                        </div>

                        <!-- Alamat Lengkap -->
                        <div class="flex flex-col gap-1.5">
                            <label class="font-label-field text-label-field text-on-surface-variant flex items-center justify-between">
                                <span>Alamat Lengkap Sesuai KTP / Domisili <span class="text-secondary">*</span></span>
                                <span class="text-[11px] text-on-surface-variant/70 font-normal">Format: Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kab</span>
                            </label>
                            <textarea 
                                id="input-alamat"
                                name="alamat" 
                                rows="3" 
                                required 
                                placeholder="Contoh: Jl. Slamet Riyadi No. 112, Kelurahan Sriwedari, Kecamatan Laweyan, Surakarta" 
                                class="w-full bg-surface-container-low p-4 rounded-xl font-body-md text-body-md text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-inner border border-outline-variant/30 leading-relaxed"
                            ></textarea>
                        </div>

                        <!-- INTERACTIVE MAP & AUTO-GEOCODING DISPLAY -->
                        <div class="bg-surface-container-low p-3.5 rounded-xl border border-outline-variant/30 flex flex-col gap-2.5">
                            <div class="flex flex-wrap items-center justify-between gap-2">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-secondary text-[20px]">explore</span>
                                    <span class="font-label-field text-label-field text-primary font-bold">Peta Penunjuk Titik Domisili</span>
                                    <span id="geo-status-badge" class="font-code-nim text-[11px] bg-surface-container text-on-surface-variant px-2 py-0.5 rounded-full flex items-center gap-1.5 font-medium border border-outline-variant/20">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                                        <span>Siap Deteksi</span>
                                    </span>
                                </div>
                                
                                <div class="flex items-center gap-1.5 text-xs">
                                    <button type="button" id="btn-geocode-address" class="px-2.5 py-1 rounded-lg bg-surface-container-lowest hover:bg-surface-container-high text-primary font-label-field flex items-center gap-1 border border-outline-variant/30 shadow-xs transition-colors" title="Lacak alamat yang diketik ke titik peta">
                                        <span class="material-symbols-outlined text-[15px] text-secondary">my_location</span>
                                        <span>Lacak Alamat</span>
                                    </button>
                                    <button type="button" id="btn-center-campus" class="px-2.5 py-1 rounded-lg bg-surface-container-lowest hover:bg-surface-container-high text-primary font-label-field flex items-center gap-1 border border-outline-variant/30 shadow-xs transition-colors" title="Pusatkan ke Kampus FIKOM UDB Surakarta">
                                        <span class="material-symbols-outlined text-[15px] text-primary">school</span>
                                        <span>Kampus UDB</span>
                                    </button>
                                    <button type="button" id="btn-use-gps" class="px-2.5 py-1 rounded-lg bg-surface-container-lowest hover:bg-surface-container-high text-primary font-label-field flex items-center gap-1 border border-outline-variant/30 shadow-xs transition-colors" title="Gunakan koordinat GPS perangkat">
                                        <span class="material-symbols-outlined text-[15px] text-emerald-600">near_me</span>
                                        <span>GPS</span>
                                    </button>
                                </div>
                            </div>

                            <!-- Map Canvas Container -->
                            <div id="form-domisili-map" class="w-full h-64 md:h-72 rounded-lg overflow-hidden border border-outline-variant/40 shadow-inner z-0 relative"></div>

                            <!-- Live Geocoding Feedback -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between text-[11px] text-on-surface-variant gap-1.5">
                                <div id="geo-detected-address" class="truncate flex items-center gap-1.5 font-label-field text-primary">
                                    <span class="material-symbols-outlined text-[15px] text-secondary shrink-0">pin_drop</span>
                                    <span class="truncate">Ketikkan alamat di atas, atau klik / geser pin marker pada peta untuk menentukan koordinat.</span>
                                </div>
                                <span class="shrink-0 text-on-surface-variant/80 font-code-nim font-semibold bg-surface-container px-2 py-0.5 rounded" id="geo-coords-preview">
                                    Lat: -7.55800000 | Lng: 110.82700000
                                </span>
                            </div>
                        </div>

                        <!-- Titik Koordinat Lat/Long (Tersinkronisasi Otomatis) -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                            <div class="flex flex-col gap-1.5">
                                <label class="font-label-field text-label-field text-on-surface-variant flex items-center justify-between">
                                    <span>Latitude</span>
                                    <span class="text-[11px] text-secondary font-code-nim font-bold">Auto-Sync Map</span>
                                </label>
                                <input 
                                    id="input-lat" 
                                    name="latitude" 
                                    type="number" 
                                    step="any" 
                                    value="-7.55800000"
                                    placeholder="-7.55800000" 
                                    class="w-full bg-surface-container-low px-4 py-3 rounded-xl font-code-nim text-code-nim text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-inner border border-outline-variant/30"
                                />
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label class="font-label-field text-label-field text-on-surface-variant flex items-center justify-between">
                                    <span>Longitude</span>
                                    <span class="text-[11px] text-secondary font-code-nim font-bold">Auto-Sync Map</span>
                                </label>
                                <input 
                                    id="input-lng" 
                                    name="longitude" 
                                    type="number" 
                                    step="any" 
                                    value="110.82700000"
                                    placeholder="110.82700000" 
                                    class="w-full bg-surface-container-low px-4 py-3 rounded-xl font-code-nim text-code-nim text-on-surface focus:outline-none focus:ring-1 focus:ring-primary shadow-inner border border-outline-variant/30"
                                />
                            </div>
                        </div>
                    </div>
                </section>

                <!-- SEKSI 4: UNGGAH DOKUMEN LEGALITAS & BERKAS OTENTIK -->
                <section class="bg-surface-container-lowest rounded-2xl p-space-lg md:p-space-xl shadow-sm relative overflow-hidden border border-outline-variant/20">
                    <div class="absolute top-0 left-0 w-2 h-full bg-secondary"></div>
                    
                    <div class="flex items-center justify-between gap-space-md pb-space-md mb-space-lg border-b border-outline-variant/10">
                        <div class="flex items-center gap-space-sm">
                            <span class="material-symbols-outlined text-secondary text-[24px]">cloud_upload</span>
                            <div class="flex flex-col">
                                <span class="font-badge-label text-badge-label text-on-surface-variant uppercase tracking-wider">Digital Dossier Vault</span>
                                <h2 class="font-headline-md text-headline-md text-primary font-bold">Seksi 4: Unggah Berkas &amp; Dokumen Otentik</h2>
                            </div>
                        </div>
                        <span class="font-badge-label text-badge-label bg-emerald-50 text-emerald-800 px-space-sm py-1 rounded-full uppercase tracking-wider font-bold">Verifikasi Biner MIME</span>
                    </div>

                    <!-- Dropzones Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-space-md">
                        
                        <!-- Dropzone 1: Pasfoto Resmi 3x4 (JPG/PNG maks 2MB) -->
                        <div class="bg-surface-container-low rounded-xl p-space-md flex flex-col justify-between gap-space-md border border-outline-variant/30">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-headline-sm text-[15px] text-primary font-bold">1. Pasfoto Resmi 3x4</h3>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">Formal latar rapi (JPG / PNG, Maks 2MB)</p>
                                </div>
                                <span id="icon-status-foto" class="material-symbols-outlined text-on-surface-variant text-[20px]">radio_button_unchecked</span>
                            </div>

                            <label class="cursor-pointer">
                                <input 
                                    id="file-input-foto" 
                                    name="foto" 
                                    type="file" 
                                    accept="image/jpeg,image/png" 
                                    class="hidden" 
                                    onchange="handleFotoUpload(this)"
                                />
                                <div class="flex items-center gap-space-md bg-surface-container-lowest p-space-sm rounded-xl shadow-sm border border-dashed border-outline-variant hover:border-primary transition-all">
                                    <img 
                                        id="preview-img-foto" 
                                        class="w-16 h-20 rounded-lg object-cover shrink-0 shadow-inner bg-surface-container" 
                                        src="<?= generate_avatar_svg('M', 'Laki-laki', 'NEW') ?>" 
                                        alt="Preview Pasfoto"
                                    />
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span id="label-name-foto" class="font-code-nim text-[12px] text-primary truncate font-bold">Klik untuk memilih foto...</span>
                                        <span id="label-info-foto" class="font-body-sm text-[11px] text-on-surface-variant">JPG atau PNG • Max 2.0 MB</span>
                                        <div class="flex items-center gap-space-xs mt-1.5">
                                            <span class="font-label-field text-[11px] text-secondary hover:underline">Pilih Berkas</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Dropzone 2: Dokumen Berkas PDF (Maks 5MB) -->
                        <div class="bg-surface-container-low rounded-xl p-space-md flex flex-col justify-between gap-space-md border border-outline-variant/30">
                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-headline-sm text-[15px] text-primary font-bold">2. Dokumen Akademik (PDF)</h3>
                                    <p class="font-body-sm text-body-sm text-on-surface-variant">Ijazah / SKL / Transkrip (PDF, Maks 5MB)</p>
                                </div>
                                <span id="icon-status-doc" class="material-symbols-outlined text-on-surface-variant text-[20px]">radio_button_unchecked</span>
                            </div>

                            <label class="cursor-pointer">
                                <input 
                                    id="file-input-doc" 
                                    name="dokumen" 
                                    type="file" 
                                    accept="application/pdf" 
                                    class="hidden" 
                                    onchange="handleDocUpload(this)"
                                />
                                <div class="flex items-center gap-space-sm bg-surface-container-lowest p-space-sm rounded-xl shadow-sm border border-dashed border-outline-variant hover:border-secondary transition-all">
                                    <div class="w-12 h-12 rounded-lg bg-red-50 text-red-700 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[24px]">picture_as_pdf</span>
                                    </div>
                                    <div class="flex flex-col flex-1 min-w-0">
                                        <span id="label-name-doc" class="font-code-nim text-[12px] text-primary truncate font-bold">Klik untuk memilih berkas PDF...</span>
                                        <span id="label-info-doc" class="font-body-sm text-[11px] text-on-surface-variant">Maksimal 5.0 MB</span>
                                        <div class="flex items-center gap-space-xs mt-1.5">
                                            <span class="font-label-field text-[11px] text-secondary hover:underline">Pilih Berkas PDF</span>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                    </div>
                </section>

            </div>

            <!-- RIGHT COLUMN / STICKY DOSSIER INSPECTOR & REALTIME KTM (Col 4) -->
            <div class="lg:col-span-4 flex flex-col gap-space-lg lg:sticky lg:top-28">
                
                <!-- 1. REALTIME DIGITAL KTM PREVIEW CARD -->
                <div class="bg-surface-container-lowest rounded-2xl p-space-md shadow-sm flex flex-col gap-space-sm relative overflow-hidden border border-outline-variant/20">
                    <div class="flex items-center justify-between pb-space-xs">
                        <div class="flex items-center gap-space-xs">
                            <span class="material-symbols-outlined text-secondary text-[20px]">credit_card</span>
                            <span class="font-headline-sm text-[15px] text-primary font-bold">KTM Digital Realtime</span>
                        </div>
                        <span class="font-badge-label text-[10px] bg-secondary-fixed text-on-secondary-fixed px-2 py-0.5 rounded-full uppercase tracking-wider font-bold">Live Sync</span>
                    </div>

                    <!-- THE PHYSICAL CARD CANVAS -->
                    <div class="relative w-full rounded-2xl bg-gradient-to-br from-primary-container via-[#1e2532] to-primary text-on-primary p-space-md shadow-xl overflow-hidden min-h-[220px] flex flex-col justify-between">
                        <!-- Decorative Micro Blur in Card -->
                        <div class="absolute -right-8 -top-8 w-44 h-44 rounded-full bg-secondary-container/10 blur-2xl pointer-events-none"></div>
                        <div class="absolute -left-8 -bottom-8 w-44 h-44 rounded-full bg-surface-container-high/5 blur-xl pointer-events-none"></div>

                        <!-- Card Header -->
                        <div class="relative z-10 flex items-start justify-between">
                            <div class="flex items-center gap-space-xs">
                                <div class="w-8 h-8 rounded-lg bg-surface-container-lowest/10 backdrop-blur flex items-center justify-center p-1">
                                    <span class="material-symbols-outlined text-secondary-fixed text-[20px]">account_balance</span>
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-headline-sm text-[11px] tracking-wider uppercase text-on-primary font-bold">Universitas Duta Bangsa</span>
                                    <span class="font-badge-label text-[9px] text-on-primary-container tracking-widest uppercase">Fakultas Ilmu Komputer</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-1.5 opacity-80">
                                <span class="material-symbols-outlined text-[18px] text-secondary-fixed">contactless</span>
                                <span class="font-code-nim text-[9px] tracking-widest text-on-primary-container">NFC READY</span>
                            </div>
                        </div>

                        <!-- Card Middle: Photo + Identity -->
                        <div class="relative z-10 flex items-center gap-space-md my-space-sm">
                            <img 
                                id="ktm-card-photo" 
                                class="w-16 h-20 rounded-xl object-cover shadow-md ring-2 ring-surface-container-lowest/20 shrink-0 bg-white/10" 
                                src="<?= generate_avatar_svg('NAMA MAHASISWA', 'Laki-laki', '2026') ?>" 
                                alt="KTM Student Photo"
                            />
                            <div class="flex flex-col min-w-0">
                                <span class="font-headline-sm text-[14px] text-on-primary truncate font-bold tracking-tight" id="ktm-name-display">
                                    NAMA MAHASISWA
                                </span>
                                <span class="font-code-nim text-code-nim text-secondary-fixed font-bold tracking-wider mt-0.5" id="ktm-nim-display">
                                    NIM BELUM DIISI
                                </span>
                                <span class="font-body-sm text-[11px] text-on-primary-container mt-1 truncate font-medium" id="ktm-prodi-display">
                                    <?= !empty($prodi_list) ? htmlspecialchars($prodi_list[0]['nama_prodi']) : 'Program Studi' ?>
                                </span>
                                <span class="font-badge-label text-[9px] text-on-primary/60 mt-0.5" id="ktm-fakultas-display">
                                    <?= !empty($prodi_list) ? htmlspecialchars($prodi_list[0]['fakultas']) : 'Fakultas Ilmu Komputer' ?>
                                </span>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="relative z-10 flex items-end justify-between pt-2 border-t border-white/10">
                            <div class="flex flex-col">
                                <span class="font-badge-label text-[8px] text-on-primary-container uppercase tracking-wider">Masa Berlaku</span>
                                <span class="font-code-nim text-[11px] text-on-primary font-bold">2026 - 2030</span>
                            </div>
                            <!-- Barcode Graphic Simulation -->
                            <div class="flex flex-col items-end">
                                <div class="flex items-center gap-[2px] h-3 opacity-75">
                                    <div class="w-[2px] h-full bg-white"></div>
                                    <div class="w-[1px] h-full bg-white"></div>
                                    <div class="w-[3px] h-full bg-white"></div>
                                    <div class="w-[1px] h-full bg-white"></div>
                                    <div class="w-[4px] h-full bg-white"></div>
                                    <div class="w-[2px] h-full bg-white"></div>
                                </div>
                                <span class="font-code-nim text-[8px] text-on-primary-container mt-0.5 tracking-widest">UDB-SEC-AUTHPASS</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-body-sm text-[12px] text-on-surface-variant px-1 pt-1">
                        <span>Standar Desain Kartu</span>
                        <span class="text-secondary font-semibold">Tersinkron Otomatis</span>
                    </div>
                </div>

                <!-- 2. CHECKLIST KELENGKAPAN DOSSIER -->
                <div class="bg-surface-container-lowest rounded-2xl p-space-md shadow-sm flex flex-col gap-space-md border border-outline-variant/20">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-space-xs">
                            <span class="material-symbols-outlined text-primary text-[20px]">fact_check</span>
                            <span class="font-headline-sm text-[15px] text-primary font-bold">Kelengkapan Registri</span>
                        </div>
                        <span id="badge-valid-status" class="font-code-nim text-code-nim text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full font-bold text-[11px]">Memproses Input</span>
                    </div>

                    <div class="space-y-space-xs text-xs">
                        <div class="flex items-start gap-space-sm p-space-sm rounded-xl bg-surface-container-low" id="check-step-1">
                            <span class="material-symbols-outlined text-outline text-[18px] shrink-0 mt-0.5">check_circle</span>
                            <div class="flex flex-col min-w-0">
                                <span class="font-label-field text-primary font-bold">Identitas Pokok (Nama &amp; NIM)</span>
                                <span class="text-on-surface-variant text-[11px]">Wajib terisi sebelum formulir dapat disimpan</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-space-sm p-space-sm rounded-xl bg-surface-container-low" id="check-step-2">
                            <span class="material-symbols-outlined text-outline text-[18px] shrink-0 mt-0.5">check_circle</span>
                            <div class="flex flex-col min-w-0">
                                <span class="font-label-field text-primary font-bold">Kontak &amp; Domisili</span>
                                <span class="text-on-surface-variant text-[11px]">Email aktif, WhatsApp, dan alamat lengkap</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-space-sm p-space-sm rounded-xl bg-surface-container-low" id="check-step-3">
                            <span class="material-symbols-outlined text-outline text-[18px] shrink-0 mt-0.5">check_circle</span>
                            <div class="flex flex-col min-w-0">
                                <span class="font-label-field text-primary font-bold">Unggah Berkas Pasfoto &amp; PDF</span>
                                <span class="text-on-surface-variant text-[11px]">Mendukung JPG/PNG (2MB) &amp; PDF (5MB)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. BANTUAN REGISTRAR AKADEMIK -->
                <div class="bg-surface-container-lowest rounded-2xl p-space-md shadow-sm flex flex-col gap-space-sm border border-outline-variant/20">
                    <div class="flex items-center gap-space-xs">
                        <span class="material-symbols-outlined text-secondary text-[20px]">support_agent</span>
                        <span class="font-headline-sm text-[15px] text-primary font-bold">Helpdesk Registrar Akademik</span>
                    </div>
                    <p class="font-body-sm text-body-sm text-on-surface-variant text-xs leading-relaxed">
                        Jika terdapat kendala sinkronisasi program studi atau duplikasi NIM, silakan hubungi operator Biro Akademik.
                    </p>
                </div>

            </div>

        </div>

        <!-- STICKY ACTION BAR AT FOOTER -->
        <div class="fixed bottom-0 left-0 lg:left-64 right-0 z-40 bg-surface-container-lowest/95 backdrop-blur-md shadow-[0_-4px_24px_rgba(24,29,38,0.08)] px-margin py-space-md border-t border-outline-variant/20">
            <div class="max-w-[1440px] mx-auto flex flex-col sm:flex-row items-center justify-between gap-space-md">
                <!-- Left: Back Button -->
                <a class="inline-flex items-center gap-space-xs font-headline-sm text-[13px] text-on-surface-variant hover:text-primary transition-colors order-2 sm:order-1 font-bold" href="index.php">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    <span>Batal &amp; Kembali ke Direktori</span>
                </a>

                <!-- Right: Submit Action Button -->
                <div class="flex items-center gap-space-sm w-full sm:w-auto justify-end order-1 sm:order-2">
                    <button 
                        type="submit" 
                        class="inline-flex items-center justify-center gap-space-xs bg-primary text-on-primary font-headline-sm text-[14px] font-bold px-space-lg py-3 rounded-xl hover:bg-[#2a3240] active:scale-[0.98] transition-all shadow-md w-full sm:w-auto cursor-pointer"
                    >
                        <span class="material-symbols-outlined text-[18px]">save</span>
                        <span>Simpan Data Biodata Mahasiswa</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- CLIENT-SIDE INTERACTIVE SYNCHRONIZATION SCRIPT -->
<script>
    // Live Synchronize Form Inputs with KTM Preview Card & Progress
    const inputNama = document.getElementById('input-nama');
    const inputNim = document.getElementById('input-nim');
    const selectProdi = document.getElementById('select-prodi');
    const previewFakultas = document.getElementById('preview-fakultas-val');

    const ktmName = document.getElementById('ktm-name-display');
    const ktmNim = document.getElementById('ktm-nim-display');
    const ktmProdi = document.getElementById('ktm-prodi-display');
    const ktmFakultas = document.getElementById('ktm-fakultas-display');
    const ktmPhoto = document.getElementById('ktm-card-photo');

    let currentGender = 'Laki-laki';

    function updateGenderStyle(radio) {
        currentGender = radio.value;
        const labelL = document.getElementById('label-gender-l');
        const labelP = document.getElementById('label-gender-p');

        if (radio.value === 'Laki-laki') {
            labelL.className = "flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg bg-surface-container-lowest text-primary shadow-sm font-label-field text-label-field cursor-pointer transition-all";
            labelP.className = "flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg text-on-surface-variant hover:text-primary transition-all font-label-field text-label-field cursor-pointer";
        } else {
            labelP.className = "flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg bg-surface-container-lowest text-primary shadow-sm font-label-field text-label-field cursor-pointer transition-all";
            labelL.className = "flex items-center justify-center gap-2 py-2.5 px-3 rounded-lg text-on-surface-variant hover:text-primary transition-all font-label-field text-label-field cursor-pointer";
        }
        updateFormProgress();
    }

    if (inputNama && ktmName) {
        inputNama.addEventListener('input', function(e) {
            const val = e.target.value.trim();
            ktmName.textContent = val.length > 0 ? val.toUpperCase() : 'NAMA MAHASISWA';
            updateFormProgress();
        });
    }

    if (inputNim && ktmNim) {
        inputNim.addEventListener('input', function(e) {
            const val = e.target.value.trim();
            ktmNim.textContent = val.length > 0 ? val : 'NIM BELUM DIISI';
            updateFormProgress();
        });
    }

    if (selectProdi && ktmProdi) {
        selectProdi.addEventListener('change', function(e) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const prodiText = selectedOption.text.trim();
            const fakultasText = selectedOption.getAttribute('data-fakultas') || 'Fakultas Ilmu Komputer';

            ktmProdi.textContent = prodiText;
            ktmFakultas.textContent = fakultasText;
            if (previewFakultas) {
                previewFakultas.textContent = fakultasText;
            }
        });
    }

    // Live Upload Preview for Foto
    function handleFotoUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const labelName = document.getElementById('label-name-foto');
            const labelInfo = document.getElementById('label-info-foto');
            const iconStatus = document.getElementById('icon-status-foto');
            const previewImg = document.getElementById('preview-img-foto');

            labelName.textContent = file.name;
            labelInfo.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB • Siap Diunggah';
            iconStatus.textContent = 'check_circle';
            iconStatus.className = 'material-symbols-outlined text-emerald-600 text-[20px]';

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                ktmPhoto.src = e.target.result;
            };
            reader.readAsDataURL(file);
            updateFormProgress();
        }
    }

    // Live Upload Preview for Doc
    function handleDocUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const labelName = document.getElementById('label-name-doc');
            const labelInfo = document.getElementById('label-info-doc');
            const iconStatus = document.getElementById('icon-status-doc');

            labelName.textContent = file.name;
            labelInfo.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB • Berkas PDF Siap';
            iconStatus.textContent = 'check_circle';
            iconStatus.className = 'material-symbols-outlined text-emerald-600 text-[20px]';
            updateFormProgress();
        }
    }

    function updateFormProgress() {
        let score = 0;
        const nama = inputNama ? inputNama.value.trim() : '';
        const nim = inputNim ? inputNim.value.trim() : '';
        
        const check1 = document.getElementById('check-step-1');
        const check2 = document.getElementById('check-step-2');
        const check3 = document.getElementById('check-step-3');
        const bar = document.getElementById('form-progress-bar');
        const text = document.getElementById('form-progress-text');
        const statusBadge = document.getElementById('badge-valid-status');

        if (nama.length > 0 && nim.length > 0) {
            score += 40;
            if (check1) {
                check1.querySelector('.material-symbols-outlined').textContent = 'check_circle';
                check1.querySelector('.material-symbols-outlined').className = 'material-symbols-outlined text-emerald-600 text-[18px] shrink-0 mt-0.5';
            }
        }

        const email = document.querySelector('input[name="email"]') ? document.querySelector('input[name="email"]').value.trim() : '';
        const hp = document.querySelector('input[name="no_hp"]') ? document.querySelector('input[name="no_hp"]').value.trim() : '';
        const alamat = document.querySelector('textarea[name="alamat"]') ? document.querySelector('textarea[name="alamat"]').value.trim() : '';

        if (email.length > 0 && hp.length > 0 && alamat.length > 0) {
            score += 30;
            if (check2) {
                check2.querySelector('.material-symbols-outlined').textContent = 'check_circle';
                check2.querySelector('.material-symbols-outlined').className = 'material-symbols-outlined text-emerald-600 text-[18px] shrink-0 mt-0.5';
            }
        }

        const fotoInput = document.getElementById('file-input-foto');
        const docInput = document.getElementById('file-input-doc');
        if ((fotoInput && fotoInput.files.length > 0) || (docInput && docInput.files.length > 0)) {
            score += 30;
            if (check3) {
                check3.querySelector('.material-symbols-outlined').textContent = 'check_circle';
                check3.querySelector('.material-symbols-outlined').className = 'material-symbols-outlined text-emerald-600 text-[18px] shrink-0 mt-0.5';
            }
        }

        if (score === 0) score = 15;
        if (bar) bar.style.width = score + '%';
        if (text) text.textContent = score + '% Terisi';
        if (statusBadge) {
            if (score >= 70) {
                statusBadge.textContent = 'Siap Simpan (' + score + '%)';
                statusBadge.className = 'font-code-nim text-code-nim text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded-full font-bold text-[11px]';
            } else {
                statusBadge.textContent = 'Memproses Input (' + score + '%)';
                statusBadge.className = 'font-code-nim text-code-nim text-amber-800 bg-amber-50 px-2 py-0.5 rounded-full font-bold text-[11px]';
            }
        }
    }

    // =========================================================
    // LEAFLET MAP & AUTOMATIC ADDRESS GEOCODING INTEGRATION
    // =========================================================
    const campusDefaultCoords = [-7.558000, 110.827000]; // UDB Surakarta
    const inputLat = document.getElementById('input-lat');
    const inputLng = document.getElementById('input-lng');
    const inputAlamat = document.getElementById('input-alamat');
    const geoStatusBadge = document.getElementById('geo-status-badge');
    const geoCoordsPreview = document.getElementById('geo-coords-preview');
    const geoDetectedAddress = document.getElementById('geo-detected-address');
    const btnGeocode = document.getElementById('btn-geocode-address');
    const btnCampus = document.getElementById('btn-center-campus');
    const btnGps = document.getElementById('btn-use-gps');

    let formMap = null;
    let formMarker = null;
    let geocodeTimeout = null;

    // Custom Editorial Pin Icon
    const formPinIcon = L.divIcon({
        className: 'custom-form-pin',
        html: `
            <div style="
                background: #af3003; 
                color: #ffffff; 
                width: 34px; 
                height: 34px; 
                border-radius: 50% 50% 50% 0; 
                transform: rotate(-45deg); 
                display: flex; 
                align-items: center; 
                justify-content: center; 
                border: 2px solid #ffffff; 
                box-shadow: 0 4px 12px rgba(0,0,0,0.35);
            ">
                <span class="material-symbols-outlined" style="transform: rotate(45deg); font-size: 19px; line-height: 1;">person_pin_circle</span>
            </div>
        `,
        iconSize: [34, 34],
        iconAnchor: [17, 34],
        popupAnchor: [0, -34]
    });

    function initFormMap() {
        const initialLat = parseFloat(inputLat.value) || campusDefaultCoords[0];
        const initialLng = parseFloat(inputLng.value) || campusDefaultCoords[1];

        formMap = L.map('form-domisili-map', {
            center: [initialLat, initialLng],
            zoom: 15,
            scrollWheelZoom: true
        });

        // OpenStreetMap Tile Layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(formMap);

        // Draggable Marker
        formMarker = L.marker([initialLat, initialLng], {
            icon: formPinIcon,
            draggable: true
        }).addTo(formMap);

        formMarker.bindPopup("<b>Titik Domisili Mahasiswa</b><br><small>Geser atau klik peta untuk mengubah posisi.</small>");

        // Sync on Marker Drag End
        formMarker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            updateCoordinates(pos.lat, pos.lng, "Marker Digeser");
        });

        // Sync on Map Click
        formMap.on('click', function(e) {
            const pos = e.latlng;
            formMarker.setLatLng(pos);
            updateCoordinates(pos.lat, pos.lng, "Peta Diklik");
        });

        // Trigger size update when container is rendered
        setTimeout(() => {
            formMap.invalidateSize();
        }, 300);
    }

    function updateCoordinates(lat, lng, actionInfo = '') {
        const roundedLat = parseFloat(lat).toFixed(8);
        const roundedLng = parseFloat(lng).toFixed(8);

        if (inputLat) inputLat.value = roundedLat;
        if (inputLng) inputLng.value = roundedLng;

        if (geoCoordsPreview) {
            geoCoordsPreview.textContent = `Lat: ${roundedLat} | Lng: ${roundedLng}`;
        }

        if (geoStatusBadge) {
            geoStatusBadge.innerHTML = `<span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span><span>Tersinkron (${actionInfo || 'Aktif'})</span>`;
            geoStatusBadge.className = 'font-code-nim text-[11px] bg-emerald-50 text-emerald-800 px-2 py-0.5 rounded-full flex items-center gap-1.5 font-medium border border-emerald-200';
        }
    }

    // Geocoding Function using OpenStreetMap Nominatim
    async function performGeocoding(queryText) {
        if (!queryText || queryText.trim().length < 3) return;

        if (geoStatusBadge) {
            geoStatusBadge.innerHTML = `<span class="w-2 h-2 rounded-full bg-amber-500 animate-ping inline-block"></span><span>Mencari Koordinat...</span>`;
            geoStatusBadge.className = 'font-code-nim text-[11px] bg-amber-50 text-amber-800 px-2 py-0.5 rounded-full flex items-center gap-1.5 font-medium border border-amber-200';
        }

        try {
            let cleanQuery = queryText.replace(/^(Jl\.|Jalan\s+)/i, '').trim();
            if (!cleanQuery.toLowerCase().includes('indonesia')) {
                cleanQuery += ', Indonesia';
            }

            const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(cleanQuery)}&limit=1`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data && data.length > 0) {
                const target = data[0];
                const lat = parseFloat(target.lat);
                const lon = parseFloat(target.lon);

                formMarker.setLatLng([lat, lon]);
                formMap.flyTo([lat, lon], 16, { duration: 1.2 });
                updateCoordinates(lat, lon, "Alamat Ditemukan");

                if (geoDetectedAddress) {
                    geoDetectedAddress.innerHTML = `<span class="material-symbols-outlined text-[15px] text-emerald-600 shrink-0">check_circle</span><span class="truncate text-emerald-900 font-medium">${target.display_name}</span>`;
                }
            } else {
                const words = queryText.split(/[\s,]+/);
                const lastWords = words.slice(-3).join(' ');
                if (lastWords.length > 3) {
                    const fallbackRes = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(lastWords + ', Indonesia')}&limit=1`);
                    const fallbackData = await fallbackRes.json();
                    if (fallbackData && fallbackData.length > 0) {
                        const target = fallbackData[0];
                        const lat = parseFloat(target.lat);
                        const lon = parseFloat(target.lon);
                        formMarker.setLatLng([lat, lon]);
                        formMap.flyTo([lat, lon], 14, { duration: 1.0 });
                        updateCoordinates(lat, lon, "Area Ditemukan");
                        if (geoDetectedAddress) {
                            geoDetectedAddress.innerHTML = `<span class="material-symbols-outlined text-[15px] text-amber-600 shrink-0">info</span><span class="truncate text-amber-900">Area terdeteksi: ${target.display_name}. Anda dapat menggeser pin marker lebih spesifik.</span>`;
                        }
                        return;
                    }
                }

                if (geoStatusBadge) {
                    geoStatusBadge.innerHTML = `<span class="w-2 h-2 rounded-full bg-secondary inline-block"></span><span>Lokasi Spesifik Belum Ketemu</span>`;
                    geoStatusBadge.className = 'font-code-nim text-[11px] bg-secondary-fixed/40 text-on-secondary-fixed px-2 py-0.5 rounded-full flex items-center gap-1.5 font-medium border border-secondary/20';
                }
                if (geoDetectedAddress) {
                    geoDetectedAddress.innerHTML = `<span class="material-symbols-outlined text-[15px] text-secondary shrink-0">help</span><span class="truncate">Alamat spesifik belum ditemukan di OSM. Silakan klik atau geser pin marker pada peta secara manual.</span>`;
                }
            }
        } catch (err) {
            console.warn("Geocoding error:", err);
            if (geoStatusBadge) {
                geoStatusBadge.innerHTML = `<span class="w-2 h-2 rounded-full bg-slate-400 inline-block"></span><span>Manual Picker Aktif</span>`;
                geoStatusBadge.className = 'font-code-nim text-[11px] bg-slate-100 text-slate-700 px-2 py-0.5 rounded-full flex items-center gap-1.5 font-medium';
            }
        }
    }

    // Auto-Geocoding saat user mengetikkan alamat (Debounce 800ms)
    if (inputAlamat) {
        inputAlamat.addEventListener('input', function(e) {
            clearTimeout(geocodeTimeout);
            const text = e.target.value.trim();
            updateFormProgress();

            if (text.length >= 6) {
                geocodeTimeout = setTimeout(() => {
                    performGeocoding(text);
                }, 800);
            }
        });
    }

    // Tombol Lacak Alamat
    if (btnGeocode) {
        btnGeocode.addEventListener('click', function() {
            if (inputAlamat && inputAlamat.value.trim().length > 0) {
                performGeocoding(inputAlamat.value.trim());
            } else {
                alert('Silakan ketikkan alamat terlebih dahulu pada kolom Alamat Lengkap.');
                if (inputAlamat) inputAlamat.focus();
            }
        });
    }

    // Tombol Kampus UDB
    if (btnCampus) {
        btnCampus.addEventListener('click', function() {
            if (formMap && formMarker) {
                formMarker.setLatLng(campusDefaultCoords);
                formMap.flyTo(campusDefaultCoords, 16, { duration: 1.0 });
                updateCoordinates(campusDefaultCoords[0], campusDefaultCoords[1], "Pusat Kampus UDB");
                if (geoDetectedAddress) {
                    geoDetectedAddress.innerHTML = `<span class="material-symbols-outlined text-[15px] text-primary shrink-0">school</span><span class="truncate font-medium text-primary">Kampus Fakultas Ilmu Komputer (FIKOM) Universitas Duta Bangsa Surakarta</span>`;
                }
            }
        });
    }

    // Tombol GPS
    if (btnGps) {
        btnGps.addEventListener('click', function() {
            if ('geolocation' in navigator) {
                if (geoStatusBadge) {
                    geoStatusBadge.innerHTML = `<span class="w-2 h-2 rounded-full bg-blue-500 animate-ping inline-block"></span><span>Membaca GPS...</span>`;
                }
                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        if (formMap && formMarker) {
                            formMarker.setLatLng([userLat, userLng]);
                            formMap.flyTo([userLat, userLng], 17, { duration: 1.2 });
                            updateCoordinates(userLat, userLng, "GPS Terdeteksi");
                            if (geoDetectedAddress) {
                                geoDetectedAddress.innerHTML = `<span class="material-symbols-outlined text-[15px] text-emerald-600 shrink-0">gps_fixed</span><span class="truncate font-medium text-emerald-900">Koordinat GPS Anda Berhasil Digunakan!</span>`;
                            }
                        }
                    },
                    function(error) {
                        alert('Tidak dapat mengakses GPS perangkat: ' + error.message);
                        if (geoStatusBadge) {
                            geoStatusBadge.innerHTML = `<span class="w-2 h-2 rounded-full bg-slate-400 inline-block"></span><span>GPS Gagal</span>`;
                        }
                    },
                    { enableHighAccuracy: true, timeout: 8000 }
                );
            } else {
                alert('Browser Anda tidak mendukung Geolocation API.');
            }
        });
    }

    // Manual Input Lat / Lng -> Sync Marker
    if (inputLat && inputLng) {
        const syncManualCoords = () => {
            const lat = parseFloat(inputLat.value);
            const lng = parseFloat(inputLng.value);
            if (!isNaN(lat) && !isNaN(lng) && formMap && formMarker) {
                formMarker.setLatLng([lat, lng]);
                formMap.panTo([lat, lng]);
                updateCoordinates(lat, lng, "Input Manual");
            }
        };
        inputLat.addEventListener('change', syncManualCoords);
        inputLng.addEventListener('change', syncManualCoords);
    }

    // Inisialisasi peta saat DOM selesai dimuat
    document.addEventListener('DOMContentLoaded', function() {
        initFormMap();
    });
</script>

<?php require_once __DIR__ . '/footer.php'; ?>

