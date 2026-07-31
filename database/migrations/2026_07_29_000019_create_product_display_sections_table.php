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
        Schema::create('product_display_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->enum('section', [
                'home_featured',
                'shop_page',
                'new_in',
                'brands_page',
                'collections',
                'best_sellers',
                'summer_2026'
            ]);
            $table->integer('display_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Prevent duplicate entries
            $table->unique(['product_id', 'section']);
            
            // Indexes for better performance
            $table->index('section');
            $table->index(['section', 'is_active']);
            $table->index(['section', 'display_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_display_sections');
    }
};
