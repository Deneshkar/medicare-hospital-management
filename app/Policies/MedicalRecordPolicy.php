<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\User;

class MedicalRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'doctor', 'nurse', 'receptionist', 'patient']);
    }

    public function view(User $user, MedicalRecord $record): bool
    {
        return match ($user->role) {
            'admin' => true,
            'doctor' => $user->doctor?->id === $record->doctor_id,
            'nurse', 'receptionist' => true,
            'patient' => $user->patient?->id === $record->patient_id,
            default => false,
        };
    }

    public function create(User $user): bool
    {
        return $user->role === 'doctor';
    }

    public function update(User $user, MedicalRecord $record): bool
    {
        return $user->role === 'doctor' && $user->doctor?->id === $record->doctor_id;
    }

    public function delete(User $user, MedicalRecord $record): bool
    {
        return $user->role === 'admin';
    }
}
