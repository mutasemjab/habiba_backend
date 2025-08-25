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
        Schema::table('brands', function (Blueprint $table) {
            $table->string('ar_brand_name')->after('id');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->string('ar_category_name')->after('id');
        });
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->string('ar_sub_category_name')->after('id');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->string('ar_product_name')->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('brands', function (Blueprint $table) {
            $table->dropColumn('ar_brand_name');
        });
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn('ar_category_name');
        });
        Schema::table('sub_categories', function (Blueprint $table) {
            $table->dropColumn('ar_sub_category_name');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('ar_product_name');
        });
    }
};