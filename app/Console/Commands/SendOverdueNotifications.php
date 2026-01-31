<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Mail\LoanOverdue;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendOverdueNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'loans:send-overdue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send overdue notifications for late loan returns';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for overdue loans...');
        
        // Find all approved loans that are overdue
        $overdueLoans = Loan::where('status', 'approved')
            ->where('tanggal_estimasi_kembali', '<', now())
            ->with(['user', 'items'])
            ->get();
        
        if ($overdueLoans->isEmpty()) {
            $this->info('No overdue loans found. No emails sent.');
            return 0;
        }
        
        $sentCount = 0;
        $failedCount = 0;
        
        foreach ($overdueLoans as $loan) {
            $daysOverdue = now()->diffInDays($loan->tanggal_estimasi_kembali);
            
            try {
                Mail::to($loan->user->email)->send(new LoanOverdue($loan));
                $this->line("✓ Sent overdue notice to {$loan->user->name} ({$daysOverdue} days late)");
                $sentCount++;
            } catch (\Exception $e) {
                $this->error("✗ Failed to send to {$loan->user->email}: " . $e->getMessage());
                $failedCount++;
            }
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("- Total overdue loans: {$overdueLoans->count()}");
        $this->info("- Emails sent successfully: {$sentCount}");
        if ($failedCount > 0) {
            $this->warn("- Emails failed: {$failedCount}");
        }
        
        return 0;
    }
}
