<?php

return [
    // Judul Halaman
    'title' => 'Inventaris',
    'add_item' => 'Tambah Item',
    'edit_item' => 'Edit Item',
    'item_details' => 'Detail Item',

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
        'category' => 'Kategori',
        'type' => 'Tipe',
        'stock' => 'Stok',
        'unit' => 'Satuan',
        'condition' => 'Kondisi',
        'location' => 'Lokasi',
        'description' => 'Deskripsi',
        'image' => 'Gambar',
        'code' => 'Kode Item',
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
    ],

    // Kondisi
    'conditions' => [
        'baik' => 'Baik',
        'rusak_ringan' => 'Rusak Ringan',
        'rusak_berat' => 'Rusak Berat',
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
        'edit' => 'Edit',
        'delete' => 'Hapus',
        'view' => 'Lihat Detail',
        'report_damage' => 'Laporkan Kerusakan',
        'request_stock' => 'Minta Stok',
    ],

    // Pesan
    'messages' => [
        'created' => 'Item berhasil dibuat',
        'updated' => 'Item berhasil diperbarui',
        'deleted' => 'Item berhasil dihapus',
        'not_found' => 'Item tidak ditemukan',
        'low_stock' => 'Stok menipis',
        'out_of_stock' => 'Stok habis',
    ],
];
