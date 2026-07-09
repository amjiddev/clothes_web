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
        Schema::table('receptionists', function (Blueprint $table) {
            $table->timestamp('assigned_date')->nullable()->after('status');
            $table->timestamp('last_action_date')->nullable()->after('assigned_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('receptionists', function (Blueprint $table) {
            $table->dropColumn(['assigned_date', 'last_action_date']);
        });
    }
};
