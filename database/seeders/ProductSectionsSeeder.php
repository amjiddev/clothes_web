<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductDisplaySection;

class ProductSectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This is a sample seeder to demonstrate the Product Sections Management module.
     * You can run this to create sample products with multiple images and section assignments.
     */
    public function run(): void
    {
        // Get or create a sample category
        $category = Category::firstOrCreate(
            ['slug' => 'sample-category'],
            [
                'name' => 'Sample Category',
                'description' => 'Sample category for testing',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        // Sample Product 1 - Featured on Home and Shop
        $product1 = Product::create([
            'category_id' => $category->id,
            'name' => 'Premium Cotton T-Shirt',
            'slug' => 'premium-cotton-t-shirt',
            'brand' => 'Fashion Brand',
            'price' => 1999.00,
            'regular_price' => 1999.00,
            'sale_price' => 1499.00,
            'short_description' => 'Comfortable premium cotton t-shirt perfect for everyday wear',
            'description' => 'Comfortable premium cotton t-shirt perfect for everyday wear',
            'full_description' => 'Made from 100% premium cotton, this t-shirt offers superior comfort and durability. Perfect for casual outings or lounging at home.',
            'stock_quantity' => 50,
            'is_active' => true,
        ]);

        // Assign to sections
        ProductDisplaySection::create([
            'product_id' => $product1->id,
            'section' => 'home_featured',
            'display_order' => 1,
            'is_active' => true,
        ]);

        ProductDisplaySection::create([
            'product_id' => $product1->id,
            'section' => 'shop_page',
            'display_order' => 1,
            'is_active' => true,
        ]);

        ProductDisplaySection::create([
            'product_id' => $product1->id,
            'section' => 'best_sellers',
            'display_order' => 1,
            'is_active' => true,
        ]);

        // Sample Product 2 - New In and Summer Sale
        $product2 = Product::create([
            'category_id' => $category->id,
            'name' => 'Summer Linen Shirt',
            'slug' => 'summer-linen-shirt',
            'brand' => 'Summer Collection',
            'price' => 2999.00,
            'regular_price' => 2999.00,
            'sale_price' => 1999.00,
            'short_description' => 'Light and breezy linen shirt for summer',
            'description' => 'Light and breezy linen shirt for summer',
            'full_description' => 'Stay cool and stylish this summer with our premium linen shirt. Breathable fabric and modern fit.',
            'stock_quantity' => 30,
            'is_active' => true,
        ]);

        ProductDisplaySection::create([
            'product_id' => $product2->id,
            'section' => 'new_in',
            'display_order' => 1,
            'is_active' => true,
        ]);

        ProductDisplaySection::create([
            'product_id' => $product2->id,
            'section' => 'summer_sale',
            'display_order' => 1,
            'is_active' => true,
        ]);

        ProductDisplaySection::create([
            'product_id' => $product2->id,
            'section' => 'summer_2026',
            'display_order' => 1,
            'is_active' => true,
        ]);

        // Sample Product 3 - Collections and Best Sellers
        $product3 = Product::create([
            'category_id' => $category->id,
            'name' => 'Classic Denim Jeans',
            'slug' => 'classic-denim-jeans',
            'brand' => 'Denim Co',
            'price' => 3499.00,
            'regular_price' => 3499.00,
            'sale_price' => null,
            'short_description' => 'Timeless denim jeans with perfect fit',
            'description' => 'Timeless denim jeans with perfect fit',
            'full_description' => 'Classic denim jeans crafted from premium denim fabric. Features comfortable fit and durable construction.',
            'stock_quantity' => 40,
            'is_active' => true,
        ]);

        ProductDisplaySection::create([
            'product_id' => $product3->id,
            'section' => 'collections',
            'display_order' => 1,
            'is_active' => true,
        ]);

        ProductDisplaySection::create([
            'product_id' => $product3->id,
            'section' => 'best_sellers',
            'display_order' => 2,
            'is_active' => true,
        ]);

        ProductDisplaySection::create([
            'product_id' => $product3->id,
            'section' => 'shop_page',
            'display_order' => 2,
            'is_active' => true,
        ]);

        $this->command->info('✅ Sample products created successfully!');
        $this->command->info('📦 3 products with section assignments have been added.');
        $this->command->info('🔗 View them at: /admin/website-management/product-sections');
    }
}
