# Sistem Informasi Biodata Mahasiswa Advance (Warm Editorial Stitch)

Sistem Informasi Registri dan Arsip Mahasiswa dengan arsitektur Native PHP 8+, MySQLi Prepared Statements, Tailwind CSS CDN, serta integrasi Leaflet GIS, SheetJS Excel Export, dan QRCode.js.

## Fitur Utama
1. **CRUD & Soft Delete**: Manajemen data mahasiswa dengan proteksi CSRF, sanitasi XSS, dan pencatatan riwayat ke `audit_logs`.
2. **Validasi File Biner**: Dual upload foto (JPG/PNG max 2MB) dan dokumen kelengkapan (PDF max 5MB) dengan pemeriksaan MIME biner asli (`finfo_file`).
3. **Peta Interaktif GIS (Leaflet.js)**: Pemetaan sebaran mahasiswa, auto-geocoding alamat domisili, dan drag-marker koordinat presisi.
4. **Ekspor Data Excel (SheetJS)**: Ekspor data langsung ke `.xlsx` dari frontend tanpa dependensi server tambahan.
5. **Cetak KTM Digital (QRCode.js)**: Kartu Tanda Mahasiswa siap cetak dengan QR Code dinamis berbasis NIM.
6. **RESTful API Endpoint**: Endpoint `api/mahasiswa.php` berformat JSON murni dan mendukung CORS untuk integrasi lintas platform.

## Cara Instalasi
1. Pindahkan atau buat junction folder ini ke direktori web server (misal: `C:\xampp\htdocs\3SistemDataMahasiswa`).
2. Nyalakan **Apache** dan **MySQL** di XAMPP Control Panel.
3. Impor skema database dari file `database.sql` via phpMyAdmin atau MySQL CLI.
4. Akses sistem melalui browser di `http://localhost/3SistemDataMahasiswa/` (atau port sesuai konfigurasi Apache Anda).

