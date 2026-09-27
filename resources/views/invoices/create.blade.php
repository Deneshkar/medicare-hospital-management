@extends('layouts.app')

@section('title', 'New invoice')

@section('content')
    <div class="mx-auto max-w-xl rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-xl font-semibold">New invoice</h1>
        <form method="POST" action="{{ route('web.invoices.store') }}" class="space-y-4">
            @csrf
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
                <div class="mb-2 flex items-center justify-between">
                    <span class="text-sm font-medium">Line items</span>
                    <button type="button" onclick="addInvoiceRow()" class="rounded border px-3 py-1 text-sm hover:bg-slate-50">+ Add item</button>
                </div>
                <div id="items" class="space-y-3">
                    <div class="item-row grid grid-cols-3 gap-2 rounded border p-3">
                        <input name="items[0][description]" placeholder="Description" required class="col-span-3 rounded border border-slate-300 px-2 py-1.5 text-sm">
                        <input name="items[0][quantity]" type="number" min="1" value="1" placeholder="Qty" required class="rounded border border-slate-300 px-2 py-1.5 text-sm">
                        <input name="items[0][unit_price]" type="number" step="0.01" min="0" placeholder="Unit price" required class="col-span-2 rounded border border-slate-300 px-2 py-1.5 text-sm">
                    </div>
                </div>
                <p class="mt-1 text-xs text-slate-500">Line totals are calculated server-side.</p>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-white hover:bg-slate-700">Save</button>
                <a href="{{ route('web.invoices.index') }}" class="rounded border px-4 py-2 hover:bg-slate-50">Cancel</a>
            </div>
        </form>
    </div>
    <script>
        let invoiceIndex = 1;
        function addInvoiceRow() {
            const row = document.querySelector('.item-row').cloneNode(true);
            row.querySelectorAll('input').forEach(input => {
                input.name = input.name.replace(/items\[\d+\]/, `items[${invoiceIndex}]`);
                if (input.name.includes('[quantity]')) input.value = 1;
                else if (!input.name.includes('[unit_price]')) input.value = '';
                else input.value = '';
            });
            document.getElementById('items').appendChild(row);
            invoiceIndex++;
        }
    </script>
@endsection
