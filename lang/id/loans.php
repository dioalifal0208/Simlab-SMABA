<?php

return [
    // Judul Halaman
    'title' => 'Peminjaman',
    'create_loan' => 'Buat Peminjaman',
    'edit_loan' => 'Edit Peminjaman',
    'loan_details' => 'Detail Peminjaman',

    // Header Tabel
    'table' => [
        'borrower' => 'Peminjam',
        'item' => 'Item',
        'quantity' => 'Jumlah',
        'purpose' => 'Tujuan',
        'borrow_date' => 'Tanggal Pinjam',
        'return_date' => 'Tanggal Kembali',
        'status' => 'Status',
        'actions' => 'Aksi',
    ],

    // Label Form
    'form' => [
        'borrower' => 'Peminjam',
        'item' => 'Item',
        'quantity' => 'Jumlah',
        'purpose' => 'Tujuan',
        'borrow_date' => 'Tanggal Pinjam',
        'return_date' => 'Tanggal Kembali (Perkiraan)',
        'notes' => 'Catatan',
    ],

    // Status
    'status' => [
        'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'returned' => 'Dikembalikan',
        'overdue' => 'Terlambat',
    ],

    // Aksi
    'actions' => [
        'create' => 'Buat Peminjaman',
        'approve' => 'Setujui',
        'reject' => 'Tolak',
        'return' => 'Tandai Dikembalikan',
        'view' => 'Lihat Detail',
        'edit' => 'Edit',
        'delete' => 'Hapus',
    ],

    // Pesan
    'messages' => [
        'created' => 'Peminjaman berhasil dibuat',
        'updated' => 'Peminjaman berhasil diperbarui',
        'deleted' => 'Peminjaman berhasil dihapus',
        'approved' => 'Peminjaman berhasil disetujui',
        'rejected' => 'Peminjaman berhasil ditolak',
        'returned' => 'Peminjaman ditandai sebagai dikembalikan',
        'not_found' => 'Peminjaman tidak ditemukan',
        'insufficient_stock' => 'Stok tidak mencukupi',
    ],
];
