@extends('layouts.app')

@section('title', 'Book appointment')

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-xl font-semibold">Book appointment</h1>
        <form method="POST" action="{{ route('web.appointments.store') }}" class="space-y-4">
            @csrf
            @if ($patients)
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
            @endif
            <div>
                <label for="doctor_id" class="mb-1 block text-sm font-medium">Doctor</label>
                <select id="doctor_id" name="doctor_id" required
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                    <option value="">Select…</option>
                    @foreach ($doctors as $doctor)
                        <option value="{{ $doctor->id }}" @selected(old('doctor_id') == $doctor->id)>{{ $doctor->user->name }} ({{ $doctor->specialization }})</option>
                    @endforeach
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="appointment_date" class="mb-1 block text-sm font-medium">Date</label>
                    <input id="appointment_date" name="appointment_date" type="date" value="{{ old('appointment_date') }}" required
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
                <div>
                    <label for="appointment_time" class="mb-1 block text-sm font-medium">Time</label>
                    <input id="appointment_time" name="appointment_time" type="time" value="{{ old('appointment_time') }}" required
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
            </div>
            <div>
                <label for="reason" class="mb-1 block text-sm font-medium">Reason</label>
                <textarea id="reason" name="reason" rows="3"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">{{ old('reason') }}</textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Book</button>
                <a href="{{ route('web.appointments.index') }}" class="rounded border px-4 py-2 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
