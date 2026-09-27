<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\User;
use App\Models\VitalSign;

class DashboardService
{
    public function for(User $user): array
    {
        return match ($user->role) {
            'admin' => $this->adminStats(),
            'doctor' => $this->doctorStats($user),
            'nurse' => $this->nurseStats(),
            'receptionist' => $this->receptionistStats(),
            'patient' => $this->patientStats($user),
        };
    }

    public function adminStats(): array
    {
        return [
            'total_patients' => Patient::count(),
            'total_doctors' => Doctor::count(),
            'total_nurses' => User::where('role', 'nurse')->count(),
            'total_appointments' => Appointment::count(),
            'todays_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'completed_appointments' => Appointment::where('status', 'completed')->count(),
            'revenue' => Invoice::where('payment_status', 'paid')->sum('total'),
            'recent_patients' => Patient::with('user')->latest()->take(5)->get()
                ->map(fn ($p) => ['id' => $p->id, 'name' => $p->user->name]),
            'recent_appointments' => Appointment::with(['patient.user', 'doctor.user'])->latest()->take(5)->get()
                ->map(fn ($a) => [
                    'id' => $a->id,
                    'patient' => $a->patient->user->name,
                    'doctor' => $a->doctor->user->name,
                    'date' => $a->appointment_date->format('Y-m-d'),
                    'status' => $a->status,
                ]),
        ];
    }

    public function doctorStats(User $user): array
    {
        $doctor = $user->doctor;

        return [
            'todays_appointments' => $doctor->appointments()->whereDate('appointment_date', today())->count(),
            'upcoming_appointments' => $doctor->appointments()
                ->where('appointment_date', '>=', today())
                ->whereIn('status', ['pending', 'confirmed'])
                ->count(),
            'total_patients' => $doctor->appointments()->distinct('patient_id')->count('patient_id'),
            'recent_medical_records' => $doctor->medicalRecords()->with('patient.user')->latest()->take(5)->get()
                ->map(fn ($r) => ['id' => $r->id, 'patient' => $r->patient->user->name, 'diagnosis' => $r->diagnosis]),
            'pending_lab_reports' => $doctor->labReports()->whereIn('status', ['requested', 'processing'])->count(),
        ];
    }

    public function nurseStats(): array
    {
        return [
            'assigned_patients' => Patient::count(),
            'todays_tasks' => Appointment::whereDate('appointment_date', today())
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->count(),
            'recent_vital_signs' => VitalSign::with('patient.user')->latest('recorded_at')->take(5)->get()
                ->map(fn ($v) => ['id' => $v->id, 'patient' => $v->patient->user->name, 'recorded_at' => $v->recorded_at]),
        ];
    }

    public function receptionistStats(): array
    {
        return [
            'todays_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            'pending_checkins' => Appointment::whereDate('appointment_date', today())
                ->where('status', 'confirmed')
                ->count(),
            'recent_patients' => Patient::with('user')->latest()->take(5)->get()
                ->map(fn ($p) => ['id' => $p->id, 'name' => $p->user->name]),
            'pending_invoices' => Invoice::where('payment_status', 'pending')->count(),
        ];
    }

    public function patientStats(User $user): array
    {
        $patient = $user->patient;

        return [
            'upcoming_appointment' => $patient->appointments()
                ->where('appointment_date', '>=', today())
                ->whereIn('status', ['pending', 'confirmed'])
                ->orderBy('appointment_date')
                ->first(),
            'recent_appointments' => $patient->appointments()->with('doctor.user')->latest('appointment_date')->take(5)->get()
                ->map(fn ($a) => [
                    'id' => $a->id,
                    'doctor' => $a->doctor->user->name,
                    'date' => $a->appointment_date->format('Y-m-d'),
                    'status' => $a->status,
                ]),
            'recent_prescriptions' => $patient->prescriptions()->latest()->take(5)->get(['id', 'created_at']),
            'recent_medical_records' => $patient->medicalRecords()->latest()->take(5)->get(['id', 'diagnosis', 'created_at']),
            'pending_invoices' => $patient->invoices()->where('payment_status', 'pending')->count(),
            'unread_notifications' => $user->unreadNotifications()->count(),
        ];
    }
}
