@extends('layouts.app')

@section('title', 'New department')

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-xl font-semibold">New department</h1>
        <form method="POST" action="{{ route('web.departments.store') }}" class="space-y-4">
            @csrf
            <div>
                <label for="name" class="mb-1 block text-sm font-medium">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
            </div>
            <div>
                <label for="description" class="mb-1 block text-sm font-medium">Description</label>
                <textarea id="description" name="description" rows="3"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">{{ old('description') }}</textarea>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Save</button>
                <a href="{{ route('web.departments.index') }}" class="rounded border px-4 py-2 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
