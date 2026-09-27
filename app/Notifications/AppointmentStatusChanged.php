<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentStatusChanged extends Notification
{
    use Queueable;

    public function __construct(public Appointment $appointment) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'appointment_id' => $this->appointment->id,
            'message' => "Your appointment on {$this->appointment->appointment_date->format('M j, Y')} is now {$this->appointment->status}",
        ];
    }
}
