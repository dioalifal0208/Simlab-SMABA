<?php

return [
    // Judul Halaman
    'title' => 'Peminjaman',
    'title_admin' => 'Manajemen Peminjaman',
    'title_user' => 'Peminjaman Saya',
    'subtitle_admin' => 'Pantau dan kelola semua permintaan peminjaman alat laboratorium.',
    'subtitle_user' => 'Lihat riwayat dan status peminjaman alat laboratorium Anda.',
    'create_loan' => 'Buat Peminjaman',
    'edit_loan' => 'Edit Peminjaman',
    'loan_details' => 'Detail Peminjaman',

    // Header Tabel
    'table' => [
        'id' => 'ID',
        'borrower' => 'Peminjam',
        'item' => 'Item',
        'item_count' => 'Jumlah Item',
        'quantity' => 'Jumlah',
        'purpose' => 'Tujuan',
        'borrow_date' => 'Tanggal Pinjam',
        'return_date' => 'Tanggal Kembali',
        'lab' => 'Laboratorium',
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
        'select_item' => 'Pilih Item',
        'add_item' => 'Tambah Item',
    ],

    // Status
    'status' => [
        'pending' => 'Menunggu',
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        'returned' => 'Dikembalikan',
        'overdue' => 'Terlambat',
        'completed' => 'Selesai',
    ],

    // Filter
    'filters' => [
        'all_status' => 'Semua Status',
        'all_labs' => 'Semua Laboratorium',
    ],

    // Satuan
    'units' => [
        'item' => 'item',
    ],

    // Kosong
    'empty' => [
        'title' => 'Belum Ada Peminjaman',
        'description' => 'Anda belum memiliki riwayat peminjaman alat.',
        'action' => 'Ajukan Peminjaman Baru',
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
        'waiting_approval' => 'Menunggu Persetujuan',
        'no_notes' => 'Tidak ada catatan.',
        'no_other_actions' => 'Tidak ada aksi lainnya untuk status ini.',
        'delete_confirm' => 'Yakin ingin menghapus permanen?',
    ],

    // Detail
    'details' => [
        'title' => 'Detail Peminjaman',
        'back_to_list' => 'Kembali ke Daftar Peminjaman',
        'info' => 'Informasi Pengajuan',
        'applicant' => 'Peminjam',
        'submission_date' => 'Tanggal Pengajuan',
        'lab' => 'Laboratorium',
        'plan_borrow_date' => 'Rencana Tanggal Pinjam',
        'return_date' => 'Tanggal Kembali',
        'borrower_notes' => 'Catatan dari Peminjam',
        'admin_notes' => 'Catatan dari Admin',
        'requested_items' => 'Item yang Diajukan',
        'item_name' => 'Nama Item',
        'quantity' => 'Jumlah',
        'submission_status' => 'Status Pengajuan',
        'follow_up' => 'Tindak Lanjut Pengajuan',
        'optional_notes' => 'Catatan (Opsional)',
        'notes_placeholder' => 'Contoh: Salah satu item tidak tersedia.',
        'return_actions' => 'Aksi Pengembalian',
        'mark_as_returned' => 'Tandai Sudah Dikembalikan',
        'other_actions' => 'Aksi Lainnya',
    ],
];
