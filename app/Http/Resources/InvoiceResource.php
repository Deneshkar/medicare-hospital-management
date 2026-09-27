<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'patient' => [
                'id' => $this->patient->id,
                'name' => $this->patient->user->name,
            ],
            'total' => $this->total,
            'payment_status' => $this->payment_status,
            'payment_method' => $this->payment_method,
            'invoice_date' => $this->invoice_date->format('Y-m-d'),
            'items' => $this->items->map(fn ($item) => [
                'id' => $item->id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'line_total' => $item->line_total,
            ]),
        ];
    }
}
