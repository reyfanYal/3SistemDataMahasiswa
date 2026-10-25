<?php
// ==============================================================================
// CETAK KARTU TANDA MAHASISWA (KTM) DENGAN QR CODE (MILESTONE 4)
// Framework: Native PHP, MySQLi Prepared Statement, QRCode.js CDN, Tailwind CSS
// UI/UX Reference: Stitch Airtable Warm Editorial
// ==============================================================================

require_once __DIR__ . '/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    // Ambil ID mahasiswa aktif pertama jika tidak ditentukan
    $q_first = $koneksi->query("SELECT id FROM mahasiswa WHERE is_deleted = 0 ORDER BY id ASC LIMIT 1");
    if ($q_first && $r_first = $q_first->fetch_assoc()) {
        $id = (int)$r_first['id'];
    } else {
        header("Location: index.php");
        exit;
    }
}

// Ambil data mahasiswa aktif menggunakan MySQLi Prepared Statement
$stmt = $koneksi->prepare("SELECT m.*, p.nama_prodi, p.fakultas 
                           FROM mahasiswa m 
                           LEFT JOIN prodi p ON m.id_prodi = p.id 
                           WHERE m.id = ? AND m.is_deleted = 0 
                           LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$mhs = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$mhs) {
    die("
    <div style='font-family: sans-serif; max-width: 500px; margin: 40px auto; padding: 24px; border: 1px solid #dee2ed; border-radius: 16px; background: #f8f9ff; color: #171c23; text-align: center;'>
        <h3 style='color: #ba1a1a; margin-top:0;'>Data Mahasiswa Tidak Ditemukan</h3>
        <p>Mahasiswa dengan ID #{$id} tidak ditemukan atau telah dinonaktifkan dari sistem arsip.</p>
        <a href='index.php' style='display:inline-block; padding: 10px 20px; background: #181d26; color: #ffffff; text-decoration: none; border-radius: 10px; font-weight: bold;'>Kembali ke Direktori</a>
    </div>
    ");
}

$avatar_src = get_mhs_avatar($mhs['nama'], $mhs['jenis_kelamin'], $mhs['nim'], $mhs['foto']);
$valid_until = ((int)$mhs['angkatan'] + 4);
$qr_payload = "UDB-KTM:" . $mhs['nim'] . ":" . $mhs['nama'] . ":" . ($mhs['nama_prodi'] ?? 'FIKOM');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>KTM Digital — <?= htmlspecialchars($mhs['nama']) ?> (<?= htmlspecialchars($mhs['nim']) ?>)</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700;900&family=Space+Mono:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#000208",
                        "primary-container": "#181d26",
                        "on-primary": "#ffffff",
                        "secondary": "#af3003",
                        "secondary-container": "#ff693c",
                        "surface": "#f8f9ff",
                        "surface-container": "#eaeef8",
                        "surface-container-low": "#eff4fe",
                        "surface-container-lowest": "#ffffff",
                        "on-surface": "#171c23",
                        "on-surface-variant": "#45474b",
                        "outline-variant": "#c6c6cc"
                    },
                    fontFamily: {
                        "code-nim": ["'Space Mono'", "monospace"],
                        "headline-sm": ["'Space Grotesk'", "sans-serif"],
                        "headline-lg": ["'Space Grotesk'", "sans-serif"],
                        "body-md": ["Inter", "sans-serif"]
                    }
                }
            }
        };
    </script>

    <!-- QRCode.js CDN (Tanpa NPM) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        @media print {
            body {
                background: #ffffff !important;
                padding: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .ktm-card-print {
                box-shadow: none !important;
                page-break-inside: avoid !important;
                margin: 20px auto !important;
            }
        }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface antialiased min-h-screen p-4 sm:p-8 flex flex-col items-center justify-center">

    <!-- Action Toolbar (Hidden on Print) -->
    <div class="no-print w-full max-w-4xl mb-6 flex flex-col sm:flex-row items-center justify-between gap-4 bg-surface-container-lowest p-4 rounded-2xl shadow-sm border border-outline-variant/30">
        <div class="flex items-center gap-3">
            <a href="index.php" class="px-4 py-2 rounded-xl bg-surface-container hover:bg-surface-container-high text-primary font-headline-sm text-xs font-bold transition-all inline-flex items-center gap-1.5 cursor-pointer">
                <span class="material-symbols-outlined text-[16px]">arrow_back</span>
                <span>Kembali ke Direktori</span>
            </a>
            <div class="h-5 w-[1px] bg-outline-variant/40 hidden sm:block"></div>
            <span class="text-xs text-on-surface-variant font-medium">
                Penerbitan KTM &bull; Mahasiswa <strong class="text-primary"><?= htmlspecialchars($mhs['nama']) ?></strong>
            </span>
        </div>

        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-5 py-2.5 rounded-xl bg-primary hover:bg-primary-container text-on-primary font-headline-sm text-xs font-bold shadow-md hover:scale-[1.02] active:scale-[0.98] transition-all inline-flex items-center gap-2 cursor-pointer">
                <span class="material-symbols-outlined text-[18px]">print</span>
                <span>Cetak Kartu (Print / PDF)</span>
            </button>
        </div>
    </div>

    <!-- Cards Display Grid (Sisi Depan & Sisi Belakang) -->
    <div class="w-full max-w-4xl grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

        <!-- ============================================== -->
        <!-- 1. KARTU SISI DEPAN (FRONT SIDE) -->
        <!-- ============================================== -->
        <div class="ktm-card-print relative w-full h-[320px] rounded-3xl bg-gradient-to-br from-[#12161f] via-[#1a202c] to-[#0d1017] text-white p-6 shadow-2xl border border-white/10 overflow-hidden flex flex-col justify-between select-none">
            
            <!-- Ambient Glow & Watermark -->
            <div class="absolute -right-12 -top-12 w-48 h-48 rounded-full bg-secondary/20 blur-3xl pointer-events-none"></div>
            <div class="absolute -left-12 -bottom-12 w-48 h-48 rounded-full bg-blue-500/10 blur-3xl pointer-events-none"></div>
            
            <!-- Header Kartu -->
            <div class="relative z-10 flex items-start justify-between border-b border-white/10 pb-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center p-1.5 shadow-inner border border-white/20">
                        <span class="material-symbols-outlined text-secondary-container text-[22px]">school</span>
                    </div>
                    <div class="flex flex-col">
                        <span class="font-headline-sm text-[12px] font-black tracking-wider uppercase text-white">UNIVERSITAS DUTA BANGSA</span>
                        <span class="text-[9px] text-white/70 uppercase tracking-widest font-semibold"><?= htmlspecialchars($mhs['fakultas'] ?? 'FAKULTAS ILMU KOMPUTER') ?></span>
                    </div>
                </div>

                <div class="flex items-center gap-1.5 text-white/80">
                    <span class="material-symbols-outlined text-[20px] text-secondary-container">contactless</span>
                    <span class="font-code-nim text-[9px] tracking-widest uppercase">NFC ID-1</span>
                </div>
            </div>

            <!-- Middle Identity Section -->
            <div class="relative z-10 flex items-center gap-4 my-auto">
                <!-- Foto Mahasiswa -->
                <div class="relative shrink-0">
                    <img 
                        class="w-20 h-26 rounded-2xl object-cover shadow-lg ring-2 ring-white/20 bg-white/10" 
                        src="<?= $avatar_src ?>" 
                        alt="Foto <?= htmlspecialchars($mhs['nama']) ?>"
                        onerror="this.src='<?= generate_avatar_svg($mhs['nama'], $mhs['jenis_kelamin'], $mhs['nim']) ?>'"
                    />
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-emerald-500 ring-2 ring-[#12161f] flex items-center justify-center text-white" title="Status Aktif">
                        <span class="material-symbols-outlined text-[13px]">check</span>
                    </div>
                </div>

                <!-- Informasi Biodata -->
                <div class="flex flex-col min-w-0 flex-1">
                    <span class="font-headline-sm text-[16px] text-white font-black tracking-tight leading-snug uppercase truncate">
                        <?= htmlspecialchars($mhs['nama']) ?>
                    </span>
                    <span class="font-code-nim text-secondary-container font-black text-sm tracking-wider mt-0.5">
                        <?= htmlspecialchars($mhs['nim']) ?>
                    </span>
                    <div class="mt-2 space-y-0.5">
                        <span class="text-[11px] text-white/90 font-semibold block truncate">
                            <?= htmlspecialchars($mhs['nama_prodi'] ?? 'S1 Teknik Informatika') ?>
                        </span>
                        <span class="text-[10px] text-white/60 block">
                            Angkatan <?= htmlspecialchars($mhs['angkatan']) ?> &bull; <?= htmlspecialchars($mhs['status_akademik']) ?>
                        </span>
                    </div>
                </div>

                <!-- Dynamic QR Code Container (Milestone 4 - qrcode.js) -->
                <div class="shrink-0 flex flex-col items-center justify-center bg-white p-1.5 rounded-xl shadow-md border border-white/20">
                    <div id="ktm-qrcode" class="w-16 h-16"></div>
                    <span class="text-[8px] font-code-nim font-bold text-black mt-0.5 uppercase tracking-tighter">VERIFIED</span>
                </div>
            </div>

            <!-- Card Bottom Strip -->
            <div class="relative z-10 flex items-end justify-between border-t border-white/10 pt-2.5">
                <div class="flex flex-col">
                    <span class="text-[8px] text-white/50 uppercase tracking-widest font-bold">Masa Berlaku Hingga</span>
                    <span class="font-code-nim text-[11px] text-white font-bold tracking-wider">OKT <?= $valid_until ?></span>
                </div>

                <div class="flex items-center gap-[2px] h-3.5 opacity-60">
                    <div class="w-[2px] h-full bg-white"></div>
                    <div class="w-[1px] h-full bg-white"></div>
                    <div class="w-[3px] h-full bg-white"></div>
                    <div class="w-[1px] h-full bg-white"></div>
                    <div class="w-[4px] h-full bg-white"></div>
                    <div class="w-[1px] h-full bg-white"></div>
                    <div class="w-[2px] h-full bg-white"></div>
                    <div class="w-[3px] h-full bg-white"></div>
                    <div class="w-[1px] h-full bg-white"></div>
                    <div class="w-[2px] h-full bg-white"></div>
                </div>
            </div>
        </div>

        <!-- ============================================== -->
        <!-- 2. KARTU SISI BELAKANG (BACK SIDE) -->
        <!-- ============================================== -->
        <div class="ktm-card-print relative w-full h-[320px] rounded-3xl bg-surface-container-lowest text-on-surface p-6 shadow-2xl border border-outline-variant/40 overflow-hidden flex flex-col justify-between select-none">
            
            <!-- Top Magnetic Stripe Simulation -->
            <div class="relative -mx-6 -mt-6 h-11 bg-primary mb-4 flex items-center justify-between px-6">
                <span class="font-code-nim text-[9px] text-white/50 tracking-widest">UDB-MAGNETIC-DATA-SECURITY-STRIPE</span>
                <span class="font-code-nim text-[9px] text-white/80 font-bold">CR80-ID1</span>
            </div>

            <!-- Terms & Regulation -->
            <div class="space-y-1.5 text-[10px] text-on-surface-variant leading-relaxed">
                <p class="font-bold text-primary text-[11px] uppercase tracking-wide">Ketentuan Penggunaan Kartu Mahasiswa:</p>
                <ol class="list-decimal pl-3 space-y-0.5">
                    <li>Kartu ini adalah identitas resmi mahasiswa aktif Universitas Duta Bangsa Surakarta.</li>
                    <li>Wajib dibawa saat mengikuti perkuliahan, praktikum, ujian, dan layanan perpustakaan.</li>
                    <li>Penyalahgunaan kartu dapat dikenakan sanksi akademik sesuai Statuta Universitas.</li>
                    <li>Bila kartu ini hilang atau rusak, segera hubungi Bagian Administrasi Akademik (BAAK).</li>
                </ol>
            </div>

            <!-- Signature & Seal Area -->
            <div class="flex items-end justify-between border-t border-outline-variant/30 pt-3 mt-auto">
                <div class="flex flex-col">
                    <span class="text-[9px] text-on-surface-variant">Surakarta, Indonesia</span>
                    <span class="text-[10px] text-primary font-bold">Rektor Universitas Duta Bangsa</span>
                    <div class="mt-2 flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full border border-secondary text-secondary flex items-center justify-center font-bold text-[8px] uppercase">
                            SEAL
                        </div>
                        <span class="font-code-nim text-[9px] text-on-surface-variant font-bold">REG-AUTHPASS-OK</span>
                    </div>
                </div>

                <div class="flex flex-col items-end">
                    <span class="font-code-nim text-[11px] font-bold text-primary">NIM: <?= htmlspecialchars($mhs['nim']) ?></span>
                    <span class="text-[8px] text-on-surface-variant">Sistem Informasi Registri Akademik</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Script Render QR Code via QRCode.js CDN -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qrContainer = document.getElementById('ktm-qrcode');
            if (qrContainer && typeof QRCode !== 'undefined') {
                new QRCode(qrContainer, {
                    text: '<?= addslashes($qr_payload) ?>',
                    width: 64,
                    height: 64,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.M
                });
            }
        });
    </script>
</body>
</html>

