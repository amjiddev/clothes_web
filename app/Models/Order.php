<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'type',
        'status',
        'subtotal',
        'stitching_charge',
        'tax',
        'discount',
        'total',
        'payment_status',
        'payment_method',
        'notes',
        'delivery_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'stitching_charge' => 'decimal:2',
        'tax' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'delivery_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function stitchingOrder()
    {
        return $this->hasOne(StitchingOrder::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function generateOrderNumber()
    {
        $prefix = 'ORD-' . date('Ymd');
        $count = static::whereDate('created_at', today())->count() + 1;
        return $prefix . str_pad($count, 5, '0', STR_PAD_LEFT);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->order_number) {
                $model->order_number = $model->generateOrderNumber();
            }
        });
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'confirmed' => 'info',
            'in_progress' => 'primary',
            'ready' => 'success',
            'delivered' => 'success',
            'cancelled' => 'danger',
        ];
        return $badges[$this->status] ?? 'secondary';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'warning',
            'paid' => 'success',
            'failed' => 'danger',
        ];
        return $badges[$this->payment_status] ?? 'secondary';
    }

    public function getOrderTypeAttribute()
    {
        $types = [
            'ready_made' => 'Cloth Only',
            'stitching' => 'Stitching Only',
            'combined' => 'Cloth + Stitching',
        ];
        return $types[$this->type] ?? ucfirst($this->type);
    }

    public function getStatusTextAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getPaymentStatusTextAttribute()
    {
        return ucfirst($this->payment_status);
    }

    public function getTotalProductsAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    public function isDelivered()
    {
        return $this->status === 'delivered';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function canBeCancelled()
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    public function getTimeline()
    {
        $timeline = [];
        
        // Order created
        $timeline[] = [
            'date' => $this->created_at,
            'event' => 'Order Created',
            'status' => 'created',
        ];

        // Status transitions
        $statusEvents = [
            'confirmed' => 'Order Confirmed',
            'in_progress' => 'Order Processing Started',
            'ready' => 'Order Ready',
            'delivered' => 'Order Delivered',
        ];

        foreach ($statusEvents as $status => $event) {
            if ($this->status === $status || 
                ($this->status === 'delivered' && in_array($status, ['confirmed', 'in_progress', 'ready']))) {
                $timeline[] = [
                    'date' => $this->updated_at,
                    'event' => $event,
                    'status' => $status,
                ];
            }
        }

        return $timeline;
    }
}
