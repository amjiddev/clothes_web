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
        Schema::table('customer_measurements', function (Blueprint $table) {
            // Add missing columns that the model expects
            if (!Schema::hasColumn('customer_measurements', 'profile_name')) {
                $table->string('profile_name')->nullable()->after('user_id');
            }
            
            if (!Schema::hasColumn('customer_measurements', 'shirt_length')) {
                $table->decimal('shirt_length', 8, 2)->nullable()->after('sleeve_length');
            }
            
            if (!Schema::hasColumn('customer_measurements', 'trouser_length')) {
                $table->decimal('trouser_length', 8, 2)->nullable()->after('waist');
            }
            
            if (!Schema::hasColumn('customer_measurements', 'bottom')) {
                $table->decimal('bottom', 8, 2)->nullable()->after('trouser_length');
            }
            
            if (!Schema::hasColumn('customer_measurements', 'thigh')) {
                $table->decimal('thigh', 8, 2)->nullable()->after('bottom');
            }
            
            if (!Schema::hasColumn('customer_measurements', 'cuff_size')) {
                $table->decimal('cuff_size', 8, 2)->nullable()->after('thigh');
            }
            
            if (!Schema::hasColumn('customer_measurements', 'design_image')) {
                $table->string('design_image')->nullable()->after('cuff_size');
            }
            
            if (!Schema::hasColumn('customer_measurements', 'special_instructions')) {
                $table->text('special_instructions')->nullable()->after('design_image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_measurements', function (Blueprint $table) {
            $table->dropColumnIfExists([
                'profile_name',
                'shirt_length',
                'trouser_length',
                'bottom',
                'thigh',
                'cuff_size',
                'design_image',
                'special_instructions'
            ]);
        });
    }
};
