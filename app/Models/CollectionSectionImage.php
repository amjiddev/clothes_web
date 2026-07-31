<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionSectionImage extends Model
{
    protected $table = 'collection_section_images';

    protected $fillable = [
        'collection_section_id',
        'image_path',
        'image_alt_text',
        'product_name',
        'product_price',
        'display_order',
    ];

    protected $casts = [
        'display_order' => 'integer',
    ];

    /**
     * Get the collection section this image belongs to
     */
    public function collectionSection()
    {
        return $this->belongsTo(CollectionSection::class, 'collection_section_id');
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute()
    {
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }
        return asset('storage/' . $this->image_path);
    }
}
