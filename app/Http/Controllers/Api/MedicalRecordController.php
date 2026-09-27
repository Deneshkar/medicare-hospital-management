<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMedicalRecordRequest;
use App\Http\Requests\UpdateMedicalRecordRequest;
use App\Http\Resources\MedicalRecordResource;
use App\Models\MedicalRecord;
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

        return MedicalRecordResource::collection($records)
            ->additional(['success' => true]);
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

        return (new MedicalRecordResource($record->load(['patient.user', 'doctor.user'])))
            ->additional(['success' => true, 'message' => 'Medical record created successfully']);
    }

    public function show(MedicalRecord $medicalRecord)
    {
        $this->authorize('view', $medicalRecord);

        return (new MedicalRecordResource($medicalRecord->load(['patient.user', 'doctor.user'])))
            ->additional(['success' => true]);
    }

    public function update(UpdateMedicalRecordRequest $request, MedicalRecord $medicalRecord)
    {
        $this->authorize('update', $medicalRecord);

        $medicalRecord->update($request->validated());

        return (new MedicalRecordResource($medicalRecord->load(['patient.user', 'doctor.user'])))
            ->additional(['success' => true, 'message' => 'Medical record updated successfully']);
    }

    public function destroy(MedicalRecord $medicalRecord)
    {
        $this->authorize('delete', $medicalRecord);

        $medicalRecord->delete();

        return response()->json(['success' => true, 'message' => 'Medical record deleted successfully']);
    }
}
