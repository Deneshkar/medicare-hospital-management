@extends('layouts.app')

@section('title', 'Edit patient')

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-xl font-semibold">Edit {{ $patient->user->name }}</h1>
        <form method="POST" action="{{ route('web.patients.update', $patient) }}" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label for="phone" class="mb-1 block text-sm font-medium">Phone</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone', $patient->user->phone) }}"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="date_of_birth" class="mb-1 block text-sm font-medium">Date of birth</label>
                    <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
                <div>
                    <label for="gender" class="mb-1 block text-sm font-medium">Gender</label>
                    <select id="gender" name="gender"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                        <option value="">Select…</option>
                        @foreach (['male', 'female', 'other'] as $gender)
                            <option value="{{ $gender }}" @selected(old('gender', $patient->gender) === $gender)>{{ ucfirst($gender) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="address" class="mb-1 block text-sm font-medium">Address</label>
                <input id="address" name="address" type="text" value="{{ old('address', $patient->address) }}"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div>
                <label for="blood_group" class="mb-1 block text-sm font-medium">Blood group</label>
                <input id="blood_group" name="blood_group" type="text" value="{{ old('blood_group', $patient->blood_group) }}"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="emergency_contact_name" class="mb-1 block text-sm font-medium">Emergency contact</label>
                    <input id="emergency_contact_name" name="emergency_contact_name" type="text" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
                <div>
                    <label for="emergency_contact_phone" class="mb-1 block text-sm font-medium">Emergency phone</label>
                    <input id="emergency_contact_phone" name="emergency_contact_phone" type="text" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label for="medical_notes" class="mb-1 block text-sm font-medium">Medical notes</label>
                <textarea id="medical_notes" name="medical_notes" rows="3"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">{{ old('medical_notes', $patient->medical_notes) }}</textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Save</button>
                <a href="{{ route('web.patients.show', $patient) }}" class="rounded border px-4 py-2 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
