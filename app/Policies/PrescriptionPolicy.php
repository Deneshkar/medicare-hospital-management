<?php

namespace App\Policies;

use App\Models\Prescription;
use App\Models\User;

class PrescriptionPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'doctor', 'nurse', 'receptionist', 'patient']);
    }

    public function view(User $user, Prescription $prescription): bool
    {
        return match ($user->role) {
            'admin', 'nurse', 'receptionist' => true,
            'doctor' => $user->doctor?->id === $prescription->doctor_id,
            'patient' => $user->patient?->id === $prescription->patient_id,
            default => false,
        };
    }

    public function create(User $user): bool
    {
        return $user->role === 'doctor';
    }
}
