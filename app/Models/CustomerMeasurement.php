<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerMeasurement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'chest',
        'waist',
        'hips',
        'shoulder',
        'sleeve_length',
        'torso_length',
        'inseam',
        'neck',
        'notes',
        'is_default',
    ];

    protected $casts = [
        'chest' => 'decimal:2',
        'waist' => 'decimal:2',
        'hips' => 'decimal:2',
        'shoulder' => 'decimal:2',
        'sleeve_length' => 'decimal:2',
        'torso_length' => 'decimal:2',
        'inseam' => 'decimal:2',
        'neck' => 'decimal:2',
        'is_default' => 'boolean',
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
}
