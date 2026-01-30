<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BookingsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $month;
    protected $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Booking::with('user')
            ->whereYear('waktu_mulai', $this->year)
            ->whereMonth('waktu_mulai', $this->month)
            ->orderBy('waktu_mulai')
            ->get();
    }

    public function map($booking): array
    {
        return [
            $booking->waktu_mulai->format('d-m-Y'),
            $booking->waktu_mulai->format('H:i') . ' - ' . $booking->waktu_selesai->format('H:i'),
            $booking->user->name ?? 'User Terhapus',
            $booking->laboratorium,
            $booking->tujuan_kegiatan,
            ucfirst($booking->status),
            $booking->waktu_pengembalian ? $booking->waktu_pengembalian->format('d-m-Y H:i') : '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Tanggal Pelaksanaan',
            'Waktu',
            'Nama Peminjam',
            'Laboratorium',
            'Tujuan Kegiatan',
            'Status',
            'Waktu Pengembalian',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
        ];
    }
}
