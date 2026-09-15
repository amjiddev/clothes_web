<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\CollectionSection;
use App\Models\Brand;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, let's ensure we have some brands (you can modify these based on your actual brands)
        $brands = [
            ['name' => 'Gull Ahmad', 'slug' => 'gull-ahmad', 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Apna Raza', 'slug' => 'apna-raza', 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Eran Strong', 'slug' => 'eran-strong', 'is_active' => true, 'sort_order' => 3],
        ];

        foreach ($brands as $brandData) {
            Brand::firstOrCreate(
                ['slug' => $brandData['slug']],
                $brandData
            );
        }

        // Now let's update the collection sections with brand-specific links
        $sectionsToUpdate = [
            [
                'section_key' => 'summer_collection',
                'button_text' => 'Gull Ahmad',
                'brand_slug' => 'gull-ahmad'
            ],
            [
                'section_key' => 'featured_collection', 
                'button_text' => 'Apna Raza',
                'brand_slug' => 'apna-raza'
            ],
            [
                'section_key' => 'best_sellers',
                'button_text' => 'Eran Strong', 
                'brand_slug' => 'eran-strong'
            ]
        ];

        foreach ($sectionsToUpdate as $sectionData) {
            $brand = Brand::where('slug', $sectionData['brand_slug'])->first();
            if ($brand) {
                CollectionSection::where('section_key', $sectionData['section_key'])
                    ->update([
                        'button_text' => $sectionData['button_text'],
                        'button_link' => '/brands-page?brand=' . $brand->id
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original button links
        CollectionSection::where('section_key', 'summer_collection')
            ->update([
                'button_text' => 'Shop Summer Collection',
                'button_link' => '/shop'
            ]);

        CollectionSection::where('section_key', 'featured_collection')
            ->update([
                'button_text' => 'Explore Collection', 
                'button_link' => '/shop'
            ]);

        CollectionSection::where('section_key', 'best_sellers')
            ->update([
                'button_text' => 'Shop Best Sellers',
                'button_link' => '/shop'
            ]);
    }
};