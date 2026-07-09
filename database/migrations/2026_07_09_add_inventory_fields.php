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
        Schema::table('inventory', function (Blueprint $table) {
            if (!Schema::hasColumn('inventory', 'cost_per_unit')) {
                $table->decimal('cost_per_unit', 10, 2)->nullable()->default(0)->after('sku');
            }
            if (!Schema::hasColumn('inventory', 'last_restock_date')) {
                $table->timestamp('last_restock_date')->nullable()->after('cost_per_unit');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory', function (Blueprint $table) {
            if (Schema::hasColumn('inventory', 'cost_per_unit')) {
                $table->dropColumn('cost_per_unit');
            }
            if (Schema::hasColumn('inventory', 'last_restock_date')) {
                $table->dropColumn('last_restock_date');
            }
        });
    }
};
