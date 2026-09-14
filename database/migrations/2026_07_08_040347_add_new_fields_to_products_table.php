<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('short_description')->nullable()->after('description');
            $table->decimal('discount_price', 10, 2)->nullable()->after('price');
            $table->json('available_sizes')->nullable()->after('size');
            $table->json('available_colors')->nullable()->after('color');
            $table->string('fabric_type')->nullable()->after('material');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'short_description',
                'discount_price',
                'available_sizes',
                'available_colors',
                'fabric_type',
            ]);
        });
    }
};