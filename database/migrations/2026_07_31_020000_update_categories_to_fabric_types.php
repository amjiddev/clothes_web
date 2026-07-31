<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Clear existing categories
        DB::table('categories')->delete();

        // Create new fabric type categories
        $categories = [
            ['name' => 'Cotton', 'description' => 'Premium cotton fabrics'],
            ['name' => 'Wash & Wear', 'description' => 'Easy care wash and wear fabrics'],
            ['name' => 'Khaddar', 'description' => 'Traditional khaddar fabric'],
            ['name' => 'Linen', 'description' => 'Fine linen fabrics'],
            ['name' => 'Boski', 'description' => 'Boski fabric collection'],
            ['name' => 'Dhanak', 'description' => 'Dhanak fabric collection'],
        ];

        $sortOrder = 1;
        foreach ($categories as $categoryData) {
            DB::table('categories')->insert([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'is_active' => true,
                'sort_order' => $sortOrder,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $sortOrder++;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Clear categories on rollback
        DB::table('categories')->delete();

        // Re-create original categories if needed
        $originalCategories = [
            ['name' => 'Shirts', 'description' => 'Casual and formal shirts'],
            ['name' => 'Pants', 'description' => 'Trousers and pants'],
            ['name' => 'Kurtas', 'description' => 'Traditional kurtas'],
            ['name' => 'Blazers', 'description' => 'Formal blazers'],
            ['name' => 'Suits', 'description' => 'Complete suit sets'],
            ['name' => 'Waistcoats', 'description' => 'Waistcoats and vests'],
        ];

        $sortOrder = 1;
        foreach ($originalCategories as $categoryData) {
            DB::table('categories')->insert([
                'name' => $categoryData['name'],
                'slug' => Str::slug($categoryData['name']),
                'description' => $categoryData['description'],
                'is_active' => true,
                'sort_order' => $sortOrder,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $sortOrder++;
        }
    }
};
