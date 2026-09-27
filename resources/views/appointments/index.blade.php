@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Appointments</h1>
        @can('create', App\Models\Appointment::class)
            <a href="{{ route('web.appointments.create') }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">Book appointment</a>
        @endcan
    </div>
    <div class="overflow-hidden rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-slate-50">
                <tr>
                    <th class="px-4 py-2">Date</th>
                    <th class="px-4 py-2">Time</th>
                    <th class="px-4 py-2">Patient</th>
                    <th class="px-4 py-2">Doctor</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($appointments as $appointment)
                    <tr>
                        <td class="px-4 py-2">{{ $appointment->appointment_date->format('Y-m-d') }}</td>
                        <td class="px-4 py-2">{{ $appointment->appointment_time }}</td>
                        <td class="px-4 py-2">{{ $appointment->patient->user->name }}</td>
                        <td class="px-4 py-2">{{ $appointment->doctor->user->name }}</td>
                        <td class="px-4 py-2"><span class="rounded bg-slate-100 px-2 py-0.5 text-xs">{{ $appointment->status }}</span></td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('web.appointments.show', $appointment) }}" class="text-slate-700 underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-4 py-4 text-center text-slate-400">No appointments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $appointments->links() }}</div>
@endsection
