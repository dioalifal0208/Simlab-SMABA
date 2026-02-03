<?php

namespace App\Imports;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;

class ItemImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use SkipsFailures;

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
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
            return $item;
        }

        // Saat membuat baru, kita gabungkan 'nama_alat' dengan data lainnya.
        return new Item(array_merge(['nama_alat' => $row['nama_alat']], $data));
    }

    /**
     * Aturan validasi untuk setiap baris.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'nama_alat' => 'required|string|max:255',
            'tipe' => 'required|in:Alat,Bahan Habis Pakai', // PERBAIKAN: Validasi lebih ketat
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'nullable|string|max:50',
            'kondisi' => 'required|in:Baik,Kurang Baik,Rusak',
            'lokasi_penyimpanan' => 'nullable|string|max:255',
            'laboratorium' => 'nullable|in:Biologi,Fisika,Bahasa,Komputer,Kimia,Multimedia', // Validasi lab
            'stok_minimum' => 'nullable|integer|min:0',
        ];
    }
}