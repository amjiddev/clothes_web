<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stitching_orders', function (Blueprint $table) {
            $table->enum('service_option', ['cloth_only', 'cloth_stitching', 'stitching_only'])->default('cloth_stitching')->after('garment_type');
            $table->string('design_image')->nullable()->after('fabric_details');
            $table->text('additional_instructions')->nullable()->after('special_instructions');
            $table->decimal('estimated_cost', 10, 2)->nullable()->after('additional_instructions');
            $table->timestamp('service_request_date')->nullable()->after('estimated_cost');
        });
    }

    public function down(): void
    {
        Schema::table('stitching_orders', function (Blueprint $table) {
            $table->dropColumn(['service_option', 'design_image', 'additional_instructions', 'estimated_cost', 'service_request_date']);
        });
    }
};