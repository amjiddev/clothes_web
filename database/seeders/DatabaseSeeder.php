<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create some categories
        $categories = Category::factory(5)->create();
        
        // Create some brands
        $brands = Brand::factory(5)->create();
        
        // Create some products
        Product::factory(20)
            ->for($categories->random())
            ->for($brands->random())
            ->create();
        
        // Run additional seeders
        $this->call([
            CollectionSectionSeeder::class,
            ProductSectionsSeeder::class,
        ]);
    }
}
