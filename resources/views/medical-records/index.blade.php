@extends('layouts.app')

@section('title', 'Medical records')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Medical records</h1>
        @can('create', App\Models\MedicalRecord::class)
            <a href="{{ route('web.medical-records.create') }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">New record</a>
        @endcan
    </div>
    <div class="overflow-hidden rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-slate-50">
                <tr>
                    <th class="px-4 py-2">Patient</th>
                    <th class="px-4 py-2">Doctor</th>
                    <th class="px-4 py-2">Diagnosis</th>
                    <th class="px-4 py-2">Follow-up</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($records as $record)
                    <tr>
                        <td class="px-4 py-2 font-medium">{{ $record->patient->user->name }}</td>
                        <td class="px-4 py-2">{{ $record->doctor->user->name }}</td>
                        <td class="px-4 py-2 text-slate-600">{{ Str::limit($record->diagnosis, 50) ?? '—' }}</td>
                        <td class="px-4 py-2 text-slate-600">{{ $record->follow_up_date?->format('Y-m-d') ?? '—' }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('web.medical-records.show', $record) }}" class="text-slate-700 underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-4 text-center text-slate-400">No medical records yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $records->links() }}</div>
@endsection
