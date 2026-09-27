@extends('layouts.app')

@section('title', 'Departments')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Departments</h1>
        <a href="{{ route('web.departments.create') }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">New department</a>
    </div>
    <div class="overflow-hidden rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-slate-50">
                <tr>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Doctors</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($departments as $department)
                    <tr>
                        <td class="px-4 py-2 font-medium">{{ $department->name }}</td>
                        <td class="px-4 py-2 text-slate-600">{{ Str::limit($department->description, 60) }}</td>
                        <td class="px-4 py-2">{{ $department->doctors_count }}</td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('web.departments.show', $department) }}" class="text-slate-700 underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="px-4 py-4 text-center text-slate-400">No departments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $departments->links() }}</div>
@endsection
