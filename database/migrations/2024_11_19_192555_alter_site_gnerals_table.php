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
        Schema::table('site_generals', function (Blueprint $table) {
            $table->string('onboarding_1')->nullable();
            $table->string('onboarding_2')->nullable();
            $table->string('onboarding_3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_generals', function (Blueprint $table) {
            $table->dropColumn('onboarding_1')->nullable();
            $table->dropColumn('onboarding_2')->nullable();
            $table->dropColumn('onboarding_3')->nullable();
            
        });
    }
};
