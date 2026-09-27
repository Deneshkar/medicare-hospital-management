<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePrescriptionRequest;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Prescription;
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

        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create()
    {
        $this->authorize('create', Prescription::class);

        $patients = Patient::with('user')->orderBy('id')->get();
        $medicalRecords = MedicalRecord::with('patient.user')->latest()->take(50)->get();

        return view('prescriptions.create', compact('patients', 'medicalRecords'));
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
                $prescription->items()->create([
                    'medicine_name' => $item['medicine_name'],
                    'dosage' => $item['dosage'],
                    'frequency' => $item['frequency'],
                    'duration' => $item['duration'],
                    'instructions' => $item['instructions'] ?? null,
                ]);
            }

            return $prescription;
        });

        return redirect()->route('web.prescriptions.show', $prescription)
            ->with('success', 'Prescription created successfully.');
    }

    public function show(Prescription $prescription)
    {
        $this->authorize('view', $prescription);

        $prescription->load(['patient.user', 'doctor.user', 'items']);

        return view('prescriptions.show', compact('prescription'));
    }

    public function downloadPdf(Prescription $prescription)
    {
        $this->authorize('view', $prescription);

        $prescription->load(['patient.user', 'doctor.user', 'items']);

        $pdf = Pdf::loadView('pdf.prescription', compact('prescription'));

        return $pdf->download("prescription-{$prescription->id}.pdf");
    }
}
