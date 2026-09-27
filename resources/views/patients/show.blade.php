@extends('layouts.app')

@section('title', $patient->user->name)

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="text-xl font-semibold">{{ $patient->user->name }}</h1>
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Email</dt><dd>{{ $patient->user->email }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Phone</dt><dd>{{ $patient->user->phone ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Date of birth</dt><dd>{{ $patient->date_of_birth?->format('Y-m-d') ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Gender</dt><dd>{{ $patient->gender ? ucfirst($patient->gender) : '—' }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Address</dt><dd>{{ $patient->address ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Blood group</dt><dd>{{ $patient->blood_group ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Emergency contact</dt><dd>{{ $patient->emergency_contact_name ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Emergency phone</dt><dd>{{ $patient->emergency_contact_phone ?? '—' }}</dd></div>
        </dl>
        <div class="mt-4 flex gap-2">
            @can('update', $patient)
                <a href="{{ route('web.patients.edit', $patient) }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">Edit</a>
            @endcan
            @can('delete', App\Models\Patient::class)
                <form method="POST" action="{{ route('web.patients.destroy', $patient) }}" onsubmit="return confirm('Delete this patient?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded border border-red-300 px-4 py-2 text-sm text-red-700 hover:bg-red-50">Delete</button>
                </form>
            @endcan
            <a href="{{ route('web.patients.index') }}" class="rounded border px-4 py-2 text-sm hover:bg-slate-50">Back</a>
        </div>
    </div>
@endsection
