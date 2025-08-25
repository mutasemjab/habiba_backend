<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckSiteStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if status is 2 in site_generals table
        $siteStatus = DB::table('site_generals')->value('status');
        
        if ($siteStatus == 2) {
            return response()->json([
                'status' => false,
                'status' => 2,
                'message' => 'Store is close'
            ], 200); // 503 Service Unavailable
        }

        return $next($request);
    }
}