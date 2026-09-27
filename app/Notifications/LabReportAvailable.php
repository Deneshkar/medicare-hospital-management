<?php

namespace App\Notifications;

use App\Models\LabReport;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LabReportAvailable extends Notification
{
    use Queueable;

    public function __construct(public LabReport $labReport) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'lab_report_id' => $this->labReport->id,
            'message' => "Your {$this->labReport->test_name} results are now available",
        ];
    }
}
