<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Invoice;
use App\Notifications\InvoiceGenerated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Invoice::class);

        $query = Invoice::with(['patient.user', 'items']);

        if ($request->user()->role === 'patient') {
            $query->where('patient_id', $request->user()->patient?->id);
        }

        $invoices = $query->latest('invoice_date')->paginate(10);

        return InvoiceResource::collection($invoices)
            ->additional(['success' => true]);
    }

    public function store(StoreInvoiceRequest $request)
    {
        $this->authorize('create', Invoice::class);

        $invoice = DB::transaction(function () use ($request) {
            $invoice = Invoice::create([
                'patient_id' => $request->patient_id,
                'appointment_id' => $request->appointment_id,
                'invoice_date' => now(),
            ]);

            foreach ($request->items as $item) {
                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'line_total' => $item['quantity'] * $item['unit_price'],
                ]);
            }

            $invoice->recalculateTotal();

            return $invoice;
        });

        $invoice->patient->user->notify(new InvoiceGenerated($invoice));

        return (new InvoiceResource($invoice->load(['patient.user', 'items'])))
            ->additional(['success' => true, 'message' => 'Invoice created successfully'])
            ->response()
            ->setStatusCode(201);
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        return (new InvoiceResource($invoice->load(['patient.user', 'items'])))
            ->additional(['success' => true]);
    }

    public function recordPayment(Request $request, Invoice $invoice)
    {
        $this->authorize('recordPayment', Invoice::class);

        $request->validate([
            'payment_status' => ['required', Rule::in(['paid', 'partially_paid', 'cancelled'])],
            'payment_method' => ['required', 'string', 'max:50'],
        ]);

        if ($invoice->payment_status === 'paid' && $request->payment_status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Paid invoices cannot be modified',
            ], 422);
        }

        $invoice->update([
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
        ]);

        return (new InvoiceResource($invoice->load(['patient.user', 'items'])))
            ->additional(['success' => true, 'message' => 'Payment recorded successfully']);
    }

    public function downloadPdf(Invoice $invoice)
    {
        $this->authorize('view', $invoice);

        $invoice->load(['patient.user', 'items']);

        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));

        return $pdf->download("invoice-{$invoice->invoice_number}.pdf");
    }
}
