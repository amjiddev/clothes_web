<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'discount_price',
        'sku',
        'stock_quantity',
        'color',
        'material',
        'size',
        'available_sizes',
        'available_colors',
        'fabric_type',
        'image',
        'gallery',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean',
        'gallery' => 'array',
        'available_sizes' => 'array',
        'available_colors' => 'array',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return asset('images/placeholder.png');
    }

    public function getGalleryUrlsAttribute()
    {
        if ($this->gallery) {
            return array_map(fn($img) => asset('storage/' . $img), $this->gallery);
        }
        return [];
    }

    // Methods
    public function isInStock()
    {
        return $this->stock_quantity > 0 && $this->is_active;
    }

    public function hasDiscount()
    {
        return $this->discount_price && $this->discount_price < $this->price;
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->hasDiscount()) {
            return round((($this->price - $this->discount_price) / $this->price) * 100);
        }
        return 0;
    }

    public function getFinalPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock_quantity <= 0) {
            return 'Out of Stock';
        } elseif ($this->stock_quantity <= 10) {
            return 'Low Stock';
        }
        return 'In Stock';
    }

    public function getStockStatusBadgeAttribute()
    {
        if ($this->stock_quantity <= 0) {
            return 'danger';
        } elseif ($this->stock_quantity <= 10) {
            return 'warning';
        }
        return 'success';
    }
}
