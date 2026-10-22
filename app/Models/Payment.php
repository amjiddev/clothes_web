<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'order_id',
        'payment_reference',
        'amount',
        'payment_method',
        'payment_date',
        'notes',
        'transaction_id',
        'received_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    /**
     * Relationships
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function receivedByUser()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rs. ' . number_format($this->amount, 2);
    }

    /**
     * Get payment method text
     */
    public function getPaymentMethodTextAttribute()
    {
        $methods = [
            'cash' => 'Cash',
            'card' => 'Card',
            'bank_transfer' => 'Bank Transfer',
            'mobile_money' => 'Mobile Money',
            'other' => 'Other',
        ];
        return $methods[$this->payment_method] ?? 'Unknown';
    }
}
