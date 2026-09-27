@extends('layouts.app')

@section('title', 'New prescription')

@section('content')
    <div class="mx-auto max-w-xl rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-xl font-semibold">New prescription</h1>
        <form method="POST" action="{{ route('web.prescriptions.store') }}" class="space-y-4">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="patient_id" class="mb-1 block text-sm font-medium">Patient</label>
                    <select id="patient_id" name="patient_id" required
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                        <option value="">Select…</option>
                        @foreach ($patients as $patient)
                            <option value="{{ $patient->id }}" @selected(old('patient_id') == $patient->id)>{{ $patient->user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="medical_record_id" class="mb-1 block text-sm font-medium">Medical record (optional)</label>
                    <select id="medical_record_id" name="medical_record_id"
                        class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">
                        <option value="">None</option>
                        @foreach ($medicalRecords as $record)
                            <option value="{{ $record->id }}" @selected(old('medical_record_id') == $record->id)>#{{ $record->id }} — {{ $record->patient->user->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label for="general_instructions" class="mb-1 block text-sm font-medium">General instructions</label>
                <textarea id="general_instructions" name="general_instructions" rows="2"
                    class="w-full rounded border border-slate-300 px-3 py-2 focus:border-slate-500 focus:outline-none">{{ old('general_instructions') }}</textarea>
            </div>
            <div>
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm font-medium">Medicines</span>
                    <button type="button" onclick="addItemRow()" class="rounded border px-3 py-1 text-sm hover:bg-slate-50">+ Add medicine</button>
                </div>
                <div id="items" class="space-y-3">
                    <div class="item-row grid grid-cols-2 gap-2 rounded border p-3">
                        <input name="items[0][medicine_name]" placeholder="Medicine name" required class="rounded border border-slate-300 px-2 py-1.5 text-sm">
                        <input name="items[0][dosage]" placeholder="Dosage (e.g. 500mg)" required class="rounded border border-slate-300 px-2 py-1.5 text-sm">
                        <input name="items[0][frequency]" placeholder="Frequency (e.g. 3x/day)" required class="rounded border border-slate-300 px-2 py-1.5 text-sm">
                        <input name="items[0][duration]" placeholder="Duration (e.g. 3 days)" required class="rounded border border-slate-300 px-2 py-1.5 text-sm">
                        <input name="items[0][instructions]" placeholder="Notes (optional)" class="col-span-2 rounded border border-slate-300 px-2 py-1.5 text-sm">
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Save</button>
                <a href="{{ route('web.prescriptions.index') }}" class="rounded border px-4 py-2 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
    <script>
        let itemIndex = 1;
        function addItemRow() {
            const row = document.querySelector('.item-row').cloneNode(true);
            row.querySelectorAll('input').forEach(input => {
                input.name = input.name.replace(/items\[\d+\]/, `items[${itemIndex}]`);
                input.value = '';
                input.required = input.placeholder !== 'Notes (optional)';
            });
            document.getElementById('items').appendChild(row);
            itemIndex++;
        }
    </script>
@endsection
