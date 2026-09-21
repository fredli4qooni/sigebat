# SIGEBAT — Sistem Informasi Manajemen Desa Wisata Kampung Gedung Batin

> **Sistem Informasi Manajemen Desa Wisata Kampung Gedung Batin berbasis Location Based Services (LBS) dan Kalender Event Budaya Digital.**  
> Dikembangkan untuk kawasan Cagar Budaya Desa Wisata Kampung Gedung Batin, Kecamatan Umpu Semenguk, Kabupaten Way Kanan, Provinsi Lampung.

---

## 📌 Ringkasan Proyek

SIGEBAT mengintegrasikan penyebaran informasi cagar budaya rumah panggung adat Pepadun, fasilitas penunjang desa, pencarian destinasi wisata terdekat berbasis posisi pengguna (*Location Based Services* / LBS), dan kalender event budaya digital tahunan dalam satu platform web yang responsif, cepat, serta aman.

### Arsitektur & Teknologi:
- **Backend Framework**: [Laravel 11](https://laravel.com) (PHP 8.3)
- **Database**: MySQL 8 (`utf8mb4_unicode_ci`)
- **Frontend & Styling**: Blade Components + [Tailwind CSS v3](https://tailwindcss.com) (Vite Bundler)
- **Desain UI/UX**: Sistem Desain *"Rambu Kampung"* (Tegas, berani, tanpa gradien halus/glassmorphism, kontras tinggi, mobile-first)
- **Interaktivitas Ringan**: [Alpine.js](https://alpinejs.dev)
- **Peta & LBS**: [Leaflet.js](https://leafletjs.com) + OpenStreetMap (OSM) Tiles + Geolocation API + Formula Jarak Haversine (*Client-Side Engine*)
- **Sinkronisasi Kalender**: Google Calendar Link Generator & Ekspor Standar iCalendar RFC 5545 (`.ics`)
- **Autentikasi & Otorisasi**: Role-Based Access Control (Admin, Pengelola, Wisatawan Publik) dengan verifikasi status akun (`pending`, `aktif`, `nonaktif`, `ditolak`)
- **Audit Logging**: Pencatatan otomatis riwayat aktivitas login, verifikasi, serta penambahan, perubahan, dan penghapusan data master/konten

---

## 🗺️ Fitur Utama Sistem

### 1. Portal Publik Wisatawan (Tanpa Login)
- **Beranda Interaktif (`/`)**: Hero visual cagar budaya, ringkasan statistik aktif, 6 destinasi unggulan, banner edukasi LBS, dan 3 agenda event budaya terdekat.
- **Katalog Wisata & Engine LBS (`/wisata`)**: Pencarian kata kunci, penyaringan kategori adat/alam/sejarah, serta tombol *Hitung Jarak dari Lokasi Saya* yang menghitung jarak garis lurus (*Haversine*) langsung di browser pengguna tanpa melanggar privasi.
- **Rincian Objek Wisata (`/wisata/{slug}`)**: Narasi sejarah mendalam, jam buka, harga tiket, daftar sarana fasilitas pendukung, mini-peta Leaflet, dan tombol *Buka Rute di Google Maps* (deep link).
- **Peta Interaktif LBS (`/peta`)**: Peta sebaran seluruh objek wisata dan fasilitas desa, tombol GPS *Temukan Lokasi Saya*, rekomendasi otomatis destinasi terdekat, serta layer toggle fasilitas.
- **Direktori Fasilitas Desa (`/fasilitas`)**: Inventaris sarana umum desa dan fasilitas objek wisata.
- **API Read-Only (`/api/wisata`)**: GeoJSON FeatureCollection berstandar GIS.

### 2. Kalender Event Budaya Digital
- **Kalender Bulanan (`/kalender`)**: Matriks 7 kolom (Senin s.d. Minggu), penanda tanggal kegiatan adat, dukungan event multi-hari, filter kategori, navigasi bulan/tahun, dan toggle mode kalender vs daftar agenda.
- **Detail Event Budaya (`/event/{slug}`)**: Poster resolusi tinggi, status turunan otomatis (*Akan datang*, *Berlangsung*, *Selesai*), integrasi tombol *Simpan di Google Calendar*, unduh file pengingat kalender (`.ics`), dan tombol bagikan cepat ke WhatsApp.

### 3. Panel Pengelola Wisata (`/pengelola`)
- Dashboard analitik ringkas khusus pengelola desa wisata.
- Kelola konten objek wisata (dengan Leaflet interactive map coordinate picker dan unggah foto).
- Kelola fasilitas penunjang (fasilitas umum desa vs fasilitas khusus objek wisata).
- Kelola event budaya (dukungan multi-hari dan status publikasi).

### 4. Panel Administrator (`/admin`)
- Dashboard pengawasan data master dan antrean verifikasi pengelola.
- Kelola Data Master (Kategori Wisata, Kategori Event, Jenis Fasilitas) dengan proteksi penghapusan berelasi.
- Manajemen Akun Pengguna (Admin & Pengelola) dengan proteksi akun mandiri dan proteksi penghapusan admin terakhir.
- Antrean verifikasi calon pengelola (setujui / tolak dengan pencatatan alasan penolakan).
- Audit log aktivitas terfilter (pengguna, tipe aksi, rentang tanggal, alamat IP).

---

## ⚙️ Persyaratan Sistem

- PHP >= 8.3
- Ekstensi PHP: `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd` / `imagick`, `curl`
- Composer >= 2.x
- Node.js >= 18.x & NPM
- MySQL Server >= 8.0 / MariaDB >= 10.4

---

## 🚀 Panduan Instalasi Lokal

### 1. Kloning Repositori
```bash
git clone https://github.com/fredli4qooni/sigebat.git
cd sigebat
```

### 2. Instal Dependensi Backend & Frontend
```bash
composer install
npm install
```

### 3. Konfigurasi Lingkungan (`.env`)
Salin file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Sesuaikan konfigurasi koneksi database MySQL pada `.env`:
```env
APP_NAME=SIGEBAT
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Jakarta
APP_URL=http://localhost:8000
APP_LOCALE=id

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sigebat
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Kunci Aplikasi & Symlink Storage
```bash
php artisan key:generate
php artisan storage:link
```

### 5. Jalankan Migrasi & Seeder Awal
Perintah ini akan membuat struktur tabel dan mengisi data master, akun default, objek wisata bersejarah, fasilitas desa, serta agenda budaya Kampung Gedung Batin:
```bash
php artisan migrate --seed
```

### 6. Kompilasi Aset Frontend
Jalankan dev server Vite:
```bash
npm run dev
```
Atau kompilasi aset untuk produksi:
```bash
npm run build
```

### 7. Jalankan Server Aplikasi
```bash
php artisan serve
```
Buka browser di [http://localhost:8000](http://localhost:8000).

---

## 🔑 Kredensial Default Pengguna (Database Seeder)

| Peran | Email | Kata Sandi | Status Akun | Hak Akses |
|---|---|---|---|---|
| **Administrator** | `admin@gedungbatin.desa.id` | `admin123` | `aktif` | Mengelola data master, pengguna, verifikasi, log audit (`/admin`) |
| **Pengelola Wisata** | `pengelola@gedungbatin.desa.id` | `pengelola123` | `aktif` | Mengelola objek wisata, fasilitas, dan event budaya (`/pengelola`) |
| **Calon Pengelola** | `calon@gedungbatin.desa.id` | `password` | `pending` | Menunggu verifikasi persetujuan admin di panel verifikasi |
| **Wisatawan Publik** | *(Tanpa Login)* | — | — | Akses bebas seluruh halaman informasi, LBS, peta, dan kalender |

---

## 🧪 Pengujian Otomatis (Feature Tests)

Proyek ini dilengkapi cakupan pengujian otomatis PHPUnit menyeluruh yang memvalidasi seluruh skenario fungsional PRD (Must & Should):

```bash
# Menjalankan seluruh test suite (75 skenario)
php artisan test --compact

# Menjalankan pengujian spesifik per modul
php artisan test tests/Feature/AuthAndRbacTest.php
php artisan test tests/Feature/AdminPanelTest.php
php artisan test tests/Feature/PengelolaPanelTest.php
php artisan test tests/Feature/PublicPortalTest.php
php artisan test tests/Feature/PublicKalenderTest.php
```

### Pemeriksaan Standar Gaya Kode (Laravel Pint):
```bash
vendor/bin/pint --dirty --format agent
```

---

## 📋 Checklist Kesiapan Rilis Produksi (Go-Live)

- [x] Arsitektur Role-Based Access Control (Admin, Pengelola, Wisatawan Publik).
- [x] Engine LBS client-side dengan kalkulasi Haversine akurat dan deep link Google Maps.
- [x] Kalender event budaya bulanan interaktif 7 kolom dengan ekspor Google Calendar dan iCal `.ics`.
- [x] Penanganan responsif pada seluruh resolusi layar (Mobile 320px s.d. Desktop 1440px).
- [x] Optimasi cache rute dan konfigurasi (`php artisan route:cache`, `php artisan config:cache`).
- [x] Metadata SEO, OpenGraph, dan Twitter Card untuk pratinjau media sosial / WhatsApp.
- [x] Perlindungan CSRF, XSS escaping, validasi koordinat geografis, serta sanitasi file upload.
- [x] Atribusi resmi peta OpenStreetMap (`© OpenStreetMap contributors`).
- [x] Zona waktu terkonfigurasi ke Waktu Indonesia Barat (`Asia/Jakarta`).

---

## 📄 Lisensi & Hak Cipta

Dikembangkan untuk kepentingan pelestarian cagar budaya dan pengembangan Desa Wisata Kampung Gedung Batin, Kabupaten Way Kanan, Provinsi Lampung. Lisensi di bawah [MIT License](LICENSE).
