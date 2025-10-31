<?php

namespace App\Notifications;

use App\Models\DamageReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DamageReportStatusUpdated extends Notification
{
    use Queueable;

    protected DamageReport $report;

    public function __construct(DamageReport $report)
    {
        $this->report = $report;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        $itemName = $this->report->item->nama_alat ?? 'Item';
        $status   = $this->report->status ?? '-';

        return [
            'message' => 'Status laporan kerusakan untuk "' . $itemName . '" diperbarui menjadi ' . $status . '.',
            // Link untuk pelapor: arahkan ke halaman item (rute admin ke laporan tidak bisa diakses user)
            'url' => route('items.show', $this->report->item_id),
        ];
    }
}

