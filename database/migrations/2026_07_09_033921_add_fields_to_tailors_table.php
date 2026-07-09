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
        Schema::table('tailors', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('user_id');
            $table->json('skills')->nullable()->after('specialization');
            $table->string('profile_image')->nullable()->after('bio');
            $table->integer('completed_orders')->default(0)->after('total_orders');
            $table->integer('pending_orders')->default(0)->after('completed_orders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tailors', function (Blueprint $table) {
            $table->dropColumn(['phone', 'skills', 'profile_image', 'completed_orders', 'pending_orders']);
        });
    }
};
