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
        Schema::create('user_f_c_m_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained()->ondelete('cascade')->onUpdate('cascade');
            $table->foreignId('driver_id')->nullable()->constrained()->ondelete('cascade')->onUpdate('cascade');
            $table->string('token')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_f_c_m_tokens');
    }
};
