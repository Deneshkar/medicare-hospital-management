@extends('layouts.app')

@section('title', 'Prescriptions')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Prescriptions</h1>
        @can('create', App\Models\Prescription::class)
            <a href="{{ route('web.prescriptions.create') }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">New prescription</a>
        @endcan
    </div>
    <div class="overflow-hidden rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-slate-50">
                <tr>
                    <th class="px-4 py-2">Patient</th>
                    <th class="px-4 py-2">Doctor</th>
                    <th class="px-4 py-2">Medicines</th>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($prescriptions as $prescription)
                    <tr>
                        <td class="px-4 py-2 font-medium">{{ $prescription->patient->user->name }}</td>
                        <td class="px-4 py-2">{{ $prescription->doctor->user->name }}</td>
                        <td class="px-4 py-2 text-slate-600">{{ $prescription->items->count() }} item(s)</td>
                        <td class="px-4 py-2 text-slate-600">{{ $prescription->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('web.prescriptions.show', $prescription) }}" class="text-slate-700 underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-4 text-center text-slate-400">No prescriptions yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $prescriptions->links() }}</div>
@endsection
