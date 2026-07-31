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
        // Get all unique brand names from products
        $brandNames = DB::table('products')
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->distinct()
            ->pluck('brand')
            ->toArray();

        // Create brands for each unique brand name
        foreach ($brandNames as $brandName) {
            // Check if brand already exists
            $existingBrand = DB::table('brands')
                ->where('name', $brandName)
                ->first();

            if (!$existingBrand) {
                DB::table('brands')->insert([
                    'name' => $brandName,
                    'slug' => Str::slug($brandName),
                    'description' => 'Brand: ' . $brandName,
                    'is_active' => true,
                    'sort_order' => 999,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Now update products to use brand_id
        foreach ($brandNames as $brandName) {
            $brand = DB::table('brands')
                ->where('name', $brandName)
                ->first();

            if ($brand) {
                DB::table('products')
                    ->where('brand', $brandName)
                    ->update(['brand_id' => $brand->id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reset brand_id to NULL
        DB::table('products')
            ->whereNotNull('brand_id')
            ->update(['brand_id' => null]);

        // Delete newly created brands (optional - you can comment this out to keep them)
        // DB::table('brands')->where('sort_order', 999)->delete();
    }
};
