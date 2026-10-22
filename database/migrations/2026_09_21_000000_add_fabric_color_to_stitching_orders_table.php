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
            // Add missing columns if they don't exist
            if (!Schema::hasColumn('stitching_orders', 'fabric_type')) {
                $table->string('fabric_type')->nullable()->after('garment_type');
            }
            if (!Schema::hasColumn('stitching_orders', 'color')) {
                $table->string('color')->nullable()->after('fabric_type');
            }
            if (!Schema::hasColumn('stitching_orders', 'design_details')) {
                $table->text('design_details')->nullable()->after('fabric_details');
            }
            if (!Schema::hasColumn('stitching_orders', 'design_image')) {
                $table->string('design_image')->nullable()->after('design_details');
            }
            if (!Schema::hasColumn('stitching_orders', 'service_option')) {
                $table->string('service_option')->nullable()->after('fabric_type');
            }
            if (!Schema::hasColumn('stitching_orders', 'additional_instructions')) {
                $table->text('additional_instructions')->nullable()->after('special_instructions');
            }
            if (!Schema::hasColumn('stitching_orders', 'estimated_cost')) {
                $table->decimal('estimated_cost', 10, 2)->nullable()->after('additional_instructions');
            }
            if (!Schema::hasColumn('stitching_orders', 'service_request_date')) {
                $table->timestamp('service_request_date')->nullable()->after('completion_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stitching_orders', function (Blueprint $table) {
            $table->dropColumnIfExists([
                'fabric_type',
                'color',
                'design_details',
                'design_image',
                'service_option',
                'additional_instructions',
                'estimated_cost',
                'service_request_date'
            ]);
        });
    }
};
