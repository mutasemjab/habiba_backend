<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $userId = Auth::user()->id;
        $favoriteProductIds = Favorite::where('client_id', $userId)->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $favoriteProductIds)->get();
        foreach ($products as $product) {
            $imageUrls = [];
            // Use the accessors directly
            $product->category_name = $product->category_name;
            $product->sub_category_name = $product->sub_category_name;
            $product->brand_name = $product->brand_name;
            $product->offer_percentage = $product->offer_percentage;

            // URL for product image
            $product->image = url("products/images/{$product->image}");

            // Process gallery images
            foreach ($product->gallary as $imageName) {
                $imageUrls[] = url("products/gallary/{$imageName}");
            }
            $product->gallary = $imageUrls;

            // Calculate pricing
            $activeOffer = $product->activeOffer();
            if ($activeOffer) {
                $originalPrice = $product->price;
                $discountedPrice = $originalPrice - ($originalPrice * ($product->offer_percentage / 100));
                $product->original_price = $originalPrice;
                $product->discounted_price = $discountedPrice;
            } else {
                $product->original_price = $product->price;
                $product->discounted_price = null; // No discount
            }

            // Set is_favorite to true
            $product->is_favorite = true; // All products returned here are favorites
        }

        return response()->json([
            'state' => true,
            'data' => $products ?? [], // Return only favorite products
            'message' => 'المنتجات المفضلة تم تحميلها بنجاح',
        ], 200);
    }



    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::user()->id;
        $productId = $request->product_id;
        $favorite = Favorite::where('client_id', $userId)->where('product_id', $productId)->first();
        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'state' => true,
                'message' => 'Product removed from favorites successfully.',
            ], 200);
        } else {
            Favorite::create([
                'client_id' => $userId,
                'product_id' => $productId,
            ]);

            return response()->json([
                'state' => true,
                'message' => 'Product added to favorites successfully.',
            ], 201);
        }
    }
}