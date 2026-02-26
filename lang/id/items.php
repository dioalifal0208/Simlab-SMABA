<?php

return [
    // Judul Halaman
    'title' => 'Inventaris',
    'add_item' => 'Tambah Item Inventaris Baru',
    'edit_item' => 'Edit Item Inventaris',
    'item_details' => 'Detail Item',
    'subtitle' => 'Kelola semua alat dan bahan laboratorium dalam satu sistem inventaris terpusat.',
    'create_subtitle' => 'Isi detail untuk menambahkan alat atau bahan baru ke dalam inventaris.',
    'edit_subtitle' => 'Perbarui informasi untuk item inventaris yang sudah ada.',
    'edit_subtitle_param' => 'Perbarui detail untuk alat atau bahan: :name',

    // Header Tabel
    'table' => [
        'name' => 'Nama',
        'category' => 'Kategori',
        'stock' => 'Stok',
        'condition' => 'Kondisi',
        'location' => 'Lokasi',
        'actions' => 'Aksi',
        'code' => 'Kode',
        'type' => 'Tipe',
        'unit' => 'Satuan',
    ],

    // Label Form
    'form' => [
        'name' => 'Nama Item',
        'name_label' => 'Nama Alat / Bahan',
        'category' => 'Kategori',
        'type' => 'Tipe Item',
        'stock' => 'Stok',
        'stock_label' => 'Jumlah/Stok',
        'unit' => 'Satuan',
        'condition' => 'Kondisi',
        'location' => 'Lokasi Penyimpanan',
        'description' => 'Deskripsi / Keterangan',
        'description_optional' => 'Deskripsi / Keterangan (Opsional)',
        'image' => 'Gambar',
        'photos_label' => 'Foto Item (Bisa lebih dari satu)',
        'add_photo_label' => 'Tambah Foto Baru (Opsional)',
        'current_gallery' => 'Galeri Saat Ini:',
        'code' => 'Kode Item',
        'min_stock' => 'Stok Minimum',
        'optional' => '(Opsional)',
        'min_stock_placeholder' => 'Contoh: 10',
        'unit_placeholder' => 'Contoh: Pcs, Gram, Liter',
        'lab' => 'Laboratorium',
        'lab_prefix' => 'Lab',
    ],

    // Kategori
    'categories' => [
        'alat' => 'Alat',
        'bahan' => 'Bahan',
    ],

    // Tipe
    'types' => [
        'habis_pakai' => 'Habis Pakai',
        'tidak_habis_pakai' => 'Tidak Habis Pakai',
        'alat' => 'Alat (Tidak Habis Pakai)',
        'bahan' => 'Bahan Habis Pakai (Consumable)',
    ],

    // Kondisi
    'conditions' => [
        'baik' => 'baik',
        'kurang_baik' => 'kurang baik',
        'rusak' => 'Rusak',
    ],

    // Satuan
    'units' => [
        'pcs' => 'Pcs',
        'set' => 'Set',
        'box' => 'Box',
        'liter' => 'Liter',
        'ml' => 'Mililiter',
        'gram' => 'Gram',
        'kg' => 'Kilogram',
    ],

    // Aksi
    'actions' => [
        'add' => 'Tambah Item',
        'import' => 'Impor Excel/CSV',
        'edit' => 'Edit Item',
        'delete' => 'Hapus Item',
        'save' => 'Simpan',
        'request_add' => 'Ajukan Item Baru',
        'bulk_delete' => 'Hapus Terpilih',
        'reset_filters' => 'Atur Ulang Filter',
        'send_request' => 'Kirim Permintaan',
        'view' => 'Lihat Detail',
        'report_damage' => 'Laporkan Kerusakan',
        'request_stock' => 'Minta Restock',
        'request_loan' => 'Ajukan Peminjaman',
        'maintenance_log' => 'Catat Perawatan',
        'close' => 'Tutup',
    ],

    // Pesan
    'messages' => [
        'created' => 'Item berhasil dibuat',
        'updated' => 'Item berhasil diperbarui',
        'deleted' => 'Item berhasil dihapus',
        'not_found' => 'Item tidak ditemukan',
        'low_stock' => 'Stok menipis',
        'out_of_stock' => 'Stok habis',
        'no_items_selected_title' => 'Tidak Ada Item Terpilih',
        'no_items_selected_text' => 'Silakan pilih setidaknya satu item untuk dihapus.',
        'delete_bulk_title' => 'Hapus :count Item Terpilih?',
        'delete_bulk_text' => 'Tindakan ini tidak dapat dibatalkan. Semua data item yang dipilih akan dihapus permanen.',
        'delete_bulk_confirm' => 'Ya, Hapus Semua',
        'delete_bulk_cancel' => 'Batal',
        'request_new_item' => 'Ajukan Item Baru',
    ],

    'status' => [
        'low_stock' => 'Stok Rendah',
        'selected_count' => ':count item terpilih',
        'normal' => 'Normal',
        'urgent' => 'Mendesak',
        'available' => 'Tersedia',
        'unavailable' => 'Item Tidak Tersedia',
        'good' => 'baik',
        'fair' => 'kurang baik',
        'broken' => 'Rusak',
    ],

    // Baru: Label Tambahan
    'empty' => [
        'title' => 'Belum Ada Item',
        'description' => 'Mulai kelola laboratorium Anda dengan menambahkan item pertama.',
        'action' => 'Tambah Item Baru',
    ],
    'stock_low' => 'Stok Rendah',
    'request_new' => 'Ajukan Item Baru',
    // Detail
    'details' => [
        'title' => 'Detail Item',
        'subtitle' => 'Informasi lengkap, riwayat, dan aksi untuk item inventaris.',
        'back_to_list' => 'Kembali ke Daftar Inventaris',
        'photo_tip' => 'Klik untuk memperbesar gambar',
        'single_photo' => 'Hanya ada satu gambar untuk item ini.',
        'no_photo' => 'Tidak ada foto untuk item ini.',
        'specs_title' => 'Deskripsi & Spesifikasi',
        'no_description' => 'Tidak ada keterangan.',
        'inventory_code' => 'Kode Inventaris',
        'procurement_year' => 'Tahun Pengadaan',
        'min_stock' => 'Stok Minimum',
        'created_at' => 'Dibuat pada',
        'updated_at' => 'Diperbarui pada',
        'loan_history' => 'Riwayat Peminjaman',
        'loan_history_soon' => 'Fitur riwayat peminjaman akan segera tersedia.',
        'maintenance_history' => 'Riwayat Perawatan',
        'no_maintenance' => 'Belum ada riwayat perawatan untuk item ini.',
        'done_by' => 'Dilakukan oleh :name pada :date',
        'usage_modules' => 'Digunakan Dalam Modul',
        'no_modules' => 'Item ini belum ditautkan ke modul praktikum manapun.',
        'created_by' => 'Dibuat oleh',
        'scan_tip' => 'Scan untuk membuka detail item.',
        'user_actions' => 'Aksi Pengguna',
        'admin_actions' => 'Aksi Admin',
    ],

    // Filter
    'filters' => [
        'search' => 'Cari nama alat, kode, atau deskripsi...',
        'type' => 'Semua Tipe',
        'condition' => 'Semua Kondisi',
        'all_labs' => 'Semua Laboratorium',
        'locked_lab' => 'Terkunci di lab Anda',
    ],

    // Impor
    'import' => [
        'title' => 'Impor Data Inventaris',
        'subtitle' => 'Unggah file Excel (.xlsx) atau CSV untuk menambah banyak item sekaligus.',
        'upload_label' => 'Pilih file',
        'drag_drop' => 'atau seret dan lepas di sini',
        'file_types' => 'Excel atau CSV (Maks. 10MB)',
        'remove_file' => 'Hapus file',
        'submit' => 'Mulai Impor Data',
        'download_template' => 'Unduh Template Excel',
        'select_file_first' => 'Silakan pilih file terlebih dahulu.',
        'success_reload' => 'Halaman akan dimuat ulang.',
        'server_error' => 'Terjadi kesalahan pada server. Silakan coba lagi.',
    ],
];
