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
        // Mencocokkan header CSV (kiri) dengan kolom database (kanan)
        return new Item([
            'nama_alat'          => $row['nama_alat'],
            'tipe'               => $row['tipe'],
            'jumlah'             => $row['jumlah'],
            'satuan'             => $row['satuan'],
            'kondisi'            => $row['kondisi'],
            'lokasi_penyimpanan' => $row['lokasi_penyimpanan'],
            'stok_minimum'       => $row['stok_minimum'] ?? null,
            'deskripsi'          => $row['deskripsi'] ?? null,
            'user_id'            => Auth::id(), // Otomatis isi dengan ID admin yang mengimpor
        ]);
    }

    /**
     * Tentukan aturan validasi untuk setiap baris.
     */
    public function rules(): array
    {
        return [
            'nama_alat' => 'required|string|max:255',
            'tipe' => 'required|in:Alat,Bahan Habis Pakai',
            'jumlah' => 'required|integer|min:0',
            'satuan' => 'required|string|max:50',
            'kondisi' => 'required|in:Baik,Kurang Baik,Rusak',
            'lokasi_penyimpanan' => 'required|string|max:255',
            'stok_minimum' => 'nullable|integer|min:0',
        ];
    }
}