<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;

class InvoicePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'receptionist', 'patient']);
    }

    public function view(User $user, Invoice $invoice): bool
    {
        return match ($user->role) {
            'admin', 'receptionist' => true,
            'patient' => $user->patient?->id === $invoice->patient_id,
            default => false,
        };
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'receptionist']);
    }

    public function recordPayment(User $user): bool
    {
        return in_array($user->role, ['admin', 'receptionist']);
    }
}
