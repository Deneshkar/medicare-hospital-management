<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentStatusRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Notifications\AppointmentBooked;
use App\Notifications\AppointmentStatusChanged;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Appointment::class);

        $query = Appointment::with(['patient.user', 'doctor.user']);

        $query = match ($request->user()->role) {
            'patient' => $query->where('patient_id', $request->user()->patient?->id),
            'doctor' => $query->where('doctor_id', $request->user()->doctor?->id),
            default => $query,
        };

        $appointments = $query->latest('appointment_date')->paginate(10);

        return AppointmentResource::collection($appointments)
            ->additional(['success' => true]);
    }

    public function store(StoreAppointmentRequest $request)
    {
        $this->authorize('create', Appointment::class);

        $patientId = $request->user()->role === 'patient'
            ? $request->user()->patient->id
            : $request->patient_id;

        $appointment = Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        $appointment->patient->user->notify(new AppointmentBooked($appointment));

        return (new AppointmentResource($appointment->load(['patient.user', 'doctor.user'])))
            ->additional(['success' => true, 'message' => 'Appointment booked successfully']);
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);

        return (new AppointmentResource($appointment->load(['patient.user', 'doctor.user'])))
            ->additional(['success' => true]);
    }

    public function updateStatus(UpdateAppointmentStatusRequest $request, Appointment $appointment)
    {
        $this->authorize('updateStatus', $appointment);

        $appointment->update(['status' => $request->status]);

        $appointment->patient->user->notify(new AppointmentStatusChanged($appointment));

        return (new AppointmentResource($appointment->load(['patient.user', 'doctor.user'])))
            ->additional(['success' => true, 'message' => 'Appointment status updated']);
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('cancel', $appointment);

        $appointment->update(['status' => 'cancelled']);

        return response()->json(['success' => true, 'message' => 'Appointment cancelled']);
    }
}
