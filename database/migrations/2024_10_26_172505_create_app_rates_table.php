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
        Schema::create('app_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id');
            $table->integer('app_rate');
            $table->integer('app_usage_rate');
            $table->integer('delivery_rate');
            $table->integer('quality_rate');
            $table->string('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_rates');
    }
};
