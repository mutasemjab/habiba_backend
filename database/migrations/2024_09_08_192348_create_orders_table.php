<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 10, 2);
            $table->string('status')->default('pending');
            $table->text('shipping_address')->nullable();
            $table->text('billing_address')->nullable();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons','id');
            $table->foreignId('branch_id')->nullable()->constrained('branches','id');
            $table->decimal('total_cost')->nullable();
            $table->decimal('total_discount')->nullable();
            $table->decimal('original_cost')->nullable();
            $table->decimal('coupon_discount_value', 10, 2)->nullable();
            $table->decimal('order_final_cost', 10, 2)->nullable();
            $table->decimal('delivery_cost', 10, 2)->nullable();
            $table->string('address_mark')->nullable();
            $table->string('address_title')->nullable();
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->foreignId('driver_id')->nullable()->constrained('drivers','id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}