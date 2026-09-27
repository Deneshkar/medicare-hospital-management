@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-xl font-semibold">Invoices</h1>
        @can('create', App\Models\Invoice::class)
            <a href="{{ route('web.invoices.create') }}" class="rounded bg-slate-800 px-4 py-2 text-sm text-white hover:bg-slate-700">New invoice</a>
        @endcan
    </div>
    <div class="overflow-hidden rounded bg-white shadow">
        <table class="w-full text-left text-sm">
            <thead class="border-b bg-slate-50">
                <tr>
                    <th class="px-4 py-2">Number</th>
                    <th class="px-4 py-2">Patient</th>
                    <th class="px-4 py-2">Total</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($invoices as $invoice)
                    <tr>
                        <td class="px-4 py-2 font-medium">{{ $invoice->invoice_number }}</td>
                        <td class="px-4 py-2">{{ $invoice->patient->user->name }}</td>
                        <td class="px-4 py-2">{{ number_format($invoice->total, 2) }}</td>
                        <td class="px-4 py-2"><span class="rounded bg-slate-100 px-2 py-0.5 text-xs">{{ $invoice->payment_status }}</span></td>
                        <td class="px-4 py-2 text-right">
                            <a href="{{ route('web.invoices.show', $invoice) }}" class="text-slate-700 underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="px-4 py-4 text-center text-slate-400">No invoices yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $invoices->links() }}</div>
@endsection
