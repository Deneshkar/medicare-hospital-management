<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'patient_id',
        'appointment_id',
        'total',
        'payment_status',
        'payment_method',
        'invoice_date',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            $invoice->invoice_number = 'INV-'.now()->format('Y').'-'.str_pad(
                (static::whereYear('created_at', now()->year)->count() + 1),
                5,
                '0',
                STR_PAD_LEFT
            );
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function recalculateTotal(): void
    {
        $this->total = $this->items()->sum('line_total');
        $this->save();
    }
}
