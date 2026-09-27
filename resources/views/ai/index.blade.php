@extends('layouts.app')

@section('title', 'AI Assistant')

@section('content')
    <div class="mx-auto max-w-2xl">
        <h1 class="mb-4 text-xl font-semibold">AI Patient Assistant</h1>
        <div class="mb-4 max-h-[50vh] space-y-3 overflow-y-auto rounded bg-white p-4 shadow">
            @forelse ($history as $message)
                <div class="{{ $message->role === 'user' ? 'ml-8 bg-slate-800 text-white' : 'mr-8 bg-slate-100' }} rounded p-3 text-sm">
                    <div class="mb-1 text-xs uppercase opacity-60">{{ $message->role === 'user' ? 'You' : 'Assistant' }}</div>
                    <p class="whitespace-pre-line">{{ $message->message }}</p>
                </div>
            @empty
                <p class="text-center text-sm text-slate-400">No messages yet — ask something below to start.</p>
            @endforelse
        </div>
        <form method="POST" action="{{ route('web.ai.chat') }}" class="flex gap-2">
            @csrf
            <input name="message" type="text" required maxlength="1000" placeholder="Ask about appointments, services, records…" value="{{ old('message') }}"
                class="flex-1 rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Send</button>
        </form>
        <p class="mt-2 text-xs text-slate-400">General information only — never a diagnosis. For urgent symptoms, contact a doctor or emergency care.</p>
    </div>
@endsection
