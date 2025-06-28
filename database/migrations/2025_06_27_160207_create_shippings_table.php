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
        Schema::create('shippings', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->string('subdistrict')->nullable();
            $table->string('district')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('postal_code');
            $table->string('phone')->nullable();
            $table->string('courier')->default('standard');
            $table->decimal('shipping_cost', 8, 2)->default(0.00);
            $table->enum('status', ['pending', 'shipped', 'delivered', 'cancelled'])
                ->default('pending');
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamps();

            $table->foreignId('order_id')
                ->constrained('orders')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shippings');
    }
};
