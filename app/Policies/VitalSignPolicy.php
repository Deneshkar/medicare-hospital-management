<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VitalSign;

class VitalSignPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'doctor', 'nurse', 'receptionist', 'patient']);
    }

    public function view(User $user, VitalSign $vitalSign): bool
    {
        return match ($user->role) {
            'admin', 'doctor', 'nurse', 'receptionist' => true,
            'patient' => $user->patient?->id === $vitalSign->patient_id,
            default => false,
        };
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'nurse']);
    }
}
