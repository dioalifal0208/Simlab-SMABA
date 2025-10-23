<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use App\Notifications\LoanOverdue;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;

class CheckOverdueLoans extends Command
{
    /**
     * Nama dan tanda tangan dari perintah konsol.
     *
     * @var string
     */
    protected $signature = 'loans:check-overdue'; // Ini adalah nama perintah kita

    /**
     * Deskripsi dari perintah konsol.
     *
     * @var string
     */
    protected $description = 'Periksa peminjaman yang disetujui dan tandai sebagai "Terlambat" jika melewati tanggal estimasi kembali';

    /**
     * Jalankan logika perintah.
     */
    public function handle()
    {
        $this->info('Memeriksa peminjaman yang terlambat...');

        // 1. Cari semua peminjaman yang statusnya 'approved'
        //    DAN tanggal estimasi kembalinya adalah SEBELUM hari ini.
        $overdueLoans = Loan::where('status', 'approved')
                            ->whereDate('tanggal_estimasi_kembali', '<', Carbon::today())
                            ->get();

        if ($overdueLoans->isEmpty()) {
            $this->info('Tidak ada peminjaman yang terlambat ditemukan.');
            return 0;
        }

        // 2. Loop setiap peminjaman yang terlambat
        foreach ($overdueLoans as $loan) {
        $loan->update(['status' => 'Terlambat']);

        // --- TAMBAHKAN DUA BARIS INI ---
        // Pastikan relasi 'user' sudah dimuat jika belum
        $loan->load('user'); 
        Notification::send($loan->user, new LoanOverdue($loan));
        // -------------------------------
    }

        $this->info('Selesai! ' . $overdueLoans->count() . ' peminjaman telah ditandai sebagai "Terlambat".');
        return 0;
    }
}