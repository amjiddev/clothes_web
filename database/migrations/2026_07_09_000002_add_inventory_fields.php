<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->decimal('cost_per_unit', 10, 2)->nullable()->default(0)->after('sku');
            $table->timestamp('last_restock_date')->nullable()->after('cost_per_unit');
        });
    }

    public function down(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            $table->dropColumn(['cost_per_unit', 'last_restock_date']);
        });
    }
};