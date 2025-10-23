<?php

namespace App\Observers;

use App\Models\Loan;
use Illuminate\Support\Facades\DB;

class LoanObserver
{
    /**
     * Handle the Loan "created" event.
     * Biarkan method ini kosong.
     */
    public function created(Loan $loan): void
    {
        //
    }

    /**
     * Handle the Loan "updated" event.
     * Method ini akan berjalan setiap kali data peminjaman di-update.
     */
    public function updated(Loan $loan): void
    {
        // Hanya jalankan logika jika kolom 'status' yang berubah.
        if ($loan->isDirty('status')) {
            // Gunakan DB::transaction untuk memastikan semua operasi berhasil atau semua dibatalkan.
            DB::transaction(function () use ($loan) {

                // Logika saat peminjaman DISETUJUI.
                if ($loan->status === 'approved') {
                    foreach ($loan->items as $item) {
                        $jumlahDipinjam = $item->pivot->jumlah;
                        $item->jumlah -= $jumlahDipinjam; // Kurangi stok item.
                        $item->save();
                    }
                }

                // Logika saat peminjaman SELESAI atau DITOLAK.
                $statusOriginal = $loan->getOriginal('status');
                if (($loan->status === 'completed' || $loan->status === 'rejected') && $statusOriginal === 'approved') {
                    foreach ($loan->items as $item) {
                        $jumlahDipinjam = $item->pivot->jumlah;
                        $item->jumlah += $jumlahDipinjam; // Tambahkan/kembalikan stok item.
                        $item->save();
                    }
                }
            });
        }
    }
}