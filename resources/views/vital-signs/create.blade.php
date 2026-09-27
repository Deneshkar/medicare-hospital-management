@extends('layouts.app')

@section('title', 'Record vitals')

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-xl font-semibold">Record vital signs</h1>
        <form method="POST" action="{{ route('web.vital-signs.store') }}" class="space-y-4">
            @csrf
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
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="temperature" class="mb-1 block text-sm font-medium">Temperature (°C)</label>
                    <input id="temperature" name="temperature" type="number" step="0.1" value="{{ old('temperature') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
                <div>
                    <label for="blood_pressure" class="mb-1 block text-sm font-medium">Blood pressure</label>
                    <input id="blood_pressure" name="blood_pressure" type="text" placeholder="120/80" value="{{ old('blood_pressure') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
                <div>
                    <label for="heart_rate" class="mb-1 block text-sm font-medium">Heart rate (bpm)</label>
                    <input id="heart_rate" name="heart_rate" type="number" value="{{ old('heart_rate') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
                <div>
                    <label for="respiratory_rate" class="mb-1 block text-sm font-medium">Resp. rate (/min)</label>
                    <input id="respiratory_rate" name="respiratory_rate" type="number" value="{{ old('respiratory_rate') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
                <div>
                    <label for="oxygen_saturation" class="mb-1 block text-sm font-medium">SpO₂ (%)</label>
                    <input id="oxygen_saturation" name="oxygen_saturation" type="number" value="{{ old('oxygen_saturation') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
                <div>
                    <label for="weight" class="mb-1 block text-sm font-medium">Weight (kg)</label>
                    <input id="weight" name="weight" type="number" step="0.01" value="{{ old('weight') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
                <div>
                    <label for="height" class="mb-1 block text-sm font-medium">Height (cm)</label>
                    <input id="height" name="height" type="number" step="0.01" value="{{ old('height') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Save</button>
                <a href="{{ route('web.vital-signs.index') }}" class="rounded border px-4 py-2 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
