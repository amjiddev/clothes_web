<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/placeholder-category.png');
    }

    // Methods
    public function getProductsCount()
    {
        return $this->products()->count();
    }

    public function getActiveProductsCount()
    {
        return $this->products()->where('is_active', true)->count();
    }

    public function getInactiveProductsCount()
    {
        return $this->products()->where('is_active', false)->count();
    }

    public function getAveragePriceAttribute()
    {
        return $this->products()->avg('price');
    }

    public function getTotalStockAttribute()
    {
        return $this->products()->sum('stock_quantity');
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active ? 'success' : 'danger';
    }

    public function getStatusTextAttribute()
    {
        return $this->is_active ? 'Active' : 'Inactive';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }
}
