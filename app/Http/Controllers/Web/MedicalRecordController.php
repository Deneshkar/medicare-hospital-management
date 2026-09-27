<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Requests\UpdateMedicalRecordRequest;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Patient;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', MedicalRecord::class);

        $query = MedicalRecord::with(['patient.user', 'doctor.user']);

        $query = match ($request->user()->role) {
            'patient' => $query->where('patient_id', $request->user()->patient?->id),
            'doctor' => $query->where('doctor_id', $request->user()->doctor?->id),
            default => $query,
        };

        $records = $query->latest()->paginate(10);

        return view('medical-records.index', compact('records'));
    }

    public function create()
    {
        $this->authorize('create', MedicalRecord::class);

        $patients = Patient::with('user')->get();
        $appointments = Appointment::with(['patient.user'])->latest('appointment_date')->take(50)->get();

        return view('medical-records.create', compact('patients', 'appointments'));
    }

    public function store(StoreMedicalRecordRequest $request)
    {
        $this->authorize('create', MedicalRecord::class);

        $record = MedicalRecord::create([
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->user()->doctor->id,
            'appointment_id' => $request->appointment_id,
            'symptoms' => $request->symptoms,
            'diagnosis' => $request->diagnosis,
            'treatment' => $request->treatment,
            'doctor_notes' => $request->doctor_notes,
            'follow_up_date' => $request->follow_up_date,
        ]);

        return redirect()->route('web.medical-records.show', $record)
            ->with('success', 'Medical record created successfully.');
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $this->authorize('view', $medicalRecord);

        $medicalRecord->load(['patient.user', 'doctor.user']);

        return view('medical-records.show', compact('medicalRecord'));
    }

    public function edit(MedicalRecord $medicalRecord)
    {
        $this->authorize('update', $medicalRecord);

        $medicalRecord->load(['patient.user']);

        return view('medical-records.edit', compact('medicalRecord'));
    }

    public function update(UpdateMedicalRecordRequest $request, MedicalRecord $medicalRecord)
    {
        $this->authorize('update', $medicalRecord);

        $medicalRecord->update($request->validated());

        return redirect()->route('web.medical-records.show', $medicalRecord)
            ->with('success', 'Medical record updated successfully.');
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $this->authorize('delete', $medicalRecord);

        $medicalRecord->delete();

        return redirect()->route('web.medical-records.index')
            ->with('success', 'Medical record deleted successfully.');
    }
}
