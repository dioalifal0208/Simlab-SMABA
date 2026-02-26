<?php

return [
    // Judul Halaman
    'title' => 'Booking Lab',
    'title_admin' => 'Kelola Booking Lab',
    'title_user' => 'Riwayat Booking Lab Saya',
    'subtitle_admin' => 'Lihat dan proses semua pengajuan jadwal penggunaan lab.',
    'subtitle_user' => 'Ajukan jadwal dan lacak status booking lab Anda.',
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

    // Filter
    'filters' => [
        'all' => 'Semua',
        'all_labs' => 'Semua Lab',
    ],

    // Label
    'labels' => [
        'diajukan_oleh' => 'Diajukan oleh',
    ],

    // Aksi
    'actions' => [
        'create' => 'Buat Booking',
        'create_new' => 'Ajukan Booking Baru',
        'approve' => 'Setujui',
        'reject' => 'Tolak',
        'complete' => 'Tandai Selesai',
        'cancel' => 'Batalkan',
        'view' => 'Lihat Detail',
        'edit' => 'Edit',
        'delete' => 'Hapus',
        'print_letter' => 'Cetak Surat',
    ],

    // Kosong
    'empty' => [
        'title' => 'Tidak Ada Data Booking',
        'description' => 'Belum ada data booking yang cocok dengan filter Anda.',
        'action' => 'Ajukan Booking Baru',
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
        'waiting_approval' => 'Menunggu persetujuan Admin',
        'print_permit' => 'Silakan cetak surat izin',
        'event_ended' => 'Kegiatan telah berakhir',
        'click_to_complete' => 'Klik jika kegiatan & pengembalian sudah beres.',
        'delete_confirm' => 'Yakin ingin menghapus permanen?',
        'delete_action' => 'Hapus Data Booking',
    ],

    // Detail
    'details' => [
        'title' => 'Detail Booking',
        'info' => 'Informasi Booking',
        'applicant' => 'Pemohon',
        'submission_date' => 'Tanggal Pengajuan',
        'subject' => 'Mata Pelajaran',
        'execution_time' => 'Waktu Pelaksanaan',
        'purpose' => 'Tujuan Kegiatan',
        'admin_notes' => 'Catatan Admin',
        'current_status' => 'Status Saat Ini',
        'admin_control' => 'Admin Control',
        'review_notes' => 'Catatan (Review)',
        'reject_placeholder' => 'Berikan alasan jika menolak...',
    ],

    // Surat
    'letter' => [
        'view' => 'Lihat Surat',
        'preview_title' => 'Pratinjau Surat',
        'preview_subtitle' => 'Pastikan margin dan layout sesuai sebelum mencetak.',
        'download_print' => 'Download PDF / Cetak',
    ],

    // Return Form
    'report' => [
        'title' => 'Laporan Pengembalian',
        'saved_title' => 'Laporan Telah Disimpan',
        'returned_at' => 'Dikembalikan pada',
        'attention_title' => 'Perhatian',
        'attention_text' => 'Mohon isi checklist kondisi ruangan di bawah ini setelah kegiatan selesai. Data ini akan otomatis masuk ke Surat Peminjaman.',
        'save' => 'Simpan Laporan',
        'conditions' => [
            'clean' => 'Bersih dan Rapi',
            'trash' => 'Ada Sampah',
            'messy' => 'Alat Berantakan',
            'damaged' => 'Ada Kerusakan',
        ],
    ],
];
