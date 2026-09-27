@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1 class="mb-4 text-xl font-semibold">Welcome, {{ auth()->user()->name }}</h1>

    @switch($role)
        @case('admin')
            <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['total_patients'] }}</div><div class="text-sm text-slate-500">Patients</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['total_doctors'] }}</div><div class="text-sm text-slate-500">Doctors</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['total_nurses'] }}</div><div class="text-sm text-slate-500">Nurses</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['total_appointments'] }}</div><div class="text-sm text-slate-500">Appointments</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['todays_appointments'] }}</div><div class="text-sm text-slate-500">Today's appointments</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['pending_appointments'] }}</div><div class="text-sm text-slate-500">Pending</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['completed_appointments'] }}</div><div class="text-sm text-slate-500">Completed</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ number_format($stats['revenue'], 2) }}</div><div class="text-sm text-slate-500">Revenue (paid)</div></div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded bg-white p-4 shadow">
                    <h2 class="mb-2 font-semibold">Recent patients</h2>
                    <ul class="divide-y text-sm">
                        @forelse ($stats['recent_patients'] as $p)
                            <li class="py-1.5">{{ $p['name'] }}</li>
                        @empty
                            <li class="py-1.5 text-slate-400">No patients yet.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="rounded bg-white p-4 shadow">
                    <h2 class="mb-2 font-semibold">Recent appointments</h2>
                    <ul class="divide-y text-sm">
                        @forelse ($stats['recent_appointments'] as $a)
                            <li class="py-1.5">{{ $a['patient'] }} with {{ $a['doctor'] }} — {{ $a['date'] }} ({{ $a['status'] }})</li>
                        @empty
                            <li class="py-1.5 text-slate-400">No appointments yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @break

        @case('doctor')
            <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['todays_appointments'] }}</div><div class="text-sm text-slate-500">Today's appointments</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['upcoming_appointments'] }}</div><div class="text-sm text-slate-500">Upcoming</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['total_patients'] }}</div><div class="text-sm text-slate-500">Patients seen</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['pending_lab_reports'] }}</div><div class="text-sm text-slate-500">Pending lab reports</div></div>
            </div>
            <div class="rounded bg-white p-4 shadow">
                <h2 class="mb-2 font-semibold">Recent medical records</h2>
                <ul class="divide-y text-sm">
                    @forelse ($stats['recent_medical_records'] as $r)
                        <li class="py-1.5">{{ $r['patient'] }} — {{ $r['diagnosis'] ?? 'No diagnosis' }}</li>
                    @empty
                        <li class="py-1.5 text-slate-400">No records yet.</li>
                    @endforelse
                </ul>
            </div>
            @break

        @case('nurse')
            <div class="mb-6 grid grid-cols-2 gap-4">
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['assigned_patients'] }}</div><div class="text-sm text-slate-500">Patients</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['todays_tasks'] }}</div><div class="text-sm text-slate-500">Today's tasks</div></div>
            </div>
            <div class="rounded bg-white p-4 shadow">
                <h2 class="mb-2 font-semibold">Recent vital signs</h2>
                <ul class="divide-y text-sm">
                    @forelse ($stats['recent_vital_signs'] as $v)
                        <li class="py-1.5">{{ $v['patient'] }} — {{ \Carbon\Carbon::parse($v['recorded_at'])->format('M j, Y H:i') }}</li>
                    @empty
                        <li class="py-1.5 text-slate-400">No vital signs recorded yet.</li>
                    @endforelse
                </ul>
            </div>
            @break

        @case('receptionist')
            <div class="mb-6 grid grid-cols-2 gap-4 md:grid-cols-3">
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['todays_appointments'] }}</div><div class="text-sm text-slate-500">Today's appointments</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['pending_checkins'] }}</div><div class="text-sm text-slate-500">Pending check-ins</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['pending_invoices'] }}</div><div class="text-sm text-slate-500">Pending invoices</div></div>
            </div>
            <div class="rounded bg-white p-4 shadow">
                <h2 class="mb-2 font-semibold">Recent patients</h2>
                <ul class="divide-y text-sm">
                    @forelse ($stats['recent_patients'] as $p)
                        <li class="py-1.5">{{ $p['name'] }}</li>
                    @empty
                        <li class="py-1.5 text-slate-400">No patients yet.</li>
                    @endforelse
                </ul>
            </div>
            @break

        @case('patient')
            <div class="mb-6 grid grid-cols-2 gap-4">
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['pending_invoices'] }}</div><div class="text-sm text-slate-500">Pending invoices</div></div>
                <div class="rounded bg-white p-4 shadow"><div class="text-2xl font-bold">{{ $stats['unread_notifications'] }}</div><div class="text-sm text-slate-500">Unread notifications</div></div>
            </div>
            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded bg-white p-4 shadow">
                    <h2 class="mb-2 font-semibold">Upcoming appointment</h2>
                    @if ($stats['upcoming_appointment'])
                        <p class="text-sm">{{ $stats['upcoming_appointment']->appointment_date->format('M j, Y') }} at {{ $stats['upcoming_appointment']->appointment_time }} ({{ $stats['upcoming_appointment']->status }})</p>
                    @else
                        <p class="text-sm text-slate-400">No upcoming appointments.</p>
                    @endif
                    <h2 class="mb-2 mt-4 font-semibold">Recent appointments</h2>
                    <ul class="divide-y text-sm">
                        @forelse ($stats['recent_appointments'] as $a)
                            <li class="py-1.5">{{ $a['doctor'] }} — {{ $a['date'] }} ({{ $a['status'] }})</li>
                        @empty
                            <li class="py-1.5 text-slate-400">No appointments yet.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="rounded bg-white p-4 shadow">
                    <h2 class="mb-2 font-semibold">Recent prescriptions ({{ count($stats['recent_prescriptions']) }})</h2>
                    <h2 class="mb-2 mt-4 font-semibold">Recent medical records</h2>
                    <ul class="divide-y text-sm">
                        @forelse ($stats['recent_medical_records'] as $r)
                            <li class="py-1.5">{{ $r['diagnosis'] ?? 'No diagnosis' }} — {{ \Carbon\Carbon::parse($r['created_at'])->format('M j, Y') }}</li>
                        @empty
                            <li class="py-1.5 text-slate-400">No records yet.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            @break
    @endswitch
@endsection
