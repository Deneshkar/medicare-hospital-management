<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
        .header h1 { margin: 0; color: #2c3e50; }
        .info-row { margin-bottom: 6px; }
        .label { font-weight: bold; display: inline-block; width: 140px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .total-row td { font-weight: bold; border-top: 2px solid #333; }
        .status { display: inline-block; padding: 4px 10px; border-radius: 4px; font-weight: bold; }
        .status-paid { background: #d4edda; color: #155724; }
        .status-pending { background: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <div class="header">
        <h1>MediCare</h1>
        <p>Invoice {{ $invoice->invoice_number }}</p>
    </div>

    <div class="info-row"><span class="label">Patient:</span> {{ $invoice->patient->user->name }}</div>
    <div class="info-row"><span class="label">Invoice Date:</span> {{ $invoice->invoice_date->format('F j, Y') }}</div>
    <div class="info-row">
        <span class="label">Status:</span>
        <span class="status status-{{ $invoice->payment_status === 'paid' ? 'paid' : 'pending' }}">
            {{ ucwords(str_replace('_', ' ', $invoice->payment_status)) }}
        </span>
    </div>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Line Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td>{{ number_format($item->line_total, 2) }}</td>
                </tr>
            @endforeach
            <tr class="total-row">
                <td colspan="3">Total</td>
                <td>{{ number_format($invoice->total, 2) }}</td>
            </tr>
        </tbody>
    </table>
</body>
</html>
