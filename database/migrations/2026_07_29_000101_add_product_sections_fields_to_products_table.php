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
        Schema::table('products', function (Blueprint $table) {
            // Add brand field if it doesn't exist
            if (!Schema::hasColumn('products', 'brand')) {
                $table->string('brand')->nullable()->after('category_id');
            }
            
            // Add regular_price field if it doesn't exist
            if (!Schema::hasColumn('products', 'regular_price')) {
                $table->decimal('regular_price', 10, 2)->nullable()->after('price');
            }
            
            // Add sale_price field if it doesn't exist
            if (!Schema::hasColumn('products', 'sale_price')) {
                $table->decimal('sale_price', 10, 2)->nullable()->after('regular_price');
            }
            
            // Add full_description field if it doesn't exist
            if (!Schema::hasColumn('products', 'full_description')) {
                $table->text('full_description')->nullable()->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['brand', 'regular_price', 'sale_price', 'full_description']);
        });
    }
};
