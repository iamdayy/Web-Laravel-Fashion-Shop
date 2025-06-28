<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('payment_method'); // e.g., 'credit_card', 'paypal', etc.
            $table->decimal('amount', 10, 2); // Total amount
            $table->string('currency')->default('IDR'); // Currency code, default to  Indonesian Rupiah
            $table->string('status')->default('pending'); // e.g., 'pending', 'completed', 'failed'
            $table->string('transaction_id')->nullable(); // Transaction ID from the payment gateway
            $table->timestamp('paid_at')->nullable(); // Timestamp when the payment was made
            $table->string('snap_token')->nullable(); // Token for Midtrans Snap
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
