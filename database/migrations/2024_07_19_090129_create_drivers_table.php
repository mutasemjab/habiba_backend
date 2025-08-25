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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username');
            $table->string('password');
            $table->string('nid');
            $table->string('vehichle_color');
            $table->string('vehichle_number');
            $table->date('vehichle_license_ends_at');
            $table->integer('wallet')->nullable()->default(0);
            $table->integer('status');
            $table->string('mobile')->nullable();
            $table->string('nationality')->nullable();
            $table->string('vehichle_brand')->nullable();
            $table->string('vehichle_type')->nullable();
            $table->string('vehichle_model')->nullable();
            $table->string('vehichle_model_year')->nullable();
            $table->string('licence_name')->nullable();
            $table->string('licence_grade')->nullable();
            $table->string('licence_type')->nullable();
            $table->string('licence_issue_date')->nullable();
            $table->string('licence_end_date')->nullable();
            $table->string('licence_no')->nullable();
            $table->string('notes')->nullable();
            $table->string('image')->nullable(); // Add this line for the driver image

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};