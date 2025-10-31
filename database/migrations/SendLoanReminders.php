<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Notifications\LoanReminderNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class SendLoanReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-loan-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mencari peminjaman yang akan jatuh tempo dan mengirim notifikasi pengingat';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mulai mencari peminjaman yang akan jatuh tempo...');

        // Tentukan tanggal target (besok)
        $targetDate = Carbon::tomorrow()->toDateString();

        // Cari semua peminjaman yang:
        // 1. Statusnya 'approved' (masih berjalan)
        // 2. Tanggal estimasi kembalinya adalah besok
        // 3. Belum pernah dikirimi notifikasi pengingat (reminder_sent_at masih NULL)
        $loansToRemind = Loan::with('user')
            ->where('status', 'approved')
            ->whereDate('tanggal_estimasi_kembali', $targetDate)
            ->whereNull('reminder_sent_at')
            ->get();

        if ($loansToRemind->isEmpty()) {
            $this->info('Tidak ada peminjaman yang jatuh tempo besok. Tidak ada notifikasi yang dikirim.');
            return;
        }

        $this->info("Ditemukan {$loansToRemind->count()} peminjaman yang akan dikirimi pengingat.");

        foreach ($loansToRemind as $loan) {
            Notification::send($loan->user, new LoanReminderNotification($loan));

            // Tandai bahwa notifikasi sudah dikirim agar tidak dikirim lagi
            $loan->update(['reminder_sent_at' => now()]);
        }

        $this->info('Semua notifikasi pengingat berhasil dikirim.');
    }
}