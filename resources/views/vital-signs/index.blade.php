@extends('layouts.app')

@section('title', 'Vital signs')

@section('content')
    <div class="mb-4 flex flex-wrap items-center justify-between gap-2">
        <h1 class="text-xl font-semibold">Vital signs</h1>
        <div class="flex gap-2">
            @if ($patients)
                <form method="GET" action="{{ route('web.vital-signs.index') }}" class="flex gap-2">
                    <select name="patient_id" onchange="this.form.submit()"
                        class="rounded border border-slate-300 px-3 py-1.5 text-sm focus:border-slate-500 focus:outline-none">
                        <option value="">All patients</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(request('patient_id') == $patient->id)>{{ $patient->user->name }}</option>
                        @endforeach
                    </select>
                </form>
            @endif
            @can('create', App\Models\VitalSign::class)
                <a href="{{ route('web.vital-signs.create') }}" class="rounded bg-slate-800 px-4 py-1.5 text-sm text-white hover:bg-slate-700">Record vitals</a>
            @endcan
        </div>
    </div>
    <div class="overflow-hidden rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-slate-50">
                <tr>
                    <th class="px-4 py-2">Patient</th>
                    <th class="px-4 py-2">Temp (°C)</th>
                    <th class="px-4 py-2">BP</th>
                    <th class="px-4 py-2">HR</th>
                    <th class="px-4 py-2">SpO₂</th>
                    <th class="px-4 py-2">Recorded</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($vitalSigns as $vitalSign)
                    <tr>
                        <td class="px-4 py-2 font-medium">{{ $vitalSign->patient->user->name }}</td>
                        <td class="px-4 py-2">{{ $vitalSign->temperature ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $vitalSign->blood_pressure ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $vitalSign->heart_rate ?? '—' }}</td>
                        <td class="px-4 py-2">{{ $vitalSign->oxygen_saturation ?? '—' }}</td>
                        <td class="px-4 py-2 text-slate-600">{{ $vitalSign->recorded_at->format('M j, Y H:i') }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('web.vital-signs.show', $vitalSign) }}" class="text-slate-700 underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-4 py-4 text-center text-slate-400">No vital signs yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $vitalSigns->appends(request()->query())->links() }}</div>
@endsection
