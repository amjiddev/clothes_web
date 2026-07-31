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
        Schema::create('collection_section_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('collection_section_id')->constrained('collection_sections')->onDelete('cascade');
            $table->string('image_path');
            $table->string('image_alt_text')->nullable();
            $table->string('product_name')->nullable();
            $table->string('product_price')->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_section_images');
    }
};
