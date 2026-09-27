@extends('layouts.app')

@section('title', 'Vital signs')

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="text-xl font-semibold">Vitals for {{ $vitalSign->patient->user->name }}</h1>
        <p class="mt-1 text-sm text-slate-500">Recorded by {{ $vitalSign->recordedBy->name }} — {{ $vitalSign->recorded_at->format('M j, Y H:i') }}</p>
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Temperature</dt><dd>{{ $vitalSign->temperature ? $vitalSign->temperature.' °C' : '—' }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Blood pressure</dt><dd>{{ $vitalSign->blood_pressure ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Heart rate</dt><dd>{{ $vitalSign->heart_rate ? $vitalSign->heart_rate.' bpm' : '—' }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Respiratory rate</dt><dd>{{ $vitalSign->respiratory_rate ? $vitalSign->respiratory_rate.' /min' : '—' }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Oxygen saturation</dt><dd>{{ $vitalSign->oxygen_saturation ? $vitalSign->oxygen_saturation.' %' : '—' }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Weight</dt><dd>{{ $vitalSign->weight ? $vitalSign->weight.' kg' : '—' }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Height</dt><dd>{{ $vitalSign->height ? $vitalSign->height.' cm' : '—' }}</dd></div>
        </dl>
        <div class="mt-4">
            <a href="{{ route('web.vital-signs.index') }}" class="rounded border px-4 py-2 text-sm hover:bg-slate-50">Back</a>
        </div>
    </div>
@endsection
