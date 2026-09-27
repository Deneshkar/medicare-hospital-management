@extends('layouts.app')

@section('title', 'Invoice')

@section('content')
    <div class="mx-auto max-w-xl rounded bg-white p-6 shadow">
        <h1 class="text-xl font-semibold">Invoice {{ $invoice->invoice_number }}</h1>
        <dl class="mt-4 space-y-2 text-sm">
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Patient</dt><dd>{{ $invoice->patient->user->name }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Date</dt><dd>{{ $invoice->invoice_date->format('M j, Y') }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Total</dt><dd class="font-semibold">{{ number_format($invoice->total, 2) }}</dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Status</dt><dd><span class="rounded bg-slate-100 px-2 py-0.5 text-xs">{{ $invoice->payment_status }}</span></dd></div>
            <div class="flex"><dt class="w-44 font-medium text-slate-500">Method</dt><dd>{{ $invoice->payment_method ?? '—' }}</dd></div>
        </dl>
        <h2 class="mb-2 mt-4 font-semibold">Items</h2>
        <div class="overflow-hidden rounded border">
            <table class="w-full text-left text-sm">
                <thead class="border-b bg-slate-50">
                    <tr><th class="px-3 py-1.5">Description</th><th class="px-3 py-1.5">Qty</th><th class="px-3 py-1.5">Unit</th><th class="px-3 py-1.5">Total</th></tr>
                </thead>
                <tbody class="divide-y">
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td class="px-3 py-1.5">{{ $item->description }}</td>
                            <td class="px-3 py-1.5">{{ $item->quantity }}</td>
                            <td class="px-3 py-1.5">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-3 py-1.5">{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @can('recordPayment', App\Models\Invoice::class)
            <form method="POST" action="{{ route('web.invoices.payment', $invoice) }}" class="mt-4 flex items-end gap-2 rounded border p-3">
                @csrf
                @method('PATCH')
                <div class="flex-1">
                    <label for="payment_status" class="mb-1 block text-sm font-medium">Record payment</label>
                    <select id="payment_status" name="payment_status"
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none">
                        @foreach (['paid', 'partially_paid', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected($invoice->payment_status === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <label for="payment_method" class="mb-1 block text-sm font-medium">Method</label>
                    <input id="payment_method" name="payment_method" type="text" placeholder="cash / card" value="{{ old('payment_method', $invoice->payment_method) }}" required
                        class="w-full rounded border border-slate-300 px-3 py-2 text-sm focus:border-slate-500 focus:outline-none">
                </div>
                <button type="submit" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">Save</button>
            </form>
        @endcan
        <div class="mt-4 flex gap-2">
            <a href="{{ route('web.invoices.pdf', $invoice) }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">Download PDF</a>
            <a href="{{ route('web.invoices.index') }}" class="rounded border px-4 py-2 text-sm hover:bg-slate-50">Back</a>
        </div>
    </div>
@endsection
