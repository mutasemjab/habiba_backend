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
          $table->string('cliq_number')->nullable()->after('onboarding_1'); // or after any column you want

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_generals', function (Blueprint $table) {
             $table->dropColumn('cliq_number');
        });
    }
};
