<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add alt_text column if missing
        if (!Schema::hasColumn('product_images', 'alt_text')) {
            Schema::table('product_images', function (Blueprint $table) {
                $table->string('alt_text')->nullable()->after('is_featured');
            });
        }
        
        // Rename sort_order to display_order
        if (Schema::hasColumn('product_images', 'sort_order')) {
            Schema::table('product_images', function (Blueprint $table) {
                $table->renameColumn('sort_order', 'display_order');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('product_images', 'display_order')) {
            Schema::table('product_images', function (Blueprint $table) {
                $table->renameColumn('display_order', 'sort_order');
            });
        }
        
        if (Schema::hasColumn('product_images', 'alt_text')) {
            Schema::table('product_images', function (Blueprint $table) {
                $table->dropColumn('alt_text');
            });
        }
    }
};
