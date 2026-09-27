<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('department', 'user')->paginate(10);

        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        $departments = Department::orderBy('name')->get();

        return view('doctors.create', compact('departments'));
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

        return redirect()->route('web.doctors.show', $doctor)
            ->with('success', 'Doctor created successfully.');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load('department', 'user');

        return view('doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $doctor->load('user');
        $departments = Department::orderBy('name')->get();

        return view('doctors.edit', compact('doctor', 'departments'));
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $doctor->update($request->only('department_id', 'specialization', 'license_number', 'experience_years'));
        $doctor->user->update($request->only('name', 'email', 'phone'));

        return redirect()->route('web.doctors.show', $doctor)
            ->with('success', 'Doctor updated successfully.');
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->user->delete();

        return redirect()->route('web.doctors.index')
            ->with('success', 'Doctor deleted successfully.');
    }
}
