<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Mail\LoanReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendLoanReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder emails for loans due tomorrow';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for loans due tomorrow...');
        
        // Get tomorrow's date
        $tomorrow = now()->addDay()->startOfDay();
        
        // Find all approved loans due tomorrow
        $loans = Loan::where('status', 'approved')
            ->whereDate('tanggal_estimasi_kembali', $tomorrow)
            ->with(['user', 'items'])
            ->get();
        
        if ($loans->isEmpty()) {
            $this->info('No loans due tomorrow. No emails sent.');
            return 0;
        }
        
        $sentCount = 0;
        $failedCount = 0;
        
        foreach ($loans as $loan) {
            try {
                // Hanya kirim email jika user punya notification_email terverifikasi
                if ($loan->user->hasVerifiedNotificationEmail()) {
                    Mail::mailer('smtp-notif')
                        ->to($loan->user->notification_email)
                        ->send(new LoanReminder($loan));
                    $this->line("✓ Sent reminder to {$loan->user->name} ({$loan->user->notification_email})");
                    $sentCount++;
                } else {
                    $this->line("⊘ Skipped {$loan->user->name} (no verified notification email)");
                }
            } catch (\Exception $e) {
                $this->error("✗ Failed to send to {$loan->user->notification_email}: " . $e->getMessage());
                $failedCount++;
            }
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("- Total loans due tomorrow: {$loans->count()}");
        $this->info("- Emails sent successfully: {$sentCount}");
        if ($failedCount > 0) {
            $this->warn("- Emails failed: {$failedCount}");
        }
        
        return 0;
    }
}
