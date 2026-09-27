@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="mx-auto max-w-md rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-xl font-semibold">Login to MediCare</h1>
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label for="email" class="mb-1 block text-sm font-medium">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div>
                <label for="password" class="mb-1 block text-sm font-medium">Password</label>
                <input id="password" name="password" type="password" required
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <button type="submit" class="w-full rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Login</button>
        </form>
        <p class="mt-4 text-center text-sm text-slate-600">
            No account? <a href="{{ route('register') }}" class="text-slate-800 underline">Register</a>
        </p>
    </div>
@endsection
