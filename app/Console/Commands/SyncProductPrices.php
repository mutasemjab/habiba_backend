<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class SyncProductPrices extends Command
{
    protected $signature = 'products:sync-prices';
    protected $description = 'Synchronize product prices from external API';
    
    public function handle()
    {
        // Array to collect all log messages for display
        $logMessages = [];
        
        $logMessages[] = ['level' => 'info', 'message' => 'Command handle method started'];
        $logMessages[] = ['level' => 'info', 'message' => 'Starting product price synchronization...'];
        \Log::info('Command handle method started');
        \Log::info('Starting product price synchronization...');
        
        $results = [
            'updated_products' => [],
            'not_found_products' => [],
            'total_fetched' => 0,
            'total_updated' => 0,
            'total_not_found' => 0,
            'errors' => [],
            'log_messages' => []
        ];
        
        $cacheKey = 'sync_results_' . (auth()->id() ?? 'guest');
        \Log::info('Using cache key: ' . $cacheKey);
        
        try {
            $logMessages[] = ['level' => 'info', 'message' => 'Making API request...'];
            \Log::info('Making API request...');
            
            // 1. Fetch products with updated prices from API
            $response = Http::get('https://habiba.mutasemjaber.online/api/Items/GetItems', [
                'BranchNo' => 1,
                'PriceId' => 1,
                'IsECommerce' => 'true',
                'IsPOSItem' => 'false',
                'ALLItems' => 'false'
            ]);
            
            if (!$response->successful()) {
                $error = 'Failed to fetch data from API: ' . $response->status();
                $logMessages[] = ['level' => 'error', 'message' => $error];
                \Log::error($error);
                $this->error($error);
                $results['errors'][] = $error;
                $results['log_messages'] = $logMessages;
                
                \Cache::put($cacheKey, $results, now()->addMinutes(10));
                \Log::info('Cached results after API error:', $results);
                return 1;
            }
            
            $products = $response->json();
            
            $logMessages[] = ['level' => 'info', 'message' => 'API response received successfully'];
            \Log::info('API response received successfully');
            
            // Handle empty response or no content
            if (empty($products) || !is_array($products)) {
                $message = 'No products found in API response.';
                $logMessages[] = ['level' => 'warning', 'message' => $message];
                \Log::info($message);
                $results['errors'][] = $message;
                $results['log_messages'] = $logMessages;
                
                \Cache::put($cacheKey, $results, now()->addMinutes(10));
                \Log::info('Cached results after empty API response:', $results);
                return 0;
            }
            
            $results['total_fetched'] = count($products);
            $fetchMessage = 'Fetched ' . count($products) . ' products with price updates';
            $logMessages[] = ['level' => 'info', 'message' => $fetchMessage];
            \Log::info($fetchMessage);
            
            // 2 & 3. Match and update products
            foreach ($products as $apiProduct) {
                $barcode = $apiProduct['barCode'];
                $newPrice = $apiProduct['bcPrice'];
                $productName = $apiProduct['bcNameEn'];
                
                // Find the product by barcode
                $product = Product::where('barcode', $barcode)->first();
                
                if ($product) {
                    // Only update if price is different
                    if ($product->price != $newPrice) {
                        $oldPrice = $product->price;
                        $product->price = $newPrice;
                        $product->save();
                        
                        // Store update info
                        $results['updated_products'][] = [
                            'product_name' => $product->product_name,
                            'barcode' => $barcode,
                            'old_price' => $oldPrice,
                            'new_price' => $newPrice
                        ];
                        
                        $results['total_updated']++;
                        $updateMessage = "Updated price for '{$product->product_name}' (Barcode: {$barcode}) from {$oldPrice} to {$newPrice}";
                        $logMessages[] = ['level' => 'success', 'message' => $updateMessage];
                        \Log::info($updateMessage);
                    } else {
                        $noChangeMessage = "No price change for '{$product->product_name}' (Barcode: {$barcode}) - price remains {$newPrice}";
                        $logMessages[] = ['level' => 'info', 'message' => $noChangeMessage];
                    }
                } else {
                    // Store not found info
                    $results['not_found_products'][] = [
                        'product_name' => $productName,
                        'barcode' => $barcode,
                        'price' => $newPrice
                    ];
                    
                    $results['total_not_found']++;
                    $notFoundMessage = "Product with barcode {$barcode} ({$productName}) not found in your database";
                    $logMessages[] = ['level' => 'warning', 'message' => $notFoundMessage];
                    \Log::info($notFoundMessage);
                }
            }
            
            $completionMessage = "Synchronization completed. Updated {$results['total_updated']} products. {$results['total_not_found']} products not found in your database.";
            $logMessages[] = ['level' => 'info', 'message' => $completionMessage];
            \Log::info($completionMessage);
            
            // Store log messages in results
            $results['log_messages'] = $logMessages;
            
            // Store results in cache for retrieval by controller
            \Cache::put($cacheKey, $results, now()->addMinutes(10));
            \Log::info('Final results cached successfully:', $results);
            
            return 0;
            
        } catch (\Exception $e) {
            $error = 'Error during synchronization: ' . $e->getMessage();
            $logMessages[] = ['level' => 'error', 'message' => $error];
            \Log::error($error);
            $results['errors'][] = $error;
            $results['log_messages'] = $logMessages;
            
            \Log::error('Product price sync error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            \Cache::put($cacheKey, $results, now()->addMinutes(10));
            \Log::info('Cached results after exception:', $results);
            return 1;
        }
    }
}