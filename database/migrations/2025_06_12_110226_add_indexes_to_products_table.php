<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Check if indexes don't already exist before creating them
            
            // Add indexes for search columns with length limits for VARCHAR columns
            if (!$this->indexExists('products', 'idx_products_name')) {
                DB::statement('CREATE INDEX idx_products_name ON products (product_name(100))');
            }
            
            if (!$this->indexExists('products', 'idx_products_barcode')) {
                DB::statement('CREATE INDEX idx_products_barcode ON products (barcode(50))');
            }
            
            if (!$this->indexExists('products', 'idx_products_status')) {
                $table->index('product_status', 'idx_products_status');
            }
            
            // Add indexes for foreign keys if not already indexed
            if (!$this->indexExists('products', 'idx_products_category_id')) {
                $table->index('category_id', 'idx_products_category_id');
            }
            
            if (!$this->indexExists('products', 'idx_products_sub_category_id')) {
                $table->index('sub_category_id', 'idx_products_sub_category_id');
            }
            
            if (!$this->indexExists('products', 'idx_products_brand_id')) {
                $table->index('brand_id', 'idx_products_brand_id');
            }
            
            // Composite indexes with length limits
            if (!$this->indexExists('products', 'idx_products_status_name')) {
                DB::statement('CREATE INDEX idx_products_status_name ON products (product_status, product_name(100))');
            }
            
            if (!$this->indexExists('products', 'idx_products_status_barcode')) {
                DB::statement('CREATE INDEX idx_products_status_barcode ON products (product_status, barcode(50))');
            }
        });
    }
    
    /**
     * Check if an index exists on a table
     */
    private function indexExists($table, $indexName)
    {
        $indexes = DB::select("SHOW INDEX FROM {$table} WHERE Key_name = ?", [$indexName]);
        return count($indexes) > 0;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop indexes if they exist
            DB::statement('DROP INDEX IF EXISTS idx_products_name ON products');
            DB::statement('DROP INDEX IF EXISTS idx_products_barcode ON products');
            
            // Use try-catch for regular indexes in case they don't exist
            try {
                $table->dropIndex('idx_products_status');
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
            
            try {
                $table->dropIndex('idx_products_category_id');
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
            
            try {
                $table->dropIndex('idx_products_sub_category_id');
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
            
            try {
                $table->dropIndex('idx_products_brand_id');
            } catch (\Exception $e) {
                // Index doesn't exist, continue
            }
            
            DB::statement('DROP INDEX IF EXISTS idx_products_status_name ON products');
            DB::statement('DROP INDEX IF EXISTS idx_products_status_barcode ON products');
        });
    }
};