@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Notifications ({{ $unreadCount }} unread)</h1>
        <form method="POST" action="{{ route('web.notifications.read-all') }}">
            @csrf
            @method('PATCH')
            <button type="submit" class="rounded border px-4 py-1.5 text-sm hover:bg-slate-50">Mark all as read</button>
        </form>
    </div>
    <div class="space-y-2">
        @forelse ($notifications as $notification)
            <div class="flex items-center justify-between gap-4 rounded bg-white p-4 shadow {{ $notification->read_at ? 'opacity-70' : '' }}">
                <div>
                    <div class="text-xs uppercase text-slate-400">{{ \Illuminate\Support\Str::headline(class_basename($notification->type)) }}</div>
                    <p class="text-sm">{{ $notification->data['message'] ?? '—' }}</p>
                    <div class="text-xs text-slate-400">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
                @if (! $notification->read_at)
                    <form method="POST" action="{{ route('web.notifications.read', $notification->id) }}">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="rounded border px-3 py-1 text-sm hover:bg-slate-50">Mark read</button>
                    </form>
                @endif
            </div>
        @empty
            <p class="rounded bg-white p-6 text-center text-sm text-slate-400 shadow">No notifications.</p>
        @endforelse
    </div>
    <div class="mt-4">{{ $notifications->links() }}</div>
@endsection
