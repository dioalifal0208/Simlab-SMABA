<?php

return [
    // Judul Halaman
    'title' => 'Booking Lab',
    'create_booking' => 'Buat Booking',
    'edit_booking' => 'Edit Booking',
    'booking_details' => 'Detail Booking',

    // Header Tabel
    'table' => [
        'lab' => 'Laboratorium',
        'user' => 'Pengguna',
        'date' => 'Tanggal',
        'start_time' => 'Waktu Mulai',
        'end_time' => 'Waktu Selesai',
        'purpose' => 'Tujuan',
        'status' => 'Status',
        'actions' => 'Aksi',
    ],

    // Label Form
    'form' => [
        'lab' => 'Laboratorium',
        'date' => 'Tanggal',
        'start_time' => 'Waktu Mulai',
        'end_time' => 'Waktu Selesai',
        'purpose' => 'Tujuan',
        'notes' => 'Catatan',
        'participants' => 'Jumlah Peserta',
    ],

    // Status
    'status' => [
        'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'completed' => 'Selesai',
        'cancelled' => 'Dibatalkan',
    ],

    // Aksi
    'actions' => [
        'create' => 'Buat Booking',
        'approve' => 'Setujui',
        'reject' => 'Tolak',
        'complete' => 'Tandai Selesai',
        'cancel' => 'Batalkan',
        'view' => 'Lihat Detail',
        'edit' => 'Edit',
        'delete' => 'Hapus',
        'print_letter' => 'Cetak Surat',
    ],

    // Pesan
    'messages' => [
        'created' => 'Booking berhasil dibuat',
        'updated' => 'Booking berhasil diperbarui',
        'deleted' => 'Booking berhasil dihapus',
        'approved' => 'Booking berhasil disetujui',
        'rejected' => 'Booking berhasil ditolak',
        'completed' => 'Booking ditandai sebagai selesai',
        'cancelled' => 'Booking berhasil dibatalkan',
        'not_found' => 'Booking tidak ditemukan',
        'time_conflict' => 'Waktu sudah dibooking',
    ],
];
