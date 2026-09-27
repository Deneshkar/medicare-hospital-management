@extends('layouts.app')

@section('title', $department->name)

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="text-xl font-semibold">{{ $department->name }}</h1>
        <p class="mt-2 text-slate-600">{{ $department->description ?? 'No description.' }}</p>
        <p class="mt-2 text-sm text-slate-500">{{ $department->doctors_count }} doctor(s)</p>
        <div class="mt-4 flex gap-2">
            <a href="{{ route('web.departments.edit', $department) }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">Edit</a>
            <form method="POST" action="{{ route('web.departments.destroy', $department) }}" onsubmit="return confirm('Delete this department?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded border border-red-300 px-4 py-2 text-sm text-red-700 hover:bg-red-50">Delete</button>
            </form>
            <a href="{{ route('web.departments.index') }}" class="rounded border px-4 py-2 text-sm hover:bg-slate-50">Back</a>
        </div>
    </div>
@endsection
