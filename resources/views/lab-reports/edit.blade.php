@extends('layouts.app')

@section('title', 'Update lab report')

@section('content')
    <div class="mx-auto max-w-lg rounded bg-white p-6 shadow">
        <h1 class="mb-1 text-xl font-semibold">{{ $labReport->test_name }}</h1>
        <p class="mb-4 text-sm text-slate-500">Patient: {{ $labReport->patient->user->name }}</p>
        <form method="POST" action="{{ route('web.lab-reports.update', $labReport) }}" enctype="multipart/form-data" class="space-y-4">
            @csrf
            <div>
                <label for="status" class="mb-1 block text-sm font-medium">Status</label>
                <select id="status" name="status"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                    @foreach (['requested', 'processing', 'completed', 'cancelled'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $labReport->status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="result" class="mb-1 block text-sm font-medium">Result</label>
                <textarea id="result" name="result" rows="3"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">{{ old('result', $labReport->result) }}</textarea>
            </div>
            <div>
                <label for="doctor_notes" class="mb-1 block text-sm font-medium">Doctor notes</label>
                <textarea id="doctor_notes" name="doctor_notes" rows="2"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">{{ old('doctor_notes', $labReport->doctor_notes) }}</textarea>
            </div>
            <div>
                <label for="report_file" class="mb-1 block text-sm font-medium">Report file (PDF/JPG/PNG, max 10 MB)</label>
                <input id="report_file" name="report_file" type="file" accept=".pdf,.jpg,.jpeg,.png"
                    class="w-full rounded border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none">
                @if ($labReport->report_file)
                    <p class="mt-1 text-xs text-slate-500">A file is already attached — uploading replaces it.</p>
                @endif
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Save</button>
                <a href="{{ route('web.lab-reports.show', $labReport) }}" class="rounded border px-4 py-2 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
@endsection
