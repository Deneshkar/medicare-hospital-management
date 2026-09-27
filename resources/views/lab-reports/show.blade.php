@extends('layouts.app')

@section('title', 'Lab report')

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="text-xl font-semibold">{{ $labReport->test_name }}</h1>
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Patient</dt><dd>{{ $labReport->patient->user->name }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Doctor</dt><dd>{{ $labReport->doctor->user->name }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Status</dt><dd><span class="rounded bg-slate-100 px-2 py-0.5 text-xs">{{ $labReport->status }}</span></dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Result</dt><dd>{{ $labReport->result ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Notes</dt><dd>{{ $labReport->doctor_notes ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">File</dt><dd>{{ $labReport->report_file ? 'Attached' : '—' }}</dd></div>
        </dl>
        <div class="mt-4 flex flex-wrap gap-2">
            @can('update', $labReport)
                <a href="{{ route('web.lab-reports.edit', $labReport) }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">Update / upload</a>
            @endcan
            @if ($labReport->report_file)
                <a href="{{ route('web.lab-reports.download', $labReport) }}" class="rounded border px-4 py-2 text-sm hover:bg-slate-50">Download file</a>
            @endif
            <a href="{{ route('web.lab-reports.index') }}" class="rounded border px-4 py-2 text-sm hover:bg-slate-50">Back</a>
        </div>
    </div>
@endsection
