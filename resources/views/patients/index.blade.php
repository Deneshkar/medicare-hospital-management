@extends('layouts.app')

@section('title', 'Patients')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Patients</h1>
        @can('create', App\Models\Patient::class)
            <a href="{{ route('web.patients.create') }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">New patient</a>
        @endcan
    </div>
    <div class="overflow-hidden rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-slate-50">
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Phone</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($patients as $patient)
                    <tr>
                        <td class="px-4 py-2 font-medium">{{ $patient->user->name }}</td>
                        <td class="px-4 py-2 text-slate-600">{{ $patient->user->email }}</td>
                        <td class="px-4 py-2 text-slate-600">{{ $patient->user->phone ?? '—' }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('web.patients.show', $patient) }}" class="text-slate-700 underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-4 text-center text-slate-400">No patients yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $patients->links() }}</div>
@endsection
