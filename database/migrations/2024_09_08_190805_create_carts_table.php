<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->decimal('total_cost')->nullable();
            $table->decimal('total_discount')->nullable();
            $table->decimal('original_cost')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->decimal('coupon_discount_value', 10, 2)->nullable();
            $table->decimal('cart_final_cost', 10, 2)->nullable();
            $table->decimal('delivery_cost', 10, 2)->nullable();

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
        Schema::dropIfExists('carts');
    }
}
