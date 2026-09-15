<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('products')) {
            return;
        }

        Schema::table('products', function (Blueprint $table) {
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