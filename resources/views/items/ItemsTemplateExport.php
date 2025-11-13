<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsTemplateExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Kita hanya butuh header, jadi kita kembalikan collection kosong.
        return collect([]);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Ini adalah header yang akan muncul di file Excel template.
        return [
            'nama_alat', 'tipe', 'jumlah', 'satuan', 'kondisi', 'lokasi_penyimpanan', 'deskripsi', 'stok_minimum'
        ];
    }
}