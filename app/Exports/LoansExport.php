<?php

namespace App\Exports;

use App\Models\Loan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LoansExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    /**
     * Ambil data peminjaman alat (loans) berdasarkan bulan & tahun.
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Loan::with(['user', 'items'])
            ->whereYear('created_at', $this->year)
            ->whereMonth('created_at', $this->month)
            ->orderBy('created_at')
            ->get();
    }

    public function map($loan): array
    {
        // Gabungkan semua item yang dipinjam menjadi satu string
        $itemNames = $loan->items->map(function ($item) {
            return $item->nama_alat . ' (' . ($item->pivot->jumlah ?? 1) . ' ' . ($item->satuan ?? 'unit') . ')';
        })->implode(', ');

        return [
            $loan->created_at->format('d-m-Y'),
            $loan->user->name ?? 'User Terhapus',
            $loan->laboratorium,
            $itemNames ?: '-',
            $loan->tanggal_pinjam ? $loan->tanggal_pinjam->format('d-m-Y') : '-',
            $loan->tanggal_estimasi_kembali ? $loan->tanggal_estimasi_kembali->format('d-m-Y') : '-',
            $loan->tanggal_kembali ? $loan->tanggal_kembali->format('d-m-Y') : '-',
            ucfirst($loan->status),
            $loan->catatan ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Pengajuan',
            'Nama Peminjam',
            'Laboratorium',
            'Item yang Dipinjam',
            'Rencana Tanggal Pinjam',
            'Estimasi Kembali',
            'Tanggal Kembali Aktual',
            'Status',
            'Catatan',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
