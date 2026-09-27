<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrescriptionRequest;
use App\Http\Resources\PrescriptionResource;
use App\Models\Prescription;
use App\Notifications\PrescriptionCreated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Prescription::class);

        $query = Prescription::with(['patient.user', 'doctor.user', 'items']);

        $query = match ($request->user()->role) {
            'patient' => $query->where('patient_id', $request->user()->patient?->id),
            'doctor' => $query->where('doctor_id', $request->user()->doctor?->id),
            default => $query,
        };

        $prescriptions = $query->latest()->paginate(10);

        return PrescriptionResource::collection($prescriptions)
            ->additional(['success' => true]);
    }

    public function store(StorePrescriptionRequest $request)
    {
        $this->authorize('create', Prescription::class);

        $prescription = DB::transaction(function () use ($request) {
            $prescription = Prescription::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->user()->doctor->id,
                'medical_record_id' => $request->medical_record_id,
                'general_instructions' => $request->general_instructions,
            ]);

            foreach ($request->items as $item) {
                $prescription->items()->create($item);
            }

            return $prescription;
        });

        $prescription->patient->user->notify(new PrescriptionCreated($prescription));

        return (new PrescriptionResource($prescription->load(['patient.user', 'doctor.user', 'items'])))
            ->additional(['success' => true, 'message' => 'Prescription created successfully'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Prescription $prescription)
    {
        $this->authorize('view', $prescription);

        return (new PrescriptionResource($prescription->load(['patient.user', 'doctor.user', 'items'])))
            ->additional(['success' => true]);
    }

    public function downloadPdf(Prescription $prescription)
    {
        $this->authorize('view', $prescription);

        $prescription->load(['patient.user', 'doctor.user', 'items']);

        $pdf = Pdf::loadView('pdf.prescription', compact('prescription'));

        return $pdf->download("prescription-{$prescription->id}.pdf");
    }
}
