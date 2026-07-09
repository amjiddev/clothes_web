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
            // Add new columns if they don't exist
            if (!Schema::hasColumn('products', 'short_description')) {
                $table->text('short_description')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'discount_price')) {
                $table->decimal('discount_price', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('products', 'available_sizes')) {
                $table->json('available_sizes')->nullable()->after('size');
            }
            if (!Schema::hasColumn('products', 'available_colors')) {
                $table->json('available_colors')->nullable()->after('color');
            }
            if (!Schema::hasColumn('products', 'fabric_type')) {
                $table->string('fabric_type')->nullable()->after('material');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumnIfExists('short_description');
            $table->dropColumnIfExists('discount_price');
            $table->dropColumnIfExists('available_sizes');
            $table->dropColumnIfExists('available_colors');
            $table->dropColumnIfExists('fabric_type');
        });
    }
};
