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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->longText('value')->nullable();
            $table->enum('type', ['string', 'integer', 'boolean', 'json', 'text'])->default('string');
            $table->enum('group', [
                'shop',
                'payment',
                'email',
                'website',
                'general',
                'other'
            ])->default('general');
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index('group');
            $table->index('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
