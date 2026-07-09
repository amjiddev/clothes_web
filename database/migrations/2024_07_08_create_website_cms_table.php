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
        Schema::create('website_cms', function (Blueprint $table) {
            $table->id();
            $table->string('page_slug')->unique();
            $table->string('page_title');
            $table->text('page_content')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('featured_image')->nullable();
            $table->boolean('is_published')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_cms');
    }
};
