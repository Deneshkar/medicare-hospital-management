@extends('layouts.app')

@section('title', 'Edit medical record')

@section('content')
    <div class="mx-auto max-w-xl rounded bg-white p-6 shadow">
        <h1 class="mb-1 text-xl font-semibold">Edit record #{{ $medicalRecord->id }}</h1>
        <p class="mb-4 text-sm text-slate-500">Patient: {{ $medicalRecord->patient->user->name }} (cannot be reassigned)</p>
        <form method="POST" action="{{ route('web.medical-records.update', $medicalRecord) }}" class="space-y-4">
            @csrf
            @method('PUT')
            @foreach (['symptoms' => 'Symptoms', 'diagnosis' => 'Diagnosis', 'treatment' => 'Treatment', 'doctor_notes' => 'Doctor notes'] as $field => $label)
                <div>
                    <label for="{{ $field }}" class="mb-1 block text-sm font-medium">{{ $label }}</label>
                    <textarea id="{{ $field }}" name="{{ $field }}" rows="2"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">{{ old($field, $medicalRecord->$field) }}</textarea>
                </div>
            @endforeach
            <div>
                <label for="follow_up_date" class="mb-1 block text-sm font-medium">Follow-up date</label>
                <input id="follow_up_date" name="follow_up_date" type="date" value="{{ old('follow_up_date', $medicalRecord->follow_up_date?->format('Y-m-d')) }}"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Save</button>
                <a href="{{ route('web.medical-records.show', $medicalRecord) }}" class="rounded border px-4 py-2 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
