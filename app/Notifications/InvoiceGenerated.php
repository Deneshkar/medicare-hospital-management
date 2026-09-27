<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class InvoiceGenerated extends Notification
{
    use Queueable;

    public function __construct(public Invoice $invoice) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'invoice_id' => $this->invoice->id,
            'message' => "New invoice {$this->invoice->invoice_number} for {$this->invoice->total}",
        ];
    }
}
