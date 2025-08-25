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
        Schema::create('site_generals', function (Blueprint $table) {
            $table->id();
            $table->double('min_order');
            $table->string('whatsapp_link');
            $table->string('instagram_link');
            $table->string('facebook_link');
            $table->text('terms');
            $table->text('about_us');
            $table->text('return_policy');
            $table->integer('delivery_price');
            $table->integer('profit')->default(0);
            $table->integer('status')->default(1); // 1 on // 2 off
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_generals');
    }
};
