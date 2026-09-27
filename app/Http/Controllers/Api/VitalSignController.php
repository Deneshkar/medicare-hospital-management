<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVitalSignRequest;
use App\Http\Resources\VitalSignResource;
use App\Models\VitalSign;
use Illuminate\Http\Request;

class VitalSignController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', VitalSign::class);

        $query = VitalSign::with(['patient.user', 'recordedBy']);

        if ($request->user()->role === 'patient') {
            $query->where('patient_id', $request->user()->patient?->id);
        }

        if ($request->filled('patient_id') && $request->user()->role !== 'patient') {
            $query->where('patient_id', $request->patient_id);
        }

        $vitalSigns = $query->latest('recorded_at')->paginate(10);

        return VitalSignResource::collection($vitalSigns)
            ->additional(['success' => true]);
    }

    public function store(StoreVitalSignRequest $request)
    {
        $this->authorize('create', VitalSign::class);

        $vitalSign = VitalSign::create([
            'patient_id' => $request->patient_id,
            'recorded_by' => $request->user()->id,
            'temperature' => $request->temperature,
            'blood_pressure' => $request->blood_pressure,
            'heart_rate' => $request->heart_rate,
            'respiratory_rate' => $request->respiratory_rate,
            'oxygen_saturation' => $request->oxygen_saturation,
            'weight' => $request->weight,
            'height' => $request->height,
            'recorded_at' => now(),
        ]);

        return (new VitalSignResource($vitalSign->load(['patient.user', 'recordedBy'])))
            ->additional(['success' => true, 'message' => 'Vital signs recorded successfully']);
    }

    public function show(VitalSign $vitalSign)
    {
        $this->authorize('view', $vitalSign);

        return (new VitalSignResource($vitalSign->load(['patient.user', 'recordedBy'])))
            ->additional(['success' => true]);
    }
}
