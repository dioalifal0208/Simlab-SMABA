<?php

namespace App\Notifications;

use App\Models\DamageReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewDamageReport extends Notification
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
        $userName = $this->report->user->name ?? 'Pengguna';

        return [
            'message' => $userName . ' melaporkan kerusakan untuk "' . $itemName . '".',
            'url' => route('damage-reports.show', $this->report->id),
        ];
    }
}

