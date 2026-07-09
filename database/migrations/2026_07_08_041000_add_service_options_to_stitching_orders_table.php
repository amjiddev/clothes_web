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
        Schema::table('stitching_orders', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('stitching_orders', 'service_option')) {
                $table->enum('service_option', ['cloth_only', 'cloth_stitching', 'stitching_only'])
                      ->default('cloth_stitching')
                      ->after('garment_type');
            }
            if (!Schema::hasColumn('stitching_orders', 'design_image')) {
                $table->string('design_image')->nullable()->after('fabric_details');
            }
            if (!Schema::hasColumn('stitching_orders', 'additional_instructions')) {
                $table->text('additional_instructions')->nullable()->after('special_instructions');
            }
            if (!Schema::hasColumn('stitching_orders', 'estimated_cost')) {
                $table->decimal('estimated_cost', 10, 2)->nullable()->after('additional_instructions');
            }
            if (!Schema::hasColumn('stitching_orders', 'service_request_date')) {
                $table->timestamp('service_request_date')->nullable()->after('estimated_cost');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stitching_orders', function (Blueprint $table) {
            $table->dropColumnIfExists('service_option');
            $table->dropColumnIfExists('design_image');
            $table->dropColumnIfExists('additional_instructions');
            $table->dropColumnIfExists('estimated_cost');
            $table->dropColumnIfExists('service_request_date');
        });
    }
};
