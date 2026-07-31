<?php

namespace Database\Seeders;

use App\Models\CollectionSection;
use App\Models\CollectionSectionImage;
use Illuminate\Database\Seeder;

class CollectionSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create first section
        $section1 = CollectionSection::create([
            'section_name' => 'Best Sellers',
            'section_key' => 'best_sellers',
            'badge_text' => 'TRENDING NOW',
            'badge_bg_color' => 'gold',
            'title' => 'Best Sellers',
            'description' => 'Discover our most loved pieces. These customer favorites combine timeless style with exceptional quality, making them the cornerstone of any wardrobe.',
            'button_text' => 'Shop Best Sellers',
            'button_link' => '/shop',
            'features' => ['1+ Products', '10K+ Happy Customers'],
            'is_published' => true,
            'display_order' => 1,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        for ($i = 1; $i <= 4; $i++) {
            CollectionSectionImage::create([
                'collection_section_id' => $section1->id,
                'image_path' => 'collections/placeholder.jpg',
                'image_alt_text' => 'Best Seller Product ' . $i,
                'product_name' => 'Premium Shirt ' . $i,
                'product_price' => 'Rs. ' . (2000 + $i * 500),
                'display_order' => $i - 1,
            ]);
        }

        // Create second section
        $section2 = CollectionSection::create([
            'section_name' => 'Summer Collection',
            'section_key' => 'summer_collection',
            'badge_text' => 'SUMMER 2026',
            'badge_bg_color' => 'dark',
            'title' => 'Summer Collection',
            'description' => 'Embrace the warmth with our Summer 2026 Collection. Lightweight fabrics, breathable designs, and contemporary cuts perfect for the season.',
            'button_text' => 'Shop Summer Collection',
            'button_link' => '/shop',
            'features' => ['Breathable Fabrics', 'Modern Cuts', 'Vibrant Colors'],
            'is_published' => true,
            'display_order' => 2,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        for ($i = 1; $i <= 4; $i++) {
            CollectionSectionImage::create([
                'collection_section_id' => $section2->id,
                'image_path' => 'collections/placeholder.jpg',
                'image_alt_text' => 'Summer Product ' . $i,
                'product_name' => 'Summer Dress ' . $i,
                'product_price' => 'Rs. ' . (1500 + $i * 400),
                'display_order' => $i - 1,
            ]);
        }

        // Create third section
        $section3 = CollectionSection::create([
            'section_name' => 'Featured Collection',
            'section_key' => 'featured_collection',
            'badge_text' => "EDITOR'S CHOICE",
            'badge_bg_color' => 'dark',
            'title' => 'Featured Collection',
            'description' => 'Handpicked by our style experts, this exclusive collection showcases pieces that embody sophistication, elegance, and contemporary fashion.',
            'button_text' => 'Explore Collection',
            'button_link' => '/shop',
            'features' => ['Expertly Curated', 'Premium Quality', 'Exclusive Designs'],
            'is_published' => true,
            'display_order' => 3,
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        for ($i = 1; $i <= 4; $i++) {
            CollectionSectionImage::create([
                'collection_section_id' => $section3->id,
                'image_path' => 'collections/placeholder.jpg',
                'image_alt_text' => 'Featured Product ' . $i,
                'product_name' => 'Exclusive Outfit ' . $i,
                'product_price' => 'Rs. ' . (3000 + $i * 600),
                'display_order' => $i - 1,
            ]);
        }
    }
}
