<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if table exists
        if (Schema::hasTable('product_display_sections')) {
            // First, delete any rows with 'summer_sale' section (old invalid value)
            DB::table('product_display_sections')->where('section', 'summer_sale')->delete();
            
            // For MySQL, we need to modify the enum
            if (DB::getDriverName() === 'mysql') {
                DB::statement("
                    ALTER TABLE product_display_sections 
                    MODIFY COLUMN section ENUM(
                        'home_featured',
                        'shop_page',
                        'new_in',
                        'brands_page',
                        'collections',
                        'best_sellers',
                        'summer_2026'
                    ) NOT NULL
                ");
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to old enum if needed
        if (Schema::hasTable('product_display_sections')) {
            if (DB::getDriverName() === 'mysql') {
                DB::statement("
                    ALTER TABLE product_display_sections 
                    MODIFY COLUMN section ENUM(
                        'home_featured',
                        'shop_page',
                        'new_in',
                        'collections',
                        'best_sellers',
                        'summer_2026'
                    ) NOT NULL
                ");
            }
        }
    }
};
