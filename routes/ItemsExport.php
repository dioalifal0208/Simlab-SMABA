<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ItemsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Memilih kolom yang relevan untuk diekspor, sesuai dengan template impor
        return Item::select(
            'nama_alat', 
            'tipe', 
            'jumlah', 
            'satuan', 
            'kondisi', 
            'lokasi_penyimpanan', 
            'deskripsi', 
            'stok_minimum'
        )->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Header ini harus sama persis dengan yang ada di instruksi modal impor
        return [
            'nama_alat', 'tipe', 'jumlah', 'satuan', 'kondisi', 
            'lokasi_penyimpanan', 'deskripsi', 'stok_minimum'
        ];
    }
}