@extends('layouts.app')

@section('title', 'New doctor')

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-xl font-semibold">New doctor</h1>
        <form method="POST" action="{{ route('web.doctors.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="mb-1 block text-sm font-medium">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div>
                <label for="email" class="mb-1 block text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div>
                <label for="phone" class="mb-1 block text-sm font-medium">Phone (optional)</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone') }}"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div>
                <label for="department_id" class="mb-1 block text-sm font-medium">Department</label>
                <select id="department_id" name="department_id" required
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                    <option value="">Select…</option>
                    @foreach ($departments as $department)
                        <option value="{{ $department->id }}" @selected(old('department_id') == $department->id)>{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="specialization" class="mb-1 block text-sm font-medium">Specialization</label>
                <input id="specialization" name="specialization" type="text" value="{{ old('specialization') }}" required
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div>
                <label for="license_number" class="mb-1 block text-sm font-medium">License number</label>
                <input id="license_number" name="license_number" type="text" value="{{ old('license_number') }}" required
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div>
                <label for="experience_years" class="mb-1 block text-sm font-medium">Experience (years)</label>
                <input id="experience_years" name="experience_years" type="number" min="0" value="{{ old('experience_years', 0) }}"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Save</button>
                <a href="{{ route('web.doctors.index') }}" class="rounded border px-4 py-2 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
