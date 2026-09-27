<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — MediCare</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">
    <header class="bg-slate-800 text-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-4 py-3">
            <a href="{{ auth()->check() ? route('dashboard') : route('login') }}" class="text-lg font-semibold tracking-tight">
                MediCare
            </a>
            @auth
                <div class="flex items-center gap-4 text-sm">
                    <span class="text-slate-300">{{ auth()->user()->name }}</span>
                    <span class="rounded bg-slate-600 px-2 py-0.5 text-xs uppercase">{{ auth()->user()->role }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded bg-slate-600 px-3 py-1 hover:bg-slate-500">Logout</button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    @auth
        <nav class="border-b bg-white">
            <div class="mx-auto flex max-w-6xl gap-1 overflow-x-auto px-4 py-2 text-sm">
                <a href="{{ route('dashboard') }}" class="rounded px-3 py-1.5 hover:bg-slate-100">Dashboard</a>
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('web.departments.index') }}" class="rounded px-3 py-1.5 hover:bg-slate-100">Departments</a>
                    <a href="{{ route('web.doctors.index') }}" class="rounded px-3 py-1.5 hover:bg-slate-100">Doctors</a>
                @endif
                @if (in_array(auth()->user()->role, ['admin', 'receptionist', 'doctor', 'nurse']))
                    <a href="{{ route('web.patients.index') }}" class="rounded px-3 py-1.5 hover:bg-slate-100">Patients</a>
                @endif
                <a href="{{ route('web.appointments.index') }}" class="rounded px-3 py-1.5 hover:bg-slate-100">Appointments</a>
                <a href="{{ route('web.medical-records.index') }}" class="rounded px-3 py-1.5 hover:bg-slate-100">Records</a>
                <a href="{{ route('web.vital-signs.index') }}" class="rounded px-3 py-1.5 hover:bg-slate-100">Vitals</a>
                <a href="{{ route('web.prescriptions.index') }}" class="rounded px-3 py-1.5 hover:bg-slate-100">Prescriptions</a>
                <a href="{{ route('web.lab-reports.index') }}" class="rounded px-3 py-1.5 hover:bg-slate-100">Labs</a>
                @if (in_array(auth()->user()->role, ['admin', 'receptionist', 'patient']))
                    <a href="{{ route('web.invoices.index') }}" class="rounded px-3 py-1.5 hover:bg-slate-100">Invoices</a>
                @endif
                <a href="{{ route('web.notifications.index') }}" class="rounded px-3 py-1.5 hover:bg-slate-100">Notifications</a>
                @if (auth()->user()->role === 'patient')
                    <a href="{{ route('web.ai.index') }}" class="rounded px-3 py-1.5 hover:bg-slate-100">AI Chat</a>
                @endif
            </div>
        </nav>
    @endauth

    <main class="mx-auto max-w-6xl px-4 py-6">
        @if (session('success'))
            <div class="mb-4 rounded border border-green-300 bg-green-50 px-4 py-2 text-green-800">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="mb-4 rounded border border-red-300 bg-red-50 px-4 py-2 text-red-800">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="mb-4 rounded border border-red-300 bg-red-50 px-4 py-2 text-red-800">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
