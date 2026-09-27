<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentStatusRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
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

        return view('appointments.index', compact('appointments'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Appointment::class);

        $doctors = Doctor::with('user')->get();
        $patients = in_array($request->user()->role, ['admin', 'receptionist'])
            ? Patient::with('user')->get()
            : null;

        return view('appointments.create', compact('doctors', 'patients'));
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

        return redirect()->route('web.appointments.show', $appointment)
            ->with('success', 'Appointment booked successfully.');
    }

    public function show(Appointment $appointment)
    {
        $this->authorize('view', $appointment);

        $appointment->load(['patient.user', 'doctor.user']);

        return view('appointments.show', compact('appointment'));
    }

    public function updateStatus(UpdateAppointmentStatusRequest $request, Appointment $appointment)
    {
        $this->authorize('updateStatus', $appointment);

        $appointment->update(['status' => $request->status]);

        return redirect()->route('web.appointments.show', $appointment)
            ->with('success', 'Appointment status updated.');
    }

    public function destroy(Appointment $appointment)
    {
        $this->authorize('cancel', $appointment);

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('web.appointments.index')
            ->with('success', 'Appointment cancelled.');
    }
}
