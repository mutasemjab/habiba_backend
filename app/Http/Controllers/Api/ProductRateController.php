<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductRate;
use Illuminate\Support\Facades\Auth;

class ProductRateController extends Controller
{
    public function rateProduct(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'rate' => 'required|integer|min:1|max:5',
        ]);

        $user = Auth::user();
        $product_id = $request->input('product_id');
        $rate = $request->input('rate');
        $existingRate = ProductRate::where('client_id', $user->id)
            ->where('product_id', $product_id)
            ->first();

        if ($existingRate) {
            $existingRate->update(['rate' => $rate]);
            return response()->json([
                'status' => true,
                'message' => 'تم تعديل تقييمك لهذا المنتج',
                'data' => $existingRate
            ], 200);
        } else {
            // Create a new rating
            $newRate = ProductRate::create([
                'client_id' => $user->id,
                'product_id' => $product_id,
                'rate' => $rate
            ]);
            return response()->json([
                'status' => true,
                'message' => 'شكرا لأبداء رأيك في هذا المنتج',
                'data' => $newRate
            ], 201);
        }
    }
}