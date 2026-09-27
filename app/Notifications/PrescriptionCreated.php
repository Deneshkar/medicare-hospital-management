<?php

namespace App\Notifications;

use App\Models\Prescription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PrescriptionCreated extends Notification
{
    use Queueable;

    public function __construct(public Prescription $prescription) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'prescription_id' => $this->prescription->id,
            'message' => 'A new prescription has been added to your record',
        ];
    }
}
