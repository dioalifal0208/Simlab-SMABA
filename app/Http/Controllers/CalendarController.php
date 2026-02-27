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
        $start = request('start');
        $end = request('end');
        $type = request('type'); // type = 'lab' (bookings/loans) or 'holiday'

        // 1. Definisikan Warna Laboratorium
        $labColors = [
            'Biologi'    => '#2563eb', // blue
            'Fisika'     => '#16a34a', // green
            'Bahasa'     => '#f59e0b', // amber
            'Komputer 1' => '#7c3aed', // violet
            'Komputer 2' => '#db2777', // pink
            'Komputer 3' => '#0d9488', // teal
            'Komputer 4' => '#ea580c', // orange
            'Komputer'   => '#7c3aed',
            'Lab Komputer' => '#7c3aed',
        ];

        // 2. AMBIL JADWAL LAB & LOANS (Jika type bukan 'holiday')
        if ($type !== 'holiday') {
            // Bookings
            $bookingsQuery = Booking::with('user')->where('status', 'approved');
            if (request()->filled('laboratorium')) {
                $bookingsQuery->where('laboratorium', request('laboratorium'));
            }
            $bookings = $bookingsQuery->get();

            foreach ($bookings as $booking) {
                $color = $labColors[$booking->laboratorium] ?? '#2563eb';
                if (!isset($labColors[$booking->laboratorium]) && str_contains(strtolower($booking->laboratorium), 'komputer')) {
                    $color = '#7c3aed';
                }

                $events[] = [
                    'id' => 'booking-' . $booking->id,
                    'title' => $booking->laboratorium . ': ' . $booking->tujuan_kegiatan,
                    'start' => $booking->waktu_mulai->toIso8601String(),
                    'end' => $booking->waktu_selesai->toIso8601String(),
                    'url'   => route('bookings.show', $booking->id),
                    'color' => $color,
                    'extendedProps' => ['type' => 'lab']
                ];
            }

            // Loans
            $loans = Loan::with('user')->where('status', 'approved')->get();
            foreach ($loans as $loan) {
                $loanEndDate = \Carbon\Carbon::parse($loan->tanggal_estimasi_kembali)->addDay()->toDateString();
                $events[] = [
                    'id' => 'loan-' . $loan->id,
                    'title' => 'Peminjaman Alat oleh ' . $loan->user->name,
                    'start' => $loan->tanggal_pinjam,
                    'end'   => $loanEndDate,
                    'url'   => route('loans.show', $loan->id),
                    'color' => '#10B981',
                    'extendedProps' => ['type' => 'loan']
                ];
            }
        }

        // 3. AMBIL HARI LIBUR NASIONAL (Jika type bukan 'lab')
        if ($type !== 'lab') {
            $year = $start ? \Carbon\Carbon::parse($start)->year : date('Y');
            $holidays = \Illuminate\Support\Facades\Cache::remember("national_holidays_{$year}", 86400, function() use ($year) {
                try {
                    $response = \Illuminate\Support\Facades\Http::get("https://day-off-api.vercel.app/api?year={$year}");
                    if ($response->successful()) {
                        return $response->json();
                    }
                } catch (\Exception $e) {
                    return [];
                }
                return [];
            });

            if (!empty($holidays)) {
                foreach ($holidays as $holiday) {
                    if (isset($holiday['is_holiday']) && $holiday['is_holiday']) {
                        $events[] = [
                            'id' => 'holiday-' . \Illuminate\Support\Str::slug($holiday['holiday_name'] . $holiday['holiday_date']),
                            'title' => 'Hari Libur: ' . $holiday['holiday_name'],
                            'start' => $holiday['holiday_date'],
                            'allDay' => true,
                            'color' => '#D32F2F',
                            'display' => 'block',
                            'extendedProps' => ['type' => 'holiday']
                        ];
                    }
                }
            }
        }

        return response()->json($events);
    }
}
