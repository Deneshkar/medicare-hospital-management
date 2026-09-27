<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class AppointmentBooked extends Notification
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
            'message' => "New appointment booked for {$this->appointment->appointment_date->format('M j, Y')} at {$this->appointment->appointment_time}",
        ];
    }
}
