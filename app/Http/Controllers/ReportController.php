<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Exports\BookingsExport;
use App\Exports\LoansExport;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // --- 1. SUMMARY CARDS (Month over Month) ---
        $currentBorrowings = Loan::whereBetween('created_at', [$startOfMonth, $now])->count();
        $lastBorrowings = Loan::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $borrowingTrend = $lastBorrowings > 0 ? round((($currentBorrowings - $lastBorrowings) / $lastBorrowings) * 100) : 100;

        $currentBookings = Booking::whereBetween('created_at', [$startOfMonth, $now])->count();
        $lastBookings = Booking::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        $bookingTrend = $lastBookings > 0 ? round((($currentBookings - $lastBookings) / $lastBookings) * 100) : 100;

        $mostUsedLabData = Booking::select('laboratorium', DB::raw('count(*) as total'))
            ->where('status', 'approved')
            ->whereBetween('waktu_mulai', [$startOfMonth, $now])
            ->groupBy('laboratorium')
            ->orderByDesc('total')
            ->first();
        $mostUsedLab = $mostUsedLabData ? $mostUsedLabData->laboratorium : '-';
        $mostUsedLabCount = $mostUsedLabData ? $mostUsedLabData->total : 0;

        $mostBorrowedItemData = DB::table('loan_item')
            ->join('loans', 'loan_item.loan_id', '=', 'loans.id')
            ->join('items', 'loan_item.item_id', '=', 'items.id')
            ->select('items.nama_alat', DB::raw('SUM(loan_item.jumlah) as total'))
            ->whereBetween('loans.created_at', [$startOfMonth, $now])
            ->groupBy('items.nama_alat')
            ->orderByDesc('total')
            ->first();
        $mostBorrowedItem = $mostBorrowedItemData ? $mostBorrowedItemData->nama_alat : '-';
        $mostBorrowedItemCount = $mostBorrowedItemData ? $mostBorrowedItemData->total : 0;


        // --- 2. BAR CHART (Borrowing per month this year) ---
        $loanTrends = Loan::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyLabels = [];
        $monthlyData = array_fill(0, 12, 0);

        for ($i = 1; $i <= 12; $i++) {
            $monthlyLabels[] = Carbon::create(null, $i, 1)->format('M');
        }

        foreach ($loanTrends as $trend) {
            $monthIndex = (int) substr($trend->month, 5, 2) - 1;
            $monthlyData[$monthIndex] = $trend->count;
        }


        // --- 3. LINE CHART (Booking per month this year) ---
        $bookingTrends = Booking::selectRaw("DATE_FORMAT(waktu_mulai, '%Y-%m') as month, COUNT(*) as count")
            ->whereYear('waktu_mulai', date('Y'))
            ->where('status', 'approved')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
            
        $monthlyBookingData = array_fill(0, 12, 0);
        foreach ($bookingTrends as $trend) {
            $monthIndex = (int) substr($trend->month, 5, 2) - 1;
            $monthlyBookingData[$monthIndex] = $trend->count;
        }

        // --- 4. DONUT CHART (Lab Distribution) ---
        $labDistribution = Booking::select('laboratorium', DB::raw('count(*) as total'))
            ->whereYear('waktu_mulai', date('Y'))
            ->where('status', 'approved')
            ->groupBy('laboratorium')
            ->orderByDesc('total')
            ->get();
            
        $labDistributionLabels = $labDistribution->pluck('laboratorium');
        $labDistributionData = $labDistribution->pluck('total');

        // === 5. ITEM TERPOPULER STATS ===
        $topItems = DB::table('loan_item')
            ->join('items', 'loan_item.item_id', '=', 'items.id')
            ->select('items.nama_alat', DB::raw('SUM(loan_item.jumlah) as total_borrowed'))
            ->groupBy('items.nama_alat')
            ->orderByDesc('total_borrowed')
            ->limit(5)
            ->get();
        $topItemsLabels = $topItems->pluck('nama_alat');
        $topItemsData = $topItems->pluck('total_borrowed');

        // --- 6. TRANSACTIONS TABLE ---
        $recentTransactions = Loan::with('user')->latest()->paginate(10);

        return view('reports.index', compact(
            'currentBorrowings', 'borrowingTrend',
            'currentBookings', 'bookingTrend',
            'mostUsedLab', 'mostUsedLabCount',
            'mostBorrowedItem', 'mostBorrowedItemCount',
            'monthlyLabels', 'monthlyData',
            'monthlyBookingData',
            'labDistributionLabels', 'labDistributionData',
            'topItemsLabels', 'topItemsData',
            'recentTransactions'
        ));
    }

    public function export(Request $request)
    {
        $request->validate([
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:2020|max:' . (date('Y') + 1),
        ]);

        $month = $request->month;
        $year = $request->year;

        return Excel::download(new BookingsExport($month, $year), 'Laporan-Booking-Lab-' . $month . '-' . $year . '.xlsx');
    }

    public function exportLoans(Request $request)
    {
        $request->validate([
            'month' => 'required|numeric|min:1|max:12',
            'year' => 'required|numeric|min:2020|max:' . (date('Y') + 1),
        ]);

        $month = $request->month;
        $year = $request->year;

        return Excel::download(new LoansExport($month, $year), 'Laporan-Peminjaman-Alat-' . $month . '-' . $year . '.xlsx');
    }
}