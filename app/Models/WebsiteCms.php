<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class WebsiteCms extends Model
{
    use HasFactory;

    protected $table = 'website_cms';

    protected $fillable = [
        'section_type',
        'page_slug',
        'page_title',
        'page_content',
        'meta_description',
        'meta_keywords',
        'featured_image',
        'gallery_images',
        'data',
        'is_published',
        'published_at',
        'created_by',
        'display_order',
    ];

    protected $casts = [
        'gallery_images' => AsCollection::class,
        'data' => 'array',
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the user who created this CMS entry
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get featured image URL
     */
    public function getFeaturedImageUrlAttribute()
    {
        if ($this->featured_image) {
            return asset('storage/' . $this->featured_image);
        }
        return null;
    }

    /**
     * Get gallery image URLs
     */
    public function getGalleryUrlsAttribute()
    {
        if ($this->gallery_images && $this->gallery_images->count() > 0) {
            return $this->gallery_images->map(function ($image) {
                return asset('storage/' . $image);
            })->toArray();
        }
        return [];
    }

    /**
     * Check if this section is published
     */
    public function isPublished()
    {
        return $this->is_published && $this->published_at <= now();
    }

    /**
     * Get data value
     */
    public function getDataValue($key, $default = null)
    {
        if ($this->data && isset($this->data[$key])) {
            return $this->data[$key];
        }
        return $default;
    }

    /**
     * Get hero section
     */
    public static function getHeroSection()
    {
        return self::where('section_type', 'hero')
                   ->where('is_published', true)
                   ->first();
    }

    /**
     * Get homepage slider
     */
    public static function getHomepageSlider()
    {
        return self::where('section_type', 'slider')
                   ->where('is_published', true)
                   ->orderBy('display_order')
                   ->get();
    }

    /**
     * Get about section
     */
    public static function getAboutSection()
    {
        return self::where('section_type', 'about')
                   ->where('is_published', true)
                   ->first();
    }

    /**
     * Get services section
     */
    public static function getServicesSection()
    {
        return self::where('section_type', 'services')
                   ->where('is_published', true)
                   ->orderBy('display_order')
                   ->get();
    }

    /**
     * Get testimonials
     */
    public static function getTestimonials()
    {
        return self::where('section_type', 'testimonial')
                   ->where('is_published', true)
                   ->orderBy('display_order')
                   ->get();
    }

    /**
     * Get contact information
     */
    public static function getContactInfo()
    {
        return self::where('section_type', 'contact')
                   ->where('is_published', true)
                   ->first();
    }

    /**
     * Get social links
     */
    public static function getSocialLinks()
    {
        return self::where('section_type', 'social')
                   ->where('is_published', true)
                   ->orderBy('display_order')
                   ->get();
    }

    /**
     * Get footer content
     */
    public static function getFooterContent()
    {
        return self::where('section_type', 'footer')
                   ->where('is_published', true)
                   ->first();
    }
}
