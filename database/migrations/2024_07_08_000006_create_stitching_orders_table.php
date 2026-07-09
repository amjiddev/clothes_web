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
        Schema::create('stitching_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('measurement_id')->nullable()->constrained('customer_measurements')->onDelete('set null');
            $table->foreignId('tailor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('garment_type'); // Shirt, Pants, Kurta, etc.
            $table->text('fabric_details')->nullable();
            $table->enum('stitching_status', ['pending', 'assigned', 'in_progress', 'ready_for_fitting', 'in_fitting', 'ready', 'completed', 'cancelled'])->default('pending');
            $table->text('special_instructions')->nullable();
            $table->timestamp('assigned_date')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('fitting_date')->nullable();
            $table->timestamp('completion_date')->nullable();
            $table->text('tailor_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stitching_orders');
    }
};
