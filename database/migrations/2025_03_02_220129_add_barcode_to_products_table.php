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
        Schema::table('products', function (Blueprint $table) {
            $table->text('barcode')->after('id'); // Add the column first
            $table->unique('barcode', 'unique_barcode_index'); // Add the unique index separately
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('barcode')->after('id'); // Add the column first
            $table->unique('barcode', 'unique_barcode_index'); // Add the unique index separately
        });
    }
};
