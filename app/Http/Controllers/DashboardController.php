<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Booking;
use App\Models\Item;
use App\Models\Document;
use App\Models\DamageReport; // <-- Import DamageReport
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request; // <-- Import Request
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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
            
            // Quick Stats Summary Bar Data
            $totalItemsCount = Item::count();
            $totalUsersCount = User::count();
            $monthlyLoansCount = Loan::whereMonth('created_at', Carbon::now()->month)
                                     ->whereYear('created_at', Carbon::now()->year)
                                     ->count();
            $monthlyBookingsCount = Booking::whereMonth('created_at', Carbon::now()->month)
                                           ->whereYear('created_at', Carbon::now()->year)
                                           ->count();
            $monthlyTransactionsCount = $monthlyLoansCount + $monthlyBookingsCount;
            
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

            // Ambil aktivitas dari berbagai sumber
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

            // Tambahkan audit logs
            $auditActivities = AuditLog::with('user:id,name')
                ->whereNotNull('user_id')
                ->whereIn('action', ['created', 'updated', 'deleted', 'login'])
                ->latest('created_at')
                ->take(15)
                ->get()
                ->map(fn($x) => tap($x, fn($i) => $i->type = 'audit'));

            $recentActivities = $loanActivities
                ->merge($bookingActivities)
                ->merge($auditActivities)
                ->sortByDesc('created_at')
                ->take(10) // <-- Tingkatkan dari 5 ke 10
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
                // Quick Stats Summary
                'totalItemsCount'         => $totalItemsCount,
                'totalUsersCount'         => $totalUsersCount,
                'monthlyTransactionsCount' => $monthlyTransactionsCount,
                // Existing stats
                'lowStockItems'         => $lowStockItems,
                'newDamageReportsCount' => $newDamageReportsCount,
                'pendingLoansCount'     => $pendingLoansCount,
                'pendingBookingsCount'  => $pendingBookingsCount,
                'brokenItemsCount'      => $brokenItemsCount,
                'upcomingBookingsCount' => $upcomingBookingsCount,
                'overdueLoansCount'     => $overdueLoansCount,
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
            ->with('items') // Eager load items untuk mencegah N+1 query
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

        // ==============================
        // STATUS SUMMARY UNTUK GURU
        // ==============================
        
        // Loan status counts
        $userLoanStats = [
            'pending' => Loan::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => Loan::where('user_id', $user->id)->where('status', 'approved')->count(),
            'completed' => Loan::where('user_id', $user->id)->where('status', 'completed')->count(),
            'rejected' => Loan::where('user_id', $user->id)->where('status', 'rejected')->count(),
            'overdue' => Loan::where('user_id', $user->id)->where('status', 'Terlambat')->count(),
            'total' => Loan::where('user_id', $user->id)->count(),
        ];
        
        // Booking status counts
        $userBookingStats = [
            'pending' => Booking::where('user_id', $user->id)->where('status', 'pending')->count(),
            'approved' => Booking::where('user_id', $user->id)->where('status', 'approved')->count(),
            'completed' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'rejected' => Booking::where('user_id', $user->id)->where('status', 'rejected')->count(),
            'total' => Booking::where('user_id', $user->id)->count(),
        ];
        
        // Upcoming bookings count (in next 7 days)
        $upcomingUserBookingsCount = Booking::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereBetween('waktu_mulai', [now(), now()->addDays(7)])
            ->count();

        return view('dashboard', [
            'recentUserLoans'   => $recentUserLoans,
            'nextBooking'       => $nextBooking,
            'activeLoans'       => $activeLoans,
            'recentDocuments'   => $recentDocuments,
            'overdueLoans'      => $overdueLoans,
            'userLoanStats'     => $userLoanStats,
            'userBookingStats'  => $userBookingStats,
            'upcomingUserBookingsCount' => $upcomingUserBookingsCount,
        ]);
    }
}
