<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductDisplaySection extends Model
{
    protected $fillable = [
        'product_id',
        'section',
        'display_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];

    // Available sections
    const SECTIONS = [
        'home_featured' => 'Home Page - Featured Products',
        'shop_page' => 'Shop Page',
        'new_in' => 'New In Page',
        'summer_sale' => 'Summer Sale Page',
        'collections' => 'Collections Page',
        'best_sellers' => 'Best Sellers Page',
        'summer_2026' => 'Summer 2026 Collection Page',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getSectionNameAttribute()
    {
        return self::SECTIONS[$this->section] ?? $this->section;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySection($query, $section)
    {
        return $query->where('section', $section);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }
}
