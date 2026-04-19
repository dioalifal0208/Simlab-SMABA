<p align="center">
    <img src="public/images/logo-smaba.webp" width="120" alt="Logo SMABA">
</p>

<h1 align="center">SIMLAB SMABA v2.5</h1>

<p align="center">
    <strong>Sistem Informasi Manajemen Laboratorium Modern & Terintegrasi</strong><br>
    SMA Negeri 1 Babat
</p>

<p align="center">
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel" alt="Laravel 12"></a>
    <a href="https://livewire.laravel.com"><img src="https://img.shields.io/badge/Livewire-3.x-FB70A9?style=for-the-badge&logo=livewire" alt="Livewire 3"></a>
    <a href="https://tailwindcss.com"><img src="https://img.shields.io/badge/Tailwind-CSS-38B2AC?style=for-the-badge&logo=tailwind-css" alt="Tailwind CSS"></a>
    <a href="https://alpinejs.dev"><img src="https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js" alt="Alpine.js"></a>
</p>

---

## 📖 Tentang Aplikasi

**SIMLAB SMABA** adalah platform tata kelola laboratorium digital yang dirancang khusus untuk memenuhi standar modern operasional sekolah. Menggabungkan performa **Laravel 12** dan interaktivitas **Livewire 3**, sistem ini menyediakan solusi *end-to-end* mulai dari manajemen aset, penjadwalan ruang, hingga sistem keamanan tingkat tinggi yang menjaga integritas data laboratorium SMA Negeri 1 Babat.

## ✨ Fitur Unggulan

### 1. 🛡️ Keamanan & Integritas Data (Enterprise Grade)
- **Multi-Layer Authentication**: Mendukung **Two-Factor Authentication (2FA)** via Google Authenticator untuk akun Guru dan Admin.
- **Audit Trails (Log Aktivitas)**: Pencatatan otomatis setiap aksi *Create, Update, Delete* beserta data IP dan User Agent untuk transparansi penuh.
- **Dynamic Lock Screen**: Penguncian otomatis berbasis waktu *idle* dengan efek blur estetik untuk keamanan sesi aktif.
- **Single Session Control**: Mencegah login ganda pada akun yang sama untuk meminimalisir risiko penyalahgunaan akun.

### 2. ⚡ Interaktivitas & Real-Time
- **Live Communication (Pusher)**: Sistem chat bantuan langsung ke Admin dengan indikator *Real-time Typing* dan notifikasi instan.
- **Global Search Cerdas**: Navigasi instan ke modul, alat, atau dokumen melalui bilah pencarian universal.
- **Interactive Product Tour**: Panduan langkah demi langkah (Shepherd.js) bagi pengguna baru untuk memahami fungsi aplikasi.

### 3. 🗓️ Penjadwalan & Peminjaman Digital
- **Kalender Interaktif (v6)**: Visualisasi penuh jadwal penggunaan lab untuk menghindari konflik praktikum.
- **Workflow Persetujuan**: Alur pengajuan peminjaman alat dan ruang yang sepenuhnya digital (Paperless).
- **Validasi QR Code**: Sertifikat atau surat peminjaman dilengkapi QR Code unik untuk validasi keaslian dokumen secara publik.

### 4. 📦 Manajemen Inventaris Lanjutan
- **Bulk Operations**: Kemampuan impor data masal via Excel dengan sistem *Template Generator* otomatis.
- **Smart Tracking**: Manajemen stok minimum untuk bahan habis pakai dan riwayat kerusakan alat.
- **Maintenance Log**: Pencatatan riwayat pemeliharaan rutin untuk menjaga kualitas aset sekolah.

### 5. 📚 Pusat Pengetahuan & Pustaka
- **Modul Praktikum Digital**: Penyimpanan terpusat untuk SOP, modul ajar, dan panduan teknis laboratorium.
- **Streaming Document**: Preview dokumen (PDF/Word/Excel) langsung di browser tanpa harus mengunduh.

### 6. 📊 Analitik & Reporting
- **Dynamic Stats Dashboard**: Visualisasi data inventaris dan aktivitas bulanan menggunakan Chart.js.
- **One-Click Export**: Menghasilkan laporan resmi dalam format Excel (.xlsx) sesuai standar administrasi sekolah.

## 🛠️ Stack Teknologi

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Frontend Interactivity**: Livewire 3.6, Alpine.js 3.x
- **Styling**: Tailwind CSS (Modern Glassmorphism UI)
- **Real-time**: Pusher Channels & Laravel Echo
- **Data Visual**: Chart.js, FullCalendar 6
- **Storage**: App Storage with Intervention Image 3

## 🚀 Instalasi Cepat

1. **Clone & Install**
   ```bash
   git clone https://github.com/dioalifal0208/Simlab-SMABA.git
   cd Simlab-SMABA
   composer install
   npm install && npm run build
   ```

2. **Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --seed
   php artisan storage:link
   ```

3. **Running Environment**
   ```bash
   # Gunakan shortcut composer yang sudah dikonfigurasi
   composer dev
   ```

---

<p align="center">
    Dikembangkan dengan dedikasi untuk <strong>SMA Negeri 1 Babat</strong> © 2026
</p>
