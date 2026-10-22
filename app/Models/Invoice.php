<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'amount_paid',
        'balance_due',
        'status',
        'notes',
        'paid_at',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'amount_paid' => 'decimal:2',
        'balance_due' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function customer()
    {
        return $this->hasOneThrough(
            User::class,
            Order::class,
            'id',           // Foreign key on orders table
            'id',           // Foreign key on users table
            'order_id',     // Local key on invoices table
            'user_id'       // Local key on orders table
        );
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Generate invoice number
     */
    public function generateInvoiceNumber()
    {
        $prefix = 'INV-' . date('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;
        return $prefix . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->invoice_number) {
                $model->invoice_number = $model->generateInvoiceNumber();
            }
            if (!$model->invoice_date) {
                $model->invoice_date = now();
            }
            // Calculate balance due
            $model->balance_due = $model->total_amount - $model->amount_paid;
        });

        static::updating(function ($model) {
            // Recalculate balance due
            $model->balance_due = $model->total_amount - $model->amount_paid;
            
            // Update status based on payment
            if ($model->amount_paid >= $model->total_amount) {
                $model->status = 'paid';
                if (!$model->paid_at) {
                    $model->paid_at = now();
                }
            } elseif ($model->amount_paid > 0) {
                $model->status = 'partial';
            } else {
                $model->status = 'unpaid';
            }
        });
    }

    /**
     * Record a payment
     */
    public function recordPayment($amount, $method = 'cash', $notes = null, $receivedBy = null)
    {
        $payment = Payment::create([
            'invoice_id' => $this->id,
            'order_id' => $this->order_id,
            'payment_reference' => 'PAY-' . date('YmdHis') . '-' . $this->id,
            'amount' => $amount,
            'payment_method' => $method,
            'payment_date' => now(),
            'notes' => $notes,
            'received_by' => $receivedBy ?? auth()->id(),
        ]);

        // Update amount paid
        $this->amount_paid += $amount;
        $this->save();

        // Update order payment status
        $this->order->payment_status = $this->status;
        $this->order->save();

        return $payment;
    }

    /**
     * Check if invoice is fully paid
     */
    public function isPaid()
    {
        return $this->status === 'paid';
    }

    /**
     * Check if invoice is partially paid
     */
    public function isPartiallyPaid()
    {
        return $this->status === 'partial';
    }

    /**
     * Check if invoice is unpaid
     */
    public function isUnpaid()
    {
        return $this->status === 'unpaid';
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeAttribute()
    {
        $badges = [
            'unpaid' => 'danger',
            'partial' => 'warning',
            'paid' => 'success',
            'cancelled' => 'secondary',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Get status text
     */
    public function getStatusTextAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Get formatted total
     */
    public function getFormattedTotalAttribute()
    {
        return 'Rs. ' . number_format($this->total_amount, 2);
    }

    /**
     * Get formatted paid amount
     */
    public function getFormattedPaidAttribute()
    {
        return 'Rs. ' . number_format($this->amount_paid, 2);
    }

    /**
     * Get formatted balance
     */
    public function getFormattedBalanceAttribute()
    {
        return 'Rs. ' . number_format($this->balance_due, 2);
    }
}
