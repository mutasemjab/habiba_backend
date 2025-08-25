<?php

namespace App\Http\Controllers;

use App\Models\AppRate;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AppRateController extends Controller
{
    function index()
    {
        $app_ratings = AppRate::all();
        return view('app_ratings.index', compact('app_ratings'));
    }
    function create(Request $request)
    {
        $this->validate($request, [
            'app_rate' => 'numeric|max:5|min:1',
            'app_usage_rate' => 'numeric|max:5|min:1',
            'delivery_rate' => 'numeric|max:5|min:1',
            'quality_rate' => 'numeric|max:5|min:1',
            'comment' => 'string',
        ]);
        $client = Auth::user();
        $request['client_id'] = $client->id;
        AppRate::create($request->all());
        return response()->json([
            'status' => true,
            'data' => null,
            'message' => 'Thank you'
        ], 200);
    }
}