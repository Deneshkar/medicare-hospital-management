<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Requests\UpdateDoctorRequest;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('department', 'user')->paginate(10);

        return DoctorResource::collection($doctors)
            ->additional(['success' => true]);
    }

    public function store(StoreDoctorRequest $request)
    {
        $doctor = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => 'doctor',
                'password' => Hash::make(Str::random(12)),
            ]);

            return Doctor::create([
                'user_id' => $user->id,
                'department_id' => $request->department_id,
                'specialization' => $request->specialization,
                'license_number' => $request->license_number,
                'experience_years' => $request->experience_years ?? 0,
            ]);
        });

        return (new DoctorResource($doctor->load('department', 'user')))
            ->additional(['success' => true, 'message' => 'Doctor created successfully']);
    }

    public function show(Doctor $doctor)
    {
        return (new DoctorResource($doctor->load('department', 'user')))
            ->additional(['success' => true]);
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->only('department_id', 'specialization', 'license_number', 'experience_years'));
        $doctor->user->update($request->only('name', 'email', 'phone'));

        return (new DoctorResource($doctor->load('department', 'user')))
            ->additional(['success' => true, 'message' => 'Doctor updated successfully']);
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->user->delete();

        return response()->json(['success' => true, 'message' => 'Doctor deleted successfully']);
    }
}
