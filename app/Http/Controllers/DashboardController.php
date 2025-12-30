<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Booking;
use App\Models\Item;
use App\Models\Document;
use App\Models\DamageReport; // <-- Import DamageReport
use Illuminate\Http\Request; // <-- Import Request
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // ==============================
        // ==============================
        //       DASHBOARD UNTUK ADMIN
        // ==============================
        if ($user->role === 'admin') {
            
            $lowStockItems = Item::where('tipe', 'Bahan Habis Pakai')
                                 ->whereNotNull('stok_minimum')
                                 ->where('stok_minimum', '>', 0)
                                 ->whereColumn('jumlah', '<=', 'stok_minimum')
                                 ->get();
            
            $newDamageReportsCount = DamageReport::where('status', 'Dilaporkan')->count();
            $pendingLoansCount    = Loan::where('status', 'pending')->count();
            $pendingBookingsCount = Booking::where('status', 'pending')->count();
            $brokenItemsCount     = Item::where('kondisi', 'rusak')->count();
            $upcomingBookingsCount = Booking::whereIn('status', ['pending', 'approved'])
                                         ->where('waktu_mulai', '>=', now())
                                         ->count();

            // --- PERBAIKAN: Memindahkan query ini ke atas ---
            $overdueLoansCount = Loan::where('status', 'Terlambat')->count();
            // -----------------------------------------------

            // Ambil lebih banyak lalu gabungkan, baru batasi 5 terakhir
            $loanActivities = Loan::with('user:id,name')
                ->select('id', 'user_id', 'created_at')
                ->latest()
                ->take(10)
                ->get()
                ->map(fn($x) => tap($x, fn($i) => $i->type = 'loan'));

            $bookingActivities = Booking::with('user:id,name')
                ->select('id', 'user_id', 'tujuan_kegiatan', 'created_at')
                ->latest()
                ->take(10)
                ->get()
                ->map(fn($x) => tap($x, fn($i) => $i->type = 'booking'));

            $recentActivities = $loanActivities
                ->merge($bookingActivities)
                ->sortByDesc('created_at')
                ->take(5) // <-- Batasi hanya 5 item terbaru
                ->values();

            $agg = Item::selectRaw('kondisi, COUNT(*) AS total')
                ->groupBy('kondisi')
                ->get();

            $chartLabels = $agg->pluck('kondisi')->values();
            $chartData   = $agg->pluck('total')->values();

            $palette = [
                'rgb(75, 192, 192)',
                'rgb(255, 205, 86)',
                'rgb(255, 99, 132)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
                'rgb(201, 203, 207)'
            ];
            $chartColors = $chartLabels->map(fn($_, $i) => $palette[$i % count($palette)])->values();

            $recentDocuments = Document::with('user:id,name')
                ->visibleTo($user)
                ->latest()
                ->take(5)
                ->get();

            // --- PERBAIKAN: Menambahkan $overdueLoansCount ke return view ---
            return view('dashboard', [
                'lowStockItems'         => $lowStockItems,
                'newDamageReportsCount' => $newDamageReportsCount,
                'pendingLoansCount'     => $pendingLoansCount,
                'pendingBookingsCount'  => $pendingBookingsCount,
                'brokenItemsCount'      => $brokenItemsCount,
                'upcomingBookingsCount' => $upcomingBookingsCount,
                'overdueLoansCount'     => $overdueLoansCount, // <-- Variabel baru ditambahkan di sini
                'recentActivities'      => $recentActivities,
                'chartLabels'           => $chartLabels,
                'chartData'             => $chartData,
                'chartColors'           => $chartColors,
                'recentDocuments'       => $recentDocuments,
            ]);
            // -----------------------------------------------------------
        }

        // ==============================
        //   DASHBOARD UNTUK SISWA / GURU
        // ==============================

        // Riwayat peminjaman terakhir
        $recentUserLoans = Loan::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Booking lab terdekat
        $nextBooking = Booking::where('user_id', $user->id)
            ->where('waktu_mulai', '>=', now())
            ->orderBy('waktu_mulai')
            ->first();

        // Ambil data peminjaman yang sedang aktif (hanya approved)
        $activeLoans = Loan::where('user_id', $user->id)
                           ->where('status', 'approved') // Hanya yang disetujui
                           ->with('items')
                           ->latest()
                           ->get();

        // BARU: Ambil data peminjaman yang terlambat
        $overdueLoans = Loan::where('user_id', $user->id)
                           ->where('status', 'Terlambat')
                           ->with('items')
                           ->latest()
                           ->get();

        // Dokumen terbaru
        $recentDocuments = Document::with('user:id,name')
            ->visibleTo($user)
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', [
            'recentUserLoans' => $recentUserLoans,
            'nextBooking'     => $nextBooking,
            'activeLoans'     => $activeLoans,
            'recentDocuments' => $recentDocuments,
            'overdueLoans'    => $overdueLoans,
        ]);
    }
}
