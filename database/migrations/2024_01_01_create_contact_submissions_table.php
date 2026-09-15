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
        Schema::create('contact_submissions', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['message', 'tailoring_request'])->default('message');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->string('subject')->nullable();
            $table->text('message');
            
            // Tailoring specific fields
            $table->string('service_type')->nullable(); // Cloth Only, Cloth + Stitching, Stitching Only
            $table->string('garment_type')->nullable();
            $table->text('measurements')->nullable(); // JSON data
            $table->string('special_instructions')->nullable();
            $table->string('design_image')->nullable(); // File path
            
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
            
            $table->index('type');
            $table->index('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contact_submissions');
    }
};
