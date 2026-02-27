<?php

namespace App\Imports;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

use Maatwebsite\Excel\Concerns\WithMapping;

class ItemImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure, WithMapping
{
    use SkipsFailures;

    /** Counter untuk baris yang berhasil diimpor */
    private int $importedCount = 0;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    /**
     * Map data before validation and model creation.
     */
    public function map($row): array
    {
        // 1. Normalisasi Tipe
        $tipe = $row['tipe'] ?? '';
        if (stripos($tipe, 'Bahan') !== false) {
            $row['tipe'] = 'Bahan Habis Pakai';
        } else {
            // Default ke 'Alat' jika mengandung kata 'Alat' atau lainnya
            $row['tipe'] = 'Alat';
        }

        // 2. Normalisasi Laboratorium
        $lab = $row['laboratorium'] ?? '';
        if (stripos($lab, 'Biologi') !== false) {
            $row['laboratorium'] = 'Biologi';
        } elseif (stripos($lab, 'Fisika') !== false) {
            $row['laboratorium'] = 'Fisika';
        } elseif (stripos($lab, 'Bahasa') !== false) {
            $row['laboratorium'] = 'Bahasa';
        } elseif (preg_match('/komputer\s*4/i', $lab)) {
            $row['laboratorium'] = 'Komputer 4';
        } elseif (preg_match('/komputer\s*3/i', $lab)) {
            $row['laboratorium'] = 'Komputer 3';
        } elseif (preg_match('/komputer\s*2/i', $lab)) {
            $row['laboratorium'] = 'Komputer 2';
        } elseif (preg_match('/komputer/i', $lab)) {
            // Default "Komputer" tanpa nomor → Komputer 1
            $row['laboratorium'] = 'Komputer 1';
        } else {
            // Default sesuai user atau fallback ke Biologi
            $row['laboratorium'] = Auth::user()->laboratorium ?? 'Biologi';
        }

        // 3. Normalisasi Kondisi
        $kondisi = (string) ($row['kondisi'] ?? '');
        if ($kondisi === '1' || stripos($kondisi, 'Baik') !== false || stripos($kondisi, 'Good') !== false) {
            $row['kondisi'] = 'baik';
        } elseif ($kondisi === '2' || stripos($kondisi, 'Kurang') !== false || stripos($kondisi, 'Fair') !== false) {
            $row['kondisi'] = 'kurang baik';
        } elseif ($kondisi === '3' || stripos($kondisi, 'Rusak') !== false || stripos($kondisi, 'Broken') !== false) {
            $row['kondisi'] = 'Rusak';
        } else {
            $row['kondisi'] = 'baik'; // Default
        }

        // 4. Bersihkan angka dari spasi atau karakter non-numerik jika ada
        if (isset($row['jumlah'])) {
            $row['jumlah'] = (int) preg_replace('/[^0-9]/', '', $row['jumlah']);
        }
        if (isset($row['stok_minimum'])) {
            $row['stok_minimum'] = (int) preg_replace('/[^0-9]/', '', $row['stok_minimum']);
        }

        return $row;
    }

    public function model(array $row)
    {
        // PERBAIKAN: Logika yang lebih aman untuk membuat atau memperbarui.
        // 1. Cari item berdasarkan nama_alat.
        $item = Item::where('nama_alat', $row['nama_alat'])->first();

        // 2. Siapkan data dari file Excel.
        $data = [
            'tipe' => $row['tipe'] ?? 'Alat', // Default ke 'Alat' jika kosong
            'jumlah' => $row['jumlah'] ?? 0,
            'satuan' => $row['satuan'] ?? 'unit',
            'kondisi' => $row['kondisi'] ?? 'Baik',
            'lokasi_penyimpanan' => $row['lokasi_penyimpanan'] ?? 'Gudang',
            'laboratorium' => $row['laboratorium'] ?? 'Biologi', // Default 'Biologi' sesuai DB
            'stok_minimum' => $row['stok_minimum'] ?? 0,
            'deskripsi' => $row['deskripsi'] ?? null,
            'user_id' => Auth::id(),
        ];

        // 3. Jika item sudah ada, update. Jika tidak, buat baru.
        if ($item) {
            $item->update($data);
            $this->importedCount++;
            return $item;
        }

        // Saat membuat baru, kita gabungkan 'nama_alat' dengan data lainnya.
        $this->importedCount++;
        return new Item(array_merge(['nama_alat' => $row['nama_alat']], $data));
    }

    /** Kembalikan jumlah baris yang berhasil diimpor */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    /**
     * Aturan validasi untuk setiap baris.
     * Harus sinkron dengan StoreItemRequest.php
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nama_alat' => 'required|string|max:255',
            'tipe' => 'required|in:Alat,Bahan Habis Pakai',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50', // Sinkron dengan manual
            'kondisi' => 'required|in:baik,kurang baik,Rusak',
            'lokasi_penyimpanan' => 'required|string|max:255', // Sinkron dengan manual
            'laboratorium' => 'required|in:Biologi,Fisika,Bahasa,Komputer 1,Komputer 2,Komputer 3,Komputer 4', // Sinkron dengan manual
            'stok_minimum' => 'nullable|integer|min:0',
            'deskripsi' => 'nullable|string',
        ];
    }
}