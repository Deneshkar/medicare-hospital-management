@extends('layouts.app')

@section('title', 'New patient')

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-xl font-semibold">New patient</h1>
        <form method="POST" action="{{ route('web.patients.store') }}" class="space-y-4">
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
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="date_of_birth" class="mb-1 block text-sm font-medium">Date of birth</label>
                    <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
                <div>
                    <label for="gender" class="mb-1 block text-sm font-medium">Gender</label>
                    <select id="gender" name="gender"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                        <option value="">Select…</option>
                        @foreach (['male', 'female', 'other'] as $gender)
                            <option value="{{ $gender }}" @selected(old('gender') === $gender)>{{ ucfirst($gender) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="address" class="mb-1 block text-sm font-medium">Address</label>
                <input id="address" name="address" type="text" value="{{ old('address') }}"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div>
                <label for="blood_group" class="mb-1 block text-sm font-medium">Blood group</label>
                <input id="blood_group" name="blood_group" type="text" value="{{ old('blood_group') }}"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="emergency_contact_name" class="mb-1 block text-sm font-medium">Emergency contact</label>
                    <input id="emergency_contact_name" name="emergency_contact_name" type="text" value="{{ old('emergency_contact_name') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
                <div>
                    <label for="emergency_contact_phone" class="mb-1 block text-sm font-medium">Emergency phone</label>
                    <input id="emergency_contact_phone" name="emergency_contact_phone" type="text" value="{{ old('emergency_contact_phone') }}"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Save</button>
                <a href="{{ route('web.patients.index') }}" class="rounded border px-4 py-2 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
