<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // === 1. DATA UNTUK GRAFIK TREN PEMINJAMAN BULANAN ===
        $loanTrends = Loan::selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as count")
            ->whereYear('created_at', date('Y')) // Hanya data tahun ini
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Siapkan array 12 bulan
        $monthlyLabels = [];
        $monthlyData = array_fill(0, 12, 0); // Array berisi 12 angka nol [0, 0, ..., 0]

        for ($i = 1; $i <= 12; $i++) {
            $monthlyLabels[] = Carbon::create(null, $i, 1)->format('M'); // "Jan", "Feb", ...
        }

        // Isi data dari database ke array bulan yang sesuai
        foreach ($loanTrends as $trend) {
            $monthIndex = (int) substr($trend->month, 5, 2) - 1; // '2025-10' -> 9
            $monthlyData[$monthIndex] = $trend->count;
        }

        // === 2. DATA UNTUK GRAFIK ITEM TERPOPULER ===
        $topItems = DB::table('loan_item')
            ->join('items', 'loan_item.item_id', '=', 'items.id')
            ->select('items.nama_alat', DB::raw('SUM(loan_item.jumlah) as total_borrowed'))
            ->groupBy('items.nama_alat')
            ->orderByDesc('total_borrowed')
            ->limit(10)
            ->get();
        
        $topItemsLabels = $topItems->pluck('nama_alat');
        $topItemsData = $topItems->pluck('total_borrowed');

        // Kirim semua data yang sudah diolah ke view
        return view('reports.index', compact(
            'monthlyLabels',
            'monthlyData',
            'topItemsLabels',
            'topItemsData'
        ));
    }
}