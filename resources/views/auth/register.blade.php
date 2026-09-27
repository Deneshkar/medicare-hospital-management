@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="mx-auto max-w-md rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-xl font-semibold">Create a MediCare account</h1>
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
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
                <label for="role" class="mb-1 block text-sm font-medium">Role</label>
                <select id="role" name="role" required
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                    @foreach (['admin', 'doctor', 'nurse', 'receptionist', 'patient'] as $role)
                        <option value="{{ $role }}" @selected(old('role') === $role)>{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="password" class="mb-1 block text-sm font-medium">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div>
                <label for="password_confirmation" class="mb-1 block text-sm font-medium">Confirm password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Register</button>
        </form>
        <p class="mt-4 text-center text-sm text-slate-600">
            Already registered? <a href="{{ route('login') }}" class="text-slate-800 underline">Login</a>
        </p>
    </div>
@endsection
