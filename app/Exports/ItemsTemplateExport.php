<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ItemsTemplateExport implements WithHeadings, WithTitle, ShouldAutoSize, WithStyles
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'nama_alat',
            'tipe',
            'jumlah',
            'satuan',
            'kondisi',
            'lokasi_penyimpanan',
            'laboratorium',
            'stok_minimum',
            'deskripsi',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Template Import Item';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
