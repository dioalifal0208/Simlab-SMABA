<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Loan;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    /**
     * Menampilkan halaman utama kalender.
     */
    public function index()
    {
        return view('calendar.index');
    }

    /**
     * Menyediakan data event dalam format JSON untuk FullCalendar.
     */
    public function events()
    {
        $events = [];

        // 1. Ambil semua data booking yang sudah disetujui (approved)
        $bookings = Booking::with('user')->where('status', 'approved')->get();
        foreach ($bookings as $booking) {
            $events[] = [
                'title' => 'Lab Dibooking: ' . $booking->tujuan_kegiatan,
                'start' => $booking->waktu_mulai->toIso8601String(), // Format standar
                'end' => $booking->waktu_selesai->toIso8601String(), // Format standar
                'url'   => route('bookings.show', $booking->id), // Link saat event di-klik
                'color' => '#3788d8', // Warna biru untuk booking lab
            ];
        }

        // 2. Ambil semua data peminjaman alat yang sudah disetujui (approved)
$loans = Loan::with('user')->where('status', 'approved')->get();
foreach ($loans as $loan) {
    // Penting: FullCalendar menganggap tanggal 'end' bersifat eksklusif.
    // Jadi, kita perlu menambahkan satu hari agar blok kalender mencakup hari terakhir.
    $loanEndDate = \Carbon\Carbon::parse($loan->tanggal_estimasi_kembali)->addDay()->toDateString();

    $events[] = [
        'title' => 'Peminjaman Alat oleh ' . $loan->user->name,
        'start' => $loan->tanggal_pinjam,
        'end'   => $loanEndDate, // <-- PERUBAHAN DI SINI
        'url'   => route('loans.show', $loan->id),
        'color' => '#10B981',
    ];
}

        // 3. Kembalikan semua data sebagai JSON
        return response()->json($events);
    }
}