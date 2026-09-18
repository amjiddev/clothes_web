<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_name',
        'chest',
        'shoulder',
        'sleeve_length',
        'shirt_length',
        'neck',
        'waist',
        'trouser_length',
        'bottom',
        'thigh',
        'cuff_size',
        'design_image',
        'special_instructions',
        'notes',
        'is_default',
        'is_seen',
        'seen_at',
    ];

    protected $casts = [
        'chest' => 'decimal:2',
        'shoulder' => 'decimal:2',
        'sleeve_length' => 'decimal:2',
        'shirt_length' => 'decimal:2',
        'neck' => 'decimal:2',
        'waist' => 'decimal:2',
        'trouser_length' => 'decimal:2',
        'bottom' => 'decimal:2',
        'thigh' => 'decimal:2',
        'cuff_size' => 'decimal:2',
        'is_default' => 'boolean',
        'is_seen' => 'boolean',
        'seen_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stitchingOrders()
    {
        return $this->hasMany(StitchingOrder::class, 'measurement_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->is_default) {
                static::where('user_id', $model->user_id)->update(['is_default' => false]);
            }
        });

        static::updating(function ($model) {
            if ($model->is_default) {
                static::where('user_id', $model->user_id)->where('id', '!=', $model->id)->update(['is_default' => false]);
            }
        });
    }

    /**
     * Mark measurement as seen in notification
     */
    public function markAsSeen()
    {
        if (!$this->is_seen) {
            $this->update([
                'is_seen' => true,
                'seen_at' => now(),
            ]);
        }
        return $this;
    }

    /**
     * Mark measurement as unseen
     */
    public function markAsUnseen()
    {
        $this->update([
            'is_seen' => false,
            'seen_at' => null,
        ]);
        return $this;
    }

    /**
     * Check if measurement is seen
     */
    public function isSeen()
    {
        return $this->is_seen === true;
    }

    /**
     * Check if measurement is unseen
     */
    public function isUnseen()
    {
        return $this->is_seen === false;
    }

    /**
     * Get unread/unseen measurements count for admin
     */
    public static function getUnseenCount()
    {
        return static::where('is_seen', false)->count();
    }

    /**
     * Scope: Get unseen measurements
     */
    public function scopeUnseen($query)
    {
        return $query->where('is_seen', false);
    }

    /**
     * Scope: Get seen measurements
     */
    public function scopeSeen($query)
    {
        return $query->where('is_seen', true);
    }
}
