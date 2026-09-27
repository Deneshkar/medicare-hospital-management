@extends('layouts.app')

@section('title', 'Doctors')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Doctors</h1>
        <a href="{{ route('web.doctors.create') }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">New doctor</a>
    </div>
    <div class="overflow-hidden rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-slate-50">
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Specialization</th>
                    <th class="px-4 py-2">Department</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($doctors as $doctor)
                    <tr>
                        <td class="px-4 py-2 font-medium">{{ $doctor->user->name }}</td>
                        <td class="px-4 py-2">{{ $doctor->specialization }}</td>
                        <td class="px-4 py-2 text-slate-600">{{ $doctor->department->name ?? '—' }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('web.doctors.show', $doctor) }}" class="text-slate-700 underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-4 text-center text-slate-400">No doctors yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $doctors->links() }}</div>
@endsection
