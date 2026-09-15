<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_cms', function (Blueprint $table) {
            $table->id();
            $table->string('section_type')->default('slider');
            $table->string('page_slug')->nullable();
            $table->string('page_title')->nullable();
            $table->longText('page_content')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->json('data')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('display_order')->default(0);
            $table->timestamps();

            $table->index('section_type');
            $table->index('is_published');
            $table->index('display_order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_cms');
    }
};