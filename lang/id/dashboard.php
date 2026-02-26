<?php

return [
    // Bagian Selamat Datang
    'welcome' => [
        'admin' => 'Selamat datang, :name!',
        'user' => 'Selamat datang, :name!',
        'subtitle' => 'Ringkasan aktivitas laboratorium hari ini.',
    ],

    // Metrik
    'metrics' => [
        'total_items' => 'TOTAL ITEM',
        'total_users' => 'TOTAL PENGGUNA',
        'transactions_this_month' => 'TRANSAKSI BULAN INI',
        'items' => 'Item',
        'users' => 'Pengguna',
        'transactions' => 'Transaksi',
    ],

    // Aksi Cepat
    'quick_actions' => [
        'title' => 'Aksi Cepat',
        'add_item' => 'Tambah Item',
        'process_loan' => 'Proses Peminjaman',
        'view_reports' => 'Lihat Laporan',
    ],

    // Kartu Aktivitas
    'cards' => [
        'pending_loans' => 'Peminjaman Pending',
        'needs_approval' => 'Perlu persetujuan',
        'pending_bookings' => 'Booking Pending',
        'waiting_schedule' => 'Jadwal menunggu',
        'damage_reports' => 'Laporan Kerusakan',
        'needs_verification' => 'Perlu verifikasi',
        'this_week_schedule' => 'Jadwal Minggu Ini',
        'scheduled_practicum' => 'Praktikum terjadwal',
        'view_all' => 'Lihat Semua',
        'no_data' => 'Tidak ada data',
        'low_stock_title' => 'Stok Menipis',
        'low_stock_desc' => ':count item memerlukan restok segera.',
        'overdue_loans_title' => 'Peminjaman Terlambat',
        'overdue_loans_desc' => ':count peminjaman melewati batas waktu.',
        'take_action' => 'Tindak Lanjuti',
    ],

    // Aktivitas Terbaru
    'recent_activity' => [
        'title' => 'Aktivitas Terbaru',
        'description' => 'Lihat semua aktivitas terbaru di sistem, peminjaman, booking, dan perubahan data.',
        'page' => 'Halaman',
        'of' => 'dari',
        'no_activity' => 'Tidak ada aktivitas terbaru',
        'view_more' => 'Lihat Lebih Banyak',
        'previous' => 'Sebelumnya',
        'next' => 'Selanjutnya',
    ],

    // Jenis Aktivitas
    'activity' => [
        'loan_created' => 'mengajukan peminjaman alat',
        'booking_created' => 'mengajukan booking lab untuk ":purpose"',
        'item_added' => 'menambahkan item baru',
        'item_updated' => 'memperbarui item',
        'user_login' => 'login ke sistem',
        'no_activity' => 'Belum ada aktivitas terbaru.',
        'view_all' => 'Lihat Semua Aktivitas',
        'view' => 'Lihat',
        'detail' => 'Detail',
        'system' => 'Sistem',
    ],
];
