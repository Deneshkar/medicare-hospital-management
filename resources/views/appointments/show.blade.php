@extends('layouts.app')

@section('title', 'Appointment')

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="text-xl font-semibold">Appointment #{{ $appointment->id }}</h1>
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Patient</dt><dd>{{ $appointment->patient->user->name }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Doctor</dt><dd>{{ $appointment->doctor->user->name }} ({{ $appointment->doctor->specialization }})</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Date</dt><dd>{{ $appointment->appointment_date->format('M j, Y') }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Time</dt><dd>{{ $appointment->appointment_time }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Reason</dt><dd>{{ $appointment->reason ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-36 font-medium text-slate-500">Status</dt><dd><span class="rounded bg-slate-100 px-2 py-0.5 text-xs">{{ $appointment->status }}</span></dd></div>
        </dl>
        @can('updateStatus', $appointment)
            <form method="POST" action="{{ route('web.appointments.status', $appointment) }}" class="mt-4 flex items-end gap-2">
                @csrf
                @method('PATCH')
                <div class="flex-1">
                    <label for="status" class="mb-1 block text-sm font-medium">Change status</label>
                    <select id="status" name="status"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                        @foreach (['pending', 'confirmed', 'checked_in', 'in_progress', 'completed', 'cancelled', 'no_show'] as $status)
                            <option value="{{ $status }}" @selected($appointment->status === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">Update</button>
            </form>
        @endcan
        <div class="mt-4 flex gap-2">
            @can('cancel', $appointment)
                @if (! in_array($appointment->status, ['cancelled', 'completed']))
                    <form method="POST" action="{{ route('web.appointments.destroy', $appointment) }}" onsubmit="return confirm('Cancel this appointment?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded border border-red-300 px-4 py-2 text-sm text-red-700 hover:bg-red-50">Cancel appointment</button>
                    </form>
                @endif
            @endcan
            <a href="{{ route('web.appointments.index') }}" class="rounded border px-4 py-2 text-sm hover:bg-slate-50">Back</a>
        </div>
    </div>
@endsection
