<?php

namespace App\Policies;

use App\Models\LabReport;
use App\Models\User;

class LabReportPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'doctor', 'nurse', 'receptionist', 'patient']);
    }

    public function view(User $user, LabReport $labReport): bool
    {
        return match ($user->role) {
            'admin', 'nurse', 'receptionist' => true,
            'doctor' => $user->doctor?->id === $labReport->doctor_id,
            'patient' => $user->patient?->id === $labReport->patient_id,
            default => false,
        };
    }

    public function create(User $user): bool
    {
        return $user->role === 'doctor';
    }

    public function update(User $user, LabReport $labReport): bool
    {
        return in_array($user->role, ['admin', 'doctor', 'nurse']);
    }
}
