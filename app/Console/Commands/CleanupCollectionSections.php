<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Brand;
use App\Models\CollectionSection;

class CleanupCollectionSections extends Command
{
    protected $signature = 'cleanup:collection-sections';
    protected $description = 'Clean up and fix collection sections';

    public function handle()
    {
        $this->info('Cleaning up collection sections...');

        // Delete any extra sections that shouldn't be there
        CollectionSection::whereNotIn('section_key', ['best_sellers', 'summer_collection', 'featured_collection'])->delete();
        
        $this->info('Deleted extra collection sections');

        // Get the brands
        $gullAhmad = Brand::where('slug', 'gull-ahmad')->first();
        $apnaRaza = Brand::where('slug', 'apna-raza')->first(); 
        $eranStrong = Brand::where('slug', 'eran-strong')->first();

        // Update the three main sections
        if ($gullAhmad) {
            CollectionSection::updateOrCreate(
                ['section_key' => 'summer_collection'],
                [
                    'section_name' => 'Summer Collection',
                    'badge_text' => 'SUMMER 2026',
                    'badge_bg_color' => 'dark',
                    'title' => 'Summer Collection',
                    'description' => 'Embrace the warmth with our Summer 2026 Collection. Lightweight fabrics, breathable designs, and contemporary cuts perfect for the season.',
                    'button_text' => 'Gull Ahmad',
                    'button_link' => '/brands-page?brand=' . $gullAhmad->id,
                    'features' => ['Breathable Fabrics', 'Modern Cuts', 'Vibrant Colors'],
                    'is_published' => true,
                    'display_order' => 2,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );
            $this->info("Updated Summer Collection → Gull Ahmad (ID: {$gullAhmad->id})");
        }

        if ($apnaRaza) {
            CollectionSection::updateOrCreate(
                ['section_key' => 'featured_collection'],
                [
                    'section_name' => 'Featured Collection',
                    'badge_text' => "EDITOR'S CHOICE",
                    'badge_bg_color' => 'dark',
                    'title' => 'Featured Collection',
                    'description' => 'Handpicked by our style experts, this exclusive collection showcases pieces that embody sophistication, elegance, and contemporary fashion.',
                    'button_text' => 'Apna Raza',
                    'button_link' => '/brands-page?brand=' . $apnaRaza->id,
                    'features' => ['Expertly Curated', 'Premium Quality', 'Exclusive Designs'],
                    'is_published' => true,
                    'display_order' => 3,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );
            $this->info("Updated Featured Collection → Apna Raza (ID: {$apnaRaza->id})");
        }

        if ($eranStrong) {
            CollectionSection::updateOrCreate(
                ['section_key' => 'best_sellers'],
                [
                    'section_name' => 'Best Sellers',
                    'badge_text' => 'TRENDING NOW',
                    'badge_bg_color' => 'gold',
                    'title' => 'Best Sellers',
                    'description' => 'Discover our most loved pieces. These customer favorites combine timeless style with exceptional quality, making them the cornerstone of any wardrobe.',
                    'button_text' => 'Eran Strong',
                    'button_link' => '/brands-page?brand=' . $eranStrong->id,
                    'features' => ['1+ Products', '10K+ Happy Customers'],
                    'is_published' => true,
                    'display_order' => 1,
                    'created_by' => 1,
                    'updated_by' => 1,
                ]
            );
            $this->info("Updated Best Sellers → Eran Strong (ID: {$eranStrong->id})");
        }

        $this->info('Collection sections cleaned up successfully!');
        return 0;
    }
}