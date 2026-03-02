<p align="center">
    <img src="public/images/logo-smaba.webp" width="120" alt="Logo SMABA">
</p>

<h1 align="center">SIMLAB SMABA v2.0</h1>

<p align="center">
    <strong>Sistem Informasi Manajemen Laboratorium Modern & Terintegrasi</strong><br>
    SMA Negeri 1 Babat
</p>

<p align="center">
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 11"></a>
    <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css" alt="Tailwind CSS"></a>
    <a href="https://alpinejs.dev"><img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js" alt="Alpine.js"></a>
</p>

---

## 📖 Tentang Aplikasi

**SIMLAB SMABA** adalah platform digital mutakhir yang dirancang untuk merevolusi tata kelola laboratorium di lingkungan pendidikan. Berfokus pada efisiensi, transparansi, dan keamanan, sistem ini mengintegrasikan seluruh aspek manajemen lab—dari inventarisasi aset hingga penjadwalan praktikum—dalam satu ekosistem digital yang modern.

## ✨ Fitur Unggulan

### 1. 🛡️ Keamanan Berlapis (Security First)
- **Two-Factor Authentication (2FA)**: Proteksi akun tambahan menggunakan Google Authenticator.
- **Dynamic Lock Screen**: Penguncian layar otomatis saat sesi tidak aktif dengan efek blur untuk privasi data.
- **Audit Trails**: Pencatatan setiap aktivitas penting dalam sistem untuk transparansi penuh.

### 2. 🗓️ Penjadwalan & Peminjaman Cerdas
- **Kalender Interaktif**: Visualisasi jadwal laboratorium yang transparan untuk mencegah konflik penggunaan.
- **Workflow Persetujuan Digital**: Alur kerja dari pengajuan oleh guru hingga persetujuan Kepala Lab yang sepenuhnya paperless.
- **Validasi QR Code**: Setiap surat peminjaman dilengkapi dengan QR Code unik yang dapat divalidasi secara publik.

### 3. 📦 Manajemen Aset Terintegrasi
- **Inventaris Digital**: Database terpusat untuk alat dan bahan dengan pelacakan stok real-time.
- **Laporan Kerusakan**: Sistem pelaporan masalah alat yang terintegrasi untuk mempercepat proses pemeliharaan.
- **Maintenance Log**: Riwayat pemeliharaan alat untuk menjaga kualitas aset sekolah.

### 4. 📚 Pustaka & Pusat Pengetahuan
- **Modul Praktikum Digital**: Penyimpanan terpusat untuk modul, panduan praktikum, dan SOP laboratorium.
- **Multi-Format Support**: Mendukung dokumen PDF, Word, dan Excel dengan akses yang mudah bagi guru dan siswa.

### 5. 📊 Analitik & Reporting Otomatis
- **Real-time Landing Stats**: Statistik jumlah alat, guru, dan aktivitas yang diambil langsung dari database.
- **One-Click Export**: Menghasilkan laporan penggunaan lab dan peminjaman alat dalam format Excel (.xlsx) secara instan.

### 6. 🌐 Pengalaman Pengguna Premium
- **Interactive Product Tour**: Panduan visual 6 langkah untuk membantu pengguna baru memahami alur aplikasi.
- **Bilingual Support**: Dukungan penuh bahasa Indonesia dan Inggris (Full I18n).
- **Modern Responsive UI**: Desain yang bersih, profesional, dan dioptimalkan untuk berbagai perangkat.

## 🛠️ Stack Teknologi

- **Core**: Laravel 11, PHP 8.2+
- **Frontend**: Blade Components, Tailwind CSS (Modern Theme), Alpine.js
- **Database**: MySQL / MariaDB
- **Key Libraries**:
    - `pragmarx/google2fa` (Security)
    - `simplesoftwareio/simple-qrcode` (Verification)
    - `maatwebsite/excel` (Reporting)
    - `fullcalendar` (Scheduling)
    - `AOS.js` (Animations)

## 🚀 Instalasi & Pengembangan

1. **Persyaratan Sistem**
    - PHP >= 8.2
    - Composer
    - Node.js & NPM

2. **Langkah Instalasi**
   ```bash
   # Clone repository
   git clone https://github.com/dioalifal0208/Simlab-SMABA.git
   cd Simlab-SMABA

   # Install dependencies
   composer install
   npm install && npm run build

   # Environment Setup
   cp .env.example .env
   php artisan key:generate

   # Database Migration & Seeding
   php artisan migrate --seed
   ```

3. **Menjalankan Server**
   ```bash
   php artisan serve
   ```

---

<p align="center">
    Dikembangkan dengan ❤️ untuk <strong>SMA Negeri 1 Babat</strong> © 2026
</p>
