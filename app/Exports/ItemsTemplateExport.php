<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class ItemsTemplateExport implements WithHeadings, WithTitle, ShouldAutoSize, WithStyles, FromCollection
{
    /**
     * @return Collection
     */
    public function collection()
    {
        return collect([
            [
                'Erlenmeyer 250 ml',
                'Alat',
                20,
                'Pcs',
                'baik',
                'Lemari A1',
                'Biologi',
                5,
                'Alat kaca untuk mencampur bahan kimia.',
            ],
            [
                'HCL 0.1M',
                'Bahan Habis Pakai',
                1000,
                'ml',
                'baik',
                'Gudang Bahan B',
                'Kimia', // This will be mapped to Biologi/Fisika/etc or handled by default
                100,
                'Larutan asam klorida.',
            ],
        ]);
    }

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
