@extends('layouts.app')

@section('title', 'Medical record')

@section('content')
    <div class="mx-auto max-w-xl rounded bg-white p-6 shadow">
        <h1 class="text-xl font-semibold">Record #{{ $medicalRecord->id }}</h1>
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Patient</dt><dd>{{ $medicalRecord->patient->user->name }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Doctor</dt><dd>{{ $medicalRecord->doctor->user->name }} ({{ $medicalRecord->doctor->specialization }})</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Appointment</dt><dd>{{ $medicalRecord->appointment_id ? '#'.$medicalRecord->appointment_id : '—' }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Symptoms</dt><dd>{{ $medicalRecord->symptoms ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Diagnosis</dt><dd>{{ $medicalRecord->diagnosis ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Treatment</dt><dd>{{ $medicalRecord->treatment ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Doctor notes</dt><dd>{{ $medicalRecord->doctor_notes ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Follow-up</dt><dd>{{ $medicalRecord->follow_up_date?->format('M j, Y') ?? '—' }}</dd></div>
        </dl>
        <div class="mt-4 flex gap-2">
            @can('update', $medicalRecord)
                <a href="{{ route('web.medical-records.edit', $medicalRecord) }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">Edit</a>
            @endcan
            @can('delete', $medicalRecord)
                <form method="POST" action="{{ route('web.medical-records.destroy', $medicalRecord) }}" onsubmit="return confirm('Delete this record?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded border border-red-300 px-4 py-2 text-sm text-red-700 hover:bg-red-50">Delete</button>
                </form>
            @endcan
            <a href="{{ route('web.medical-records.index') }}" class="rounded border px-4 py-2 text-sm hover:bg-slate-50">Back</a>
        </div>
    </div>
@endsection
