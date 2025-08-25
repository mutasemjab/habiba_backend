<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class ProductSyncController extends Controller
{
    public function sync()
    {
        try {
            \Log::info('Starting product sync from web interface');
            
            $exitCode = Artisan::call('products:sync-prices');
            $output = Artisan::output();
            
            \Log::info('Command completed with exit code: ' . $exitCode);
            
            // Get structured results from cache
            $cacheKey = 'sync_results_' . (auth()->id() ?? 'guest');
            $results = \Cache::get($cacheKey, []);
            
            \Log::info('Cache key used: ' . $cacheKey);
            \Log::info('Results retrieved from cache:', $results);
            
            // Check if we have any results
            if (empty($results)) {
                \Log::warning('No results found in cache. Cache might have expired or not been set properly.');
                
                // Fallback: create basic results structure
                $results = [
                    'updated_products' => [],
                    'not_found_products' => [],
                    'total_fetched' => 0,
                    'total_updated' => 0,
                    'total_not_found' => 0,
                    'errors' => ['Cache data not found. Please check the logs for detailed information.'],
                    'log_messages' => [
                        ['level' => 'error', 'message' => 'Cache data not available. Please check Laravel logs for detailed sync information.']
                    ]
                ];
            }
            
            \Log::info('Final results being passed to view:', $results);
            
            // Pass data directly to view
            return view('output')->with([
                'sync_results' => $results,
                'command_output' => $output,
                'success' => $exitCode === 0 ? 'Product prices synchronized successfully!' : null,
                'error' => $exitCode !== 0 ? 'Synchronization completed with some issues.' : null
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Sync function error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('output')->with([
                'error' => 'Failed to synchronize product prices: ' . $e->getMessage(),
                'sync_results' => [
                    'errors' => ['Exception: ' . $e->getMessage()],
                    'log_messages' => [
                        ['level' => 'error', 'message' => 'Exception occurred: ' . $e->getMessage()]
                    ]
                ]
            ]);
        }
    }
}