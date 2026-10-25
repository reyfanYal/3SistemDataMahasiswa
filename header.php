<?php
require_once __DIR__ . '/koneksi.php';

$current_page = basename($_SERVER['PHP_SELF']);
$csrf_token = generate_csrf_token();
$flash = get_flash();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' - ' : '' ?>SIM Biodata Mahasiswa Advance</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700;900&family=Space+Mono:wght@700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "on-primary-fixed-variant": "#424751",
                        "on-surface": "#171c23",
                        "surface-dim": "#d6dae4",
                        "surface-container-low": "#eff4fe",
                        "primary": "#000208",
                        "tertiary-container": "#00194c",
                        "outline": "#76777c",
                        "primary-container": "#181d26",
                        "on-secondary": "#ffffff",
                        "on-secondary-fixed": "#3b0900",
                        "secondary": "#af3003",
                        "background": "#f8f9ff",
                        "inverse-primary": "#c2c6d3",
                        "on-error": "#ffffff",
                        "primary-fixed": "#dee2ef",
                        "error": "#ba1a1a",
                        "on-tertiary-fixed": "#001849",
                        "on-background": "#171c23",
                        "inverse-surface": "#2c3139",
                        "surface-bright": "#f8f9ff",
                        "on-tertiary-container": "#5c81e1",
                        "outline-variant": "#c6c6cc",
                        "on-secondary-container": "#601500",
                        "on-tertiary-fixed-variant": "#0e409e",
                        "on-primary-fixed": "#171c25",
                        "inverse-on-surface": "#edf1fb",
                        "surface-container-highest": "#dee2ed",
                        "on-surface-variant": "#45474b",
                        "surface": "#f8f9ff",
                        "tertiary-fixed": "#dae1ff",
                        "secondary-fixed-dim": "#ffb5a0",
                        "error-container": "#ffdad6",
                        "on-error-container": "#93000a",
                        "on-primary-container": "#808590",
                        "on-tertiary": "#ffffff",
                        "tertiary": "#00020e",
                        "surface-tint": "#5a5e69",
                        "primary-fixed-dim": "#c2c6d3",
                        "on-primary": "#ffffff",
                        "surface-container-high": "#e4e8f3",
                        "tertiary-fixed-dim": "#b3c5ff",
                        "secondary-fixed": "#ffdbd1",
                        "surface-container": "#eaeef8",
                        "secondary-container": "#ff693c",
                        "on-secondary-fixed-variant": "#862200",
                        "surface-variant": "#dee2ed",
                        "surface-container-lowest": "#ffffff"
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                    spacing: {
                        "space-xs": "0.25rem",
                        "space-sm": "0.5rem",
                        "space-md": "1rem",
                        "space-lg": "1.5rem",
                        "space-xl": "2.5rem",
                        "gutter": "1.5rem",
                        "margin": "2rem",
                        "gutter-mobile": "1rem",
                        "margin-mobile": "1rem"
                    },
                    fontFamily: {
                        "code-nim": ["'Space Mono'", "monospace"],
                        "label-field": ["Inter", "sans-serif"],
                        "headline-md": ["'Space Grotesk'", "sans-serif"],
                        "headline-lg-mobile": ["'Space Grotesk'", "sans-serif"],
                        "display-hero": ["'Space Grotesk'", "sans-serif"],
                        "display-hero-mobile": ["'Space Grotesk'", "sans-serif"],
                        "headline-sm": ["'Space Grotesk'", "sans-serif"],
                        "body-lg": ["Inter", "sans-serif"],
                        "headline-lg": ["'Space Grotesk'", "sans-serif"],
                        "body-sm": ["Inter", "sans-serif"],
                        "badge-label": ["Inter", "sans-serif"],
                        "body-md": ["Inter", "sans-serif"]
                    },
                    fontSize: {
                        "code-nim": ["13px", { lineHeight: "18px", letterSpacing: "0.05em", fontWeight: "700" }],
                        "label-field": ["12px", { lineHeight: "16px", letterSpacing: "0.02em", fontWeight: "600" }],
                        "headline-md": ["24px", { lineHeight: "30px", letterSpacing: "-0.01em", fontWeight: "600" }],
                        "headline-lg-mobile": ["26px", { lineHeight: "32px", letterSpacing: "-0.01em", fontWeight: "700" }],
                        "display-hero": ["48px", { lineHeight: "54px", letterSpacing: "-0.03em", fontWeight: "900" }],
                        "display-hero-mobile": ["34px", { lineHeight: "40px", letterSpacing: "-0.02em", fontWeight: "900" }],
                        "headline-sm": ["18px", { lineHeight: "24px", letterSpacing: "0", fontWeight: "600" }],
                        "body-lg": ["16px", { lineHeight: "26px", fontWeight: "400" }],
                        "headline-lg": ["32px", { lineHeight: "38px", letterSpacing: "-0.02em", fontWeight: "700" }],
                        "body-sm": ["12px", { lineHeight: "18px", fontWeight: "400" }],
                        "badge-label": ["11px", { lineHeight: "14px", letterSpacing: "0.04em", fontWeight: "600" }],
                        "body-md": ["14px", { lineHeight: "22px", fontWeight: "400" }]
                    }
                }
            }
        };
    </script>
    <!-- Leaflet.js CSS & JS CDN (GIS Peta Interaktif) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <style>
        @layer base {
            html, body { margin: 0; padding: 0; }
            body { overscroll-behavior: none; }
            main > :first-child { margin-top: 0 !important; }
            main > :last-child { margin-bottom: 0 !important; }
        }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #eff4fe; }
        ::-webkit-scrollbar-thumb { background: #c6c6cc; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #76777c; }

        /* Leaflet custom styling */
        .leaflet-popup-content-wrapper {
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            font-family: 'Inter', sans-serif;
            background: #ffffff;
            border: 1px solid #dee2ed;
        }
        .leaflet-popup-tip {
            background: #ffffff;
        }
    </style>
</head>
<body class="bg-surface font-body-md text-on-surface antialiased selection:bg-secondary selection:text-white">

<!-- TOP HEADER BAR -->
<header class="fixed top-0 left-0 right-0 z-50 bg-surface-container-lowest border-b border-on-surface/10 shadow-[0_1px_8px_rgba(0,0,0,0.04)]">
    <div class="h-20 max-w-[1440px] mx-auto px-margin flex items-center justify-between gap-space-lg">
        
        <!-- Logo & Branding -->
        <a href="index.php" class="flex items-center gap-space-md shrink-0 group">
            <div class="w-10 h-10 rounded-xl bg-primary text-on-primary flex items-center justify-center font-headline-sm font-black text-lg tracking-wider shadow-sm group-hover:scale-105 transition-transform">
                <span class="material-symbols-outlined text-[24px]">school</span>
            </div>
            <div class="flex flex-col">
                <div class="flex items-center gap-space-sm">
                    <span class="font-headline-sm text-headline-sm text-primary tracking-tight font-black">SIM Biodata Mahasiswa</span>
                    <span class="font-badge-label text-badge-label bg-surface-container px-space-sm py-0.5 rounded-full text-on-surface-variant font-semibold tracking-wide uppercase">Akademik v2.4</span>
                </div>
                <span class="font-body-sm text-body-sm text-on-surface-variant">Sistem Informasi Arsip &amp; Registri Akademik</span>
            </div>
        </a>

        <!-- Search Bar Header -->
        <div class="flex-1 max-w-xl hidden md:block">
            <div class="relative flex items-center w-full">
                <span class="material-symbols-outlined absolute left-space-md text-on-surface-variant text-[20px] pointer-events-none">search</span>
                <input id="header-search-input" class="w-full bg-surface-container pl-11 pr-space-md py-space-sm rounded-full font-body-sm text-body-sm text-on-surface placeholder:text-on-surface-variant/70 border-0 focus:outline-none focus:ring-1 focus:ring-primary shadow-inner" placeholder="Cari mahasiswa berdasarkan NIM, nama, atau prodi... [Alt+K]" type="text"/>
            </div>
        </div>

        <!-- Right Header Stats & Profile -->
        <div class="flex items-center gap-space-md shrink-0">
            <div class="hidden xl:flex items-center gap-space-xs bg-surface-container px-space-md py-space-xs rounded-lg border border-outline-variant/40">
                <span class="material-symbols-outlined text-[18px] text-secondary">calendar_today</span>
                <span class="font-label-field text-label-field text-on-surface-variant uppercase tracking-wider">Semester</span>
                <span class="font-code-nim text-code-nim text-primary ml-space-xs">Ganjil 2024/2025</span>
            </div>

            <button aria-label="Notifikasi" class="relative p-space-sm rounded-full text-on-surface-variant hover:bg-surface-container hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined text-[22px]">notifications</span>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-secondary"></span>
            </button>

            <div class="h-7 w-[1px] bg-outline-variant/60 hidden sm:block"></div>

            <div class="flex items-center gap-space-sm">
                <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary flex items-center justify-center font-bold text-xs ring-1 ring-outline-variant">
                    RA
                </div>
                <div class="hidden lg:flex flex-col text-left">
                    <span class="font-label-field text-label-field text-primary leading-tight">Dr. Ratna S. Dewi, M.Kom</span>
                    <span class="font-body-sm text-body-sm text-on-surface-variant text-[11px] leading-tight">Registrar Admin</span>
                </div>
            </div>

            <a class="font-label-field text-label-field text-on-surface-variant hover:text-secondary transition-colors ml-space-xs pl-space-xs hidden sm:inline-flex items-center gap-1" href="index.php" title="Refresh Sesi">
                <span class="material-symbols-outlined text-[16px]">sync</span>
                <span>Aktif</span>
            </a>
        </div>
    </div>
</header>

<!-- FIXED LEFT SIDEBAR -->
<aside class="fixed left-0 top-20 bottom-0 w-64 bg-surface-container-lowest border-r border-on-surface/10 z-40 flex flex-col py-space-lg">
    <div class="px-space-lg mb-space-md">
        <span class="font-badge-label text-badge-label uppercase text-on-surface-variant tracking-wider">Registri Dossier</span>
    </div>

    <nav class="flex-1 px-space-md space-y-1 overflow-y-auto">
        <a href="index.php" class="flex items-center gap-space-md px-space-md py-space-sm rounded-lg transition-colors <?= ($current_page === 'index.php') ? 'bg-primary-container text-on-primary font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' ?>">
            <span class="material-symbols-outlined text-[20px]">folder_shared</span>
            <span class="font-body-md text-body-md">Direktori Mahasiswa</span>
        </a>

        <a href="tambah.php" class="flex items-center gap-space-md px-space-md py-space-sm rounded-lg transition-colors <?= ($current_page === 'tambah.php' || $current_page === 'edit.php') ? 'bg-primary-container text-on-primary font-bold shadow-sm' : 'text-on-surface-variant hover:bg-surface-container hover:text-on-surface' ?>">
            <span class="material-symbols-outlined text-[20px]">person_add</span>
            <span class="font-body-md text-body-md">Registrasi Biodata</span>
        </a>

        <div class="pt-space-md mt-space-md border-t border-outline-variant/30">
            <span class="px-space-md font-badge-label text-badge-label uppercase text-on-surface-variant tracking-wider block mb-space-sm">Fitur Lanjutan</span>
            
            <a href="index.php" class="flex items-center gap-space-md px-space-md py-space-sm rounded-lg text-primary hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-[20px] text-emerald-600">manage_search</span>
                <span class="font-body-md text-body-md font-medium">Live Search &amp; Filter</span>
            </a>

            <a href="index.php#map-mahasiswa" class="flex items-center gap-space-md px-space-md py-space-sm rounded-lg text-primary hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-[20px] text-secondary">explore</span>
                <span class="font-body-md text-body-md font-medium">Peta GIS &amp; Ekspor</span>
            </a>

            <a href="cetak_ktm.php" class="flex items-center gap-space-md px-space-md py-space-sm rounded-lg text-primary hover:bg-surface-container transition-colors">
                <span class="material-symbols-outlined text-[20px] text-primary">badge</span>
                <span class="font-body-md text-body-md font-medium">Cetak KTM &amp; QR</span>
            </a>

            <a href="api/mahasiswa.php" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between px-space-md py-space-sm rounded-lg text-primary hover:bg-surface-container transition-colors group" title="Buka RESTful API Mahasiswa (JSON)">
                <div class="flex items-center gap-space-md">
                    <span class="material-symbols-outlined text-[20px] text-indigo-600">api</span>
                    <span class="font-body-md text-body-md font-medium">RESTful API</span>
                </div>
                <span class="material-symbols-outlined text-[16px] text-on-surface-variant group-hover:text-primary transition-colors">open_in_new</span>
            </a>
        </div>
    </nav>

    <!-- Bottom Database Status Pill -->
    <div class="px-space-lg pt-space-md border-t border-outline-variant/30 text-on-surface-variant">
        <div class="flex items-center gap-space-xs text-[11px] font-code-nim">
            <span class="w-2 h-2 rounded-full bg-secondary-container inline-block animate-pulse"></span>
            <span>DATABASE READY (MySQLi)</span>
        </div>
    </div>
</aside>

<!-- MAIN APP SURFACE WRAPPER -->
<div class="pl-64">
    <main class="relative pt-20 w-full min-h-screen bg-surface">
        <!-- Notification / Flash Banner Container -->
        <?php if ($flash): ?>
            <div class="max-w-[1440px] mx-auto px-margin pt-4">
                <div class="rounded-xl p-4 shadow-sm border flex items-center justify-between gap-4 <?= ($flash['type'] === 'success') ? 'bg-emerald-50 border-emerald-200 text-emerald-900' : 'bg-error-container/40 border-error/30 text-on-error-container' ?>">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[24px] <?= ($flash['type'] === 'success') ? 'text-emerald-700' : 'text-error' ?>">
                            <?= ($flash['type'] === 'success') ? 'check_circle' : 'error' ?>
                        </span>
                        <div>
                            <span class="font-label-field font-bold block text-sm">
                                <?= ($flash['type'] === 'success') ? 'Operasi Berhasil Diproses' : 'Perhatian / Terjadi Kesalahan' ?>
                            </span>
                            <span class="text-body-sm text-sm">
                                <?= htmlspecialchars($flash['message']) ?>
                            </span>
                        </div>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="p-1 rounded-lg hover:bg-black/5 text-current transition-colors">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>
            </div>
        <?php endif; ?>

