<?php

namespace App\Policies;

use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Appointment $appointment): bool
    {
        return match ($user->role) {
            'admin', 'receptionist' => true,
            'doctor' => $user->doctor?->id === $appointment->doctor_id,
            'patient' => $user->patient?->id === $appointment->patient_id,
            default => false,
        };
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'receptionist', 'patient']);
    }

    public function updateStatus(User $user, Appointment $appointment): bool
    {
        return match ($user->role) {
            'admin', 'receptionist' => true,
            'doctor' => $user->doctor?->id === $appointment->doctor_id,
            'patient' => $user->patient?->id === $appointment->patient_id,
            default => false,
        };
    }

    public function cancel(User $user, Appointment $appointment): bool
    {
        return $this->updateStatus($user, $appointment);
    }
}
