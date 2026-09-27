@extends('layouts.app')

@section('title', 'Lab reports')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Lab reports</h1>
        @can('create', App\Models\LabReport::class)
            <a href="{{ route('web.lab-reports.create') }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">Request test</a>
        @endcan
    </div>
    <div class="overflow-hidden rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-slate-50">
                <tr>
                    <th class="px-4 py-2">Test</th>
                    <th class="px-4 py-2">Patient</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">File</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($labReports as $report)
                    <tr>
                        <td class="px-4 py-2 font-medium">{{ $report->test_name }}</td>
                        <td class="px-4 py-2">{{ $report->patient->user->name }}</td>
                        <td class="px-4 py-2"><span class="rounded bg-slate-100 px-2 py-0.5 text-xs">{{ $report->status }}</span></td>
                        <td class="px-4 py-2">{{ $report->report_file ? 'Yes' : '—' }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('web.lab-reports.show', $report) }}" class="text-slate-700 underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-4 text-center text-slate-400">No lab reports yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $labReports->links() }}</div>
@endsection
