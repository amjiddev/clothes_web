<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tailors', function (Blueprint $table) {
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('tailors', 'phone')) {
                $table->string('phone')->nullable()->after('user_id');
            }
            
            if (!Schema::hasColumn('tailors', 'skills')) {
                $table->json('skills')->nullable()->after('specialization');
            }
            
            if (!Schema::hasColumn('tailors', 'profile_image')) {
                $table->string('profile_image')->nullable()->after('bio');
            }
            
            if (!Schema::hasColumn('tailors', 'completed_orders')) {
                $table->integer('completed_orders')->default(0)->after('total_orders');
            }
            
            if (!Schema::hasColumn('tailors', 'pending_orders')) {
                $table->integer('pending_orders')->default(0)->after('completed_orders');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tailors', function (Blueprint $table) {
            $table->dropColumn(['phone', 'skills', 'profile_image', 'completed_orders', 'pending_orders']);
        });
    }
};
