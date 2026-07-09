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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->enum('type', ['ready_made', 'stitching', 'combined'])->default('ready_made');
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'ready', 'delivered', 'cancelled'])->default('pending');
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('stitching_charge', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->string('payment_method')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('delivery_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
