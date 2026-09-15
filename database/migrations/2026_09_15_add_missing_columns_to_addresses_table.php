<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('addresses', 'address')) {
                $table->string('address')->nullable()->after('user_id');
            }
            
            if (!Schema::hasColumn('addresses', 'is_default')) {
                $table->boolean('is_default')->default(false);
            }
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            if (Schema::hasColumn('addresses', 'address')) {
                $table->dropColumn('address');
            }
            
            if (Schema::hasColumn('addresses', 'is_default')) {
                $table->dropColumn('is_default');
            }
        });
    }
};
