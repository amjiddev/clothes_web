<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'short_description',
        'full_description',
        'price',
        'regular_price',
        'sale_price',
        'discount_price',
        'sku',
        'stock_quantity',
        'brand',
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

    public function brandModel()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->ordered();
    }

    public function featuredImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_featured', true);
    }

    public function galleryImages()
    {
        return $this->hasMany(ProductImage::class)->where('is_featured', false)->ordered();
    }

    public function displaySections()
    {
        return $this->hasMany(ProductDisplaySection::class);
    }

    public function activeSections()
    {
        return $this->hasMany(ProductDisplaySection::class)->where('is_active', true);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        // First, try to get from featured image relationship
        if ($this->relationLoaded('featuredImage') && $this->featuredImage) {
            return asset('storage/' . $this->featuredImage->image_path);
        }
        
        // Try to load featured image if not loaded
        $featuredImage = $this->featuredImage()->first();
        if ($featuredImage) {
            return asset('storage/' . $featuredImage->image_path);
        }
        
        // Fallback to old image field
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
