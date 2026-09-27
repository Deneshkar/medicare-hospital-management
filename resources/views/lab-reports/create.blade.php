@extends('layouts.app')

@section('title', 'Request lab test')

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-xl font-semibold">Request lab test</h1>
        <form method="POST" action="{{ route('web.lab-reports.store') }}" class="space-y-4">
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
            <div>
                <label for="test_name" class="mb-1 block text-sm font-medium">Test name</label>
                <input id="test_name" name="test_name" type="text" value="{{ old('test_name') }}" required
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Request</button>
                <a href="{{ route('web.lab-reports.index') }}" class="rounded border px-4 py-2 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
