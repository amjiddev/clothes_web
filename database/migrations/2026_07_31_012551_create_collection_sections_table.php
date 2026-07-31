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
        Schema::create('collection_sections', function (Blueprint $table) {
            $table->id();
            $table->string('section_name'); // e.g., "Best Sellers", "Summer Collection", "Featured Collection"
            $table->string('section_key')->unique(); // e.g., "best_sellers", "summer_collection", "featured_collection"
            $table->string('badge_text')->nullable(); // e.g., "TRENDING NOW", "SUMMER 2026"
            $table->string('badge_bg_color')->default('gold'); // gold, dark, etc.
            $table->text('title');
            $table->text('description');
            $table->string('button_text')->default('Shop Now');
            $table->string('button_link')->nullable();
            $table->text('features')->nullable(); // JSON encoded array of features
            $table->boolean('is_published')->default(false);
            $table->integer('display_order')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_sections');
    }
};
