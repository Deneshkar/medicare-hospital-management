@extends('layouts.app')

@section('title', $doctor->user->name)

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="text-xl font-semibold">{{ $doctor->user->name }}</h1>
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Email</dt><dd>{{ $doctor->user->email }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Phone</dt><dd>{{ $doctor->user->phone ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Department</dt><dd>{{ $doctor->department->name ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Specialization</dt><dd>{{ $doctor->specialization }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">License</dt><dd>{{ $doctor->license_number }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Experience</dt><dd>{{ $doctor->experience_years }} year(s)</dd></div>
        </dl>
        <div class="mt-4 flex gap-2">
            <a href="{{ route('web.doctors.edit', $doctor) }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">Edit</a>
            <form method="POST" action="{{ route('web.doctors.destroy', $doctor) }}" onsubmit="return confirm('Delete this doctor?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded border border-red-300 px-4 py-2 text-sm text-red-700 hover:bg-red-50">Delete</button>
            </form>
            <a href="{{ route('web.doctors.index') }}" class="rounded border px-4 py-2 text-sm hover:bg-slate-50">Back</a>
        </div>
    </div>
@endsection
