<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Patient::class);

        $patients = Patient::with('user')->paginate(10);

        return PatientResource::collection($patients)
            ->additional(['success' => true]);
    }

    public function store(StorePatientRequest $request)
    {
        $this->authorize('create', Patient::class);

        $patient = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => 'patient',
                'password' => Hash::make(Str::random(12)),
            ]);

            return Patient::create([
                'user_id' => $user->id,
                'date_of_birth' => $request->date_of_birth,
                'gender' => $request->gender,
                'address' => $request->address,
                'blood_group' => $request->blood_group,
                'emergency_contact_name' => $request->emergency_contact_name,
                'emergency_contact_phone' => $request->emergency_contact_phone,
            ]);
        });

        return (new PatientResource($patient->load('user')))
            ->additional(['success' => true, 'message' => 'Patient registered successfully']);
    }

    public function show(Patient $patient)
    {
        $this->authorize('view', $patient);

        return (new PatientResource($patient->load('user')))
            ->additional(['success' => true]);
    }

    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $this->authorize('update', $patient);

        $patient->update($request->validated());

        return (new PatientResource($patient->load('user')))
            ->additional(['success' => true, 'message' => 'Patient profile updated successfully']);
    }

    public function destroy(Patient $patient)
    {
        $this->authorize('delete', Patient::class);

        $patient->user->delete();

        return response()->json(['success' => true, 'message' => 'Patient deleted successfully']);
    }
}
