<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StitchingOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'measurement_id',
        'tailor_id',
        'garment_type',
        'service_option',
        'fabric_details',
        'design_image',
        'stitching_status',
        'special_instructions',
        'additional_instructions',
        'estimated_cost',
        'assigned_date',
        'start_date',
        'fitting_date',
        'completion_date',
        'service_request_date',
        'tailor_notes',
    ];

    protected $casts = [
        'assigned_date' => 'datetime',
        'start_date' => 'datetime',
        'fitting_date' => 'datetime',
        'completion_date' => 'datetime',
        'service_request_date' => 'datetime',
        'estimated_cost' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function measurement()
    {
        return $this->belongsTo(CustomerMeasurement::class, 'measurement_id');
    }

    public function tailor()
    {
        return $this->belongsTo(User::class, 'tailor_id');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'secondary',
            'assigned' => 'info',
            'in_progress' => 'primary',
            'ready_for_fitting' => 'warning',
            'in_fitting' => 'info',
            'ready' => 'success',
            'completed' => 'success',
            'cancelled' => 'danger',
        ];
        return $badges[$this->stitching_status] ?? 'secondary';
    }

    public function canAssignToTailor()
    {
        return $this->stitching_status === 'pending';
    }

    public function canStartStitching()
    {
        return $this->stitching_status === 'assigned' && $this->tailor_id;
    }

    public function canMarkReady()
    {
        return in_array($this->stitching_status, ['in_progress', 'ready_for_fitting', 'in_fitting']);
    }

    public function getStatusTextAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->stitching_status));
    }

    public function getServiceTypeAttribute()
    {
        if ($this->service_option) {
            return $this->service_option;
        }
        return $this->garment_type ?? 'Custom Stitching';
    }

    public function getProgressPercentageAttribute()
    {
        $statuses = [
            'pending' => 0,
            'assigned' => 20,
            'in_progress' => 50,
            'ready_for_fitting' => 75,
            'in_fitting' => 85,
            'ready' => 95,
            'completed' => 100,
            'cancelled' => 0,
        ];
        return $statuses[$this->stitching_status] ?? 0;
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->completion_date) {
            return $this->completion_date->diffInDays(now());
        }
        // Estimate 7 days from start date
        if ($this->start_date) {
            $estimated = $this->start_date->addDays(7);
            return $estimated->diffInDays(now());
        }
        return null;
    }

    public function customer()
    {
        return $this->order->user;
    }

    public function getDesignImageUrlAttribute()
    {
        if ($this->design_image) {
            return asset('storage/' . $this->design_image);
        }
        return null;
    }

    public function isCompleted()
    {
        return $this->stitching_status === 'completed';
    }

    public function isDelivered()
    {
        return $this->order->status === 'delivered';
    }

    public function isPending()
    {
        return $this->stitching_status === 'pending';
    }

    public function getTimeline()
    {
        $timeline = [];
        
        $timeline[] = [
            'date' => $this->created_at,
            'event' => 'Stitching Order Created',
            'status' => 'created',
            'icon' => 'fa-plus-circle',
        ];

        if ($this->assigned_date && $this->stitching_status !== 'pending') {
            $timeline[] = [
                'date' => $this->assigned_date,
                'event' => 'Assigned to Tailor: ' . ($this->tailor->name ?? 'Unknown'),
                'status' => 'assigned',
                'icon' => 'fa-user-check',
            ];
        }

        if ($this->start_date && in_array($this->stitching_status, ['in_progress', 'ready_for_fitting', 'in_fitting', 'ready', 'completed'])) {
            $timeline[] = [
                'date' => $this->start_date,
                'event' => 'Stitching Started',
                'status' => 'started',
                'icon' => 'fa-play-circle',
            ];
        }

        if ($this->fitting_date && in_array($this->stitching_status, ['ready_for_fitting', 'in_fitting', 'ready', 'completed'])) {
            $timeline[] = [
                'date' => $this->fitting_date,
                'event' => 'Fitting Scheduled',
                'status' => 'fitting',
                'icon' => 'fa-ruler',
            ];
        }

        if ($this->completion_date && in_array($this->stitching_status, ['ready', 'completed'])) {
            $timeline[] = [
                'date' => $this->completion_date,
                'event' => 'Stitching Completed',
                'status' => 'completed',
                'icon' => 'fa-check-circle',
            ];
        }

        if ($this->stitching_status === 'cancelled') {
            $timeline[] = [
                'date' => $this->updated_at,
                'event' => 'Order Cancelled',
                'status' => 'cancelled',
                'icon' => 'fa-times-circle',
            ];
        }

        return $timeline;
    }
}
