<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionSection extends Model
{
    protected $table = 'collection_sections';

    protected $fillable = [
        'section_name',
        'section_key',
        'badge_text',
        'badge_bg_color',
        'title',
        'description',
        'button_text',
        'button_link',
        'features',
        'is_published',
        'display_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'display_order' => 'integer',
        'features' => 'json',
    ];

    /**
     * Get the images for this section
     */
    public function images(): HasMany
    {
        return $this->hasMany(CollectionSectionImage::class, 'collection_section_id')->orderBy('display_order');
    }

    /**
     * Scope to get published sections
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /**
     * Scope to order by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    /**
     * Get creator
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get updater
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
