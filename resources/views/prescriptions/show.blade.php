@extends('layouts.app')

@section('title', 'Prescription')

@section('content')
    <div class="mx-auto max-w-xl rounded bg-white p-6 shadow">
        <h1 class="text-xl font-semibold">Prescription #{{ $prescription->id }}</h1>
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Patient</dt><dd>{{ $prescription->patient->user->name }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Doctor</dt><dd>{{ $prescription->doctor->user->name }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Instructions</dt><dd>{{ $prescription->general_instructions ?? '—' }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Date</dt><dd>{{ $prescription->created_at->format('M j, Y') }}</dd></div>
        </dl>
        <h2 class="mb-2 mt-4 font-semibold">Medicines</h2>
        <div class="overflow-hidden rounded border">
            <table class="w-full text-left text-sm">
                <thead class="border-b bg-slate-50">
                    <tr><th class="px-3 py-1.5">Medicine</th><th class="px-3 py-1.5">Dosage</th><th class="px-3 py-1.5">Frequency</th><th class="px-3 py-1.5">Duration</th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($prescription->items as $item)
                        <tr>
                            <td class="px-3 py-1.5 font-medium">{{ $item->medicine_name }}</td>
                            <td class="px-3 py-1.5">{{ $item->dosage }}</td>
                            <td class="px-3 py-1.5">{{ $item->frequency }}</td>
                            <td class="px-3 py-1.5">{{ $item->duration }}</td>
                        </tr>
                        @if ($item->instructions)
                            <tr><td colspan="4" class="px-3 py-1 text-xs text-slate-500">Notes: {{ $item->instructions }}</td></tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4 flex gap-2">
            <a href="{{ route('web.prescriptions.pdf', $prescription) }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">Download PDF</a>
            <a href="{{ route('web.prescriptions.index') }}" class="rounded border px-4 py-2 text-sm hover:bg-slate-50">Back</a>
        </div>
    </div>
@endsection
