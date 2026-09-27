<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVitalSignRequest;
use App\Models\Patient;
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

        $patients = $request->user()->role === 'patient'
            ? null
            : Patient::with('user')->orderBy('id')->get();

        return view('vital-signs.index', compact('vitalSigns', 'patients'));
    }

    public function create()
    {
        $this->authorize('create', VitalSign::class);

        $patients = Patient::with('user')->orderBy('id')->get();

        return view('vital-signs.create', compact('patients'));
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

        return redirect()->route('web.vital-signs.show', $vitalSign)
            ->with('success', 'Vital signs recorded successfully.');
    }

    public function show(VitalSign $vitalSign)
    {
        $this->authorize('view', $vitalSign);

        $vitalSign->load(['patient.user', 'recordedBy']);

        return view('vital-signs.show', compact('vitalSign'));
    }
}
