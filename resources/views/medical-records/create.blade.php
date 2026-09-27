@extends('layouts.app')

@section('title', 'New medical record')

@section('content')
    <div class="mx-auto max-w-xl rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-xl font-semibold">New medical record</h1>
        <form method="POST" action="{{ route('web.medical-records.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="patient_id" class="mb-1 block text-sm font-medium">Patient</label>
                    <select id="patient_id" name="patient_id" required
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                        <option value="">Select…</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>{{ $patient->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="appointment_id" class="mb-1 block text-sm font-medium">Appointment (optional)</label>
                    <select id="appointment_id" name="appointment_id"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                        <option value="">None</option>
                        @foreach ($appointments as $appointment)
                            <option value="{{ $appointment->id }}" @selected(old('appointment_id') == $appointment->id)>#{{ $appointment->id }} — {{ $appointment->patient->user->name }} ({{ $appointment->appointment_date->format('Y-m-d') }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            @foreach (['symptoms' => 'Symptoms', 'diagnosis' => 'Diagnosis', 'treatment' => 'Treatment', 'doctor_notes' => 'Doctor notes'] as $field => $label)
                <div>
                    <label for="{{ $field }}" class="mb-1 block text-sm font-medium">{{ $label }}</label>
                    <textarea id="{{ $field }}" name="{{ $field }}" rows="2"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">{{ old($field) }}</textarea>
                </div>
            @endforeach
            <div>
                <label for="follow_up_date" class="mb-1 block text-sm font-medium">Follow-up date</label>
                <input id="follow_up_date" name="follow_up_date" type="date" value="{{ old('follow_up_date') }}"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Save</button>
                <a href="{{ route('web.medical-records.index') }}" class="rounded border px-4 py-2 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
