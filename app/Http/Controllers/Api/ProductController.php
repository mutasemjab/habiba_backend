<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ProductController extends Controller
{
    function index()
    {
        $products = Product::all();
        foreach ($products as $product) {
            $imageUrls = [];
            $product->category = $product->category->category_name;
            $product->sub_category = $product->sub_category->sub_category_name;
            $product->brand = $product->brand->brand_name;
            $product->product_unit = $product->product_unit->product_unit;
            $product->image = url("products/images/{$product->image}");
            foreach ($product->gallary as $imageName) {
                $imageUrls[] = url("products/gallary/{$imageName}");
            }
            $product->gallary = $imageUrls;
            $product->category = $product->category->category_name;
            $product->sub_category = $product->sub_category->sub_category_name;
            $product->brand = $product->brand->brand_name;
        }
        return response()->json([
            'state' => 'true',
            'data' => $products,
            'message' => 'المنتجات تم تحميلها بنجاح',
        ], 200);
    }
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $is_rated = false;
        $bearerToken = request()->bearerToken();
        $ratedProductsResponse = Http::withToken($bearerToken)->get(route('client.rated_products'));
        if ($ratedProductsResponse->successful()) {
            $ratedProductsData = $ratedProductsResponse->json();
            $ratedProductIds = $ratedProductsData['rated_product_ids'] ?? []; // Default to an empty array if key doesn't exist
            if (in_array($id, $ratedProductIds)) {
                $is_rated = true;
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Failed to retrieve rated products',
                'error' => $ratedProductsResponse->json(),
            ], $ratedProductsResponse->status());
        }
        $imageUrls = [];
        $product->image = url("products/images/{$product->image}");
        foreach ($product->gallary as $imageName) {
            $imageUrls[] = url("products/gallary/{$imageName}");
        }
        $product->gallary = $imageUrls;
        $activeOffer = $product->activeOffer();
        if ($activeOffer) {
            $offerPercentage = $activeOffer->persentage;
            $originalPrice = $product->price;
            $discountedPrice = $originalPrice - ($originalPrice * ($offerPercentage / 100));
            $product->original_price = $originalPrice;
            $product->discounted_price = round($discountedPrice, 2);
        } else {
            $product->original_price = $product->price;
            $product->discounted_price = null;
        }

        // Add category, sub-category, and brand names
        $product->category_name = $product->category->name ?? null;
        $product->sub_category_name = $product->subCategory->name ?? null;
        $product->brand_name = $product->brand->name ?? null;
        $product->is_rated = $is_rated;

        // Return the product data as JSON
        return response()->json([
            'status' => true,
            'data' => $product,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }
    function add_to_cart($id)
    {
        $product = Product::find($id);
        return response()->json([
            'status' => true,
            'data' => $product,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }
    
    public function searchAndFilter(Request $request)
    {
           $query = Product::with(['category', 'sub_category', 'brand']); // Eager load relationships
    
        // Your existing filtering logic...
        if ($request->has('sub_category_id') && !empty($request->sub_category_id)) {
            $query->where('sub_category_id', $request->sub_category_id);
        }
    
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function ($q) use ($request) {
                $q->where('product_name', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('ar_product_name', 'LIKE', '%' . $request->search . '%');
            });
        }
    
        if ($request->has('sort') && in_array($request->sort, ['asc', 'desc'])) {
            $query->orderBy('price', $request->sort);
        }
    
        $products = $query->get();
    
        foreach ($products as $product) {
            $imageUrls = [];
            $product->category = $product->category?->category_name;
            $product->sub_category = $product->sub_category?->sub_category_name;
            $product->brand = $product->brand?->brand_name;
            $product->product_unit = $product->product_unit;
            $product->image = url("products/images/{$product->image}");
    
            foreach ($product->gallary as $imageName) {
                $imageUrls[] = url("products/gallary/{$imageName}");
            }
            $product->gallary = $imageUrls;
    
            // Check for active offer
            $activeOffer = $product->activeOffer();
            if ($activeOffer) {
                $offerPercentage = $activeOffer->percentage;
                $originalPrice = $product->price;
                $discountedPrice = $originalPrice - ($originalPrice * ($offerPercentage / 100));
                $product->original_price = $originalPrice;
                $product->discounted_price = $discountedPrice;
            } else {
                $product->original_price = $product->price;
                $product->discounted_price = null;
            }
        }
    
        return response()->json([
            'state' => true,
            'data' => $products,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }
    
    
  public function productSlider()
    {
        // Fetch sliders grouped by sub_category_id
        $sliders = ProductSlider::with('sub_category')
            ->get()
            ->groupBy('sub_category_id'); // Group by sub-category
    
        $sliderData = $sliders->map(function ($slidersGroup, $subCategoryId) {
            // Fetch 4 products from this sub-category
            $products = Product::where('sub_category_id', $subCategoryId)
                ->take(4)
                ->get();
    
            return [
                'sub_category' => [
                    'id' => $subCategoryId,
                    'name' => $slidersGroup->first()->sub_category->ar_sub_category_name ?? '',
                    'image' => url("slider/images/" . $slidersGroup->first()->image), // Get slider image
                ],
                'products' => $products->map(function ($product) {
                    return $this->formatProductData($product); // Format product data using the helper function
                }),
            ];
        })->values(); // Reset array keys
    
        return response()->json([
            'status' => true,
            'data' => $sliderData,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }
    
    private function formatProductData($product)
    {
        $product->image = url("products/images/{$product->image}");
    
        // Format gallery images
        $imageUrls = [];
        foreach ($product->gallary as $imageName) {
            $imageUrls[] = url("products/gallary/{$imageName}");
        }
        $product->gallary = $imageUrls;
    
        // Calculate active offer, if any
        $activeOffer = $product->activeOffer();
        if ($activeOffer) {
            $offerPercentage = $activeOffer->persentage;
            $originalPrice = $product->price;
            $discountedPrice = $originalPrice - ($originalPrice * ($offerPercentage / 100));
            $product->original_price = $originalPrice;
            $product->discounted_price = round($discountedPrice, 2);
        } else {
            $product->original_price = $product->price;
            $product->discounted_price = null;
        }
    
        // Add category, sub-category, and brand names
        $product->category_name = $product->category->name ?? null;
        $product->sub_category_name = $product->subCategory->name ?? null;
        $product->brand_name = $product->brand->name ?? null;
    
        return [
            'id' => $product->id,
            'product_name' => $product->product_name,
            'image' => $product->image,
            'gallary' => $product->gallary,
            'original_price' => $product->original_price,
            'discounted_price' => $product->discounted_price,
            'category_name' => $product->category_name,
            'sub_category_name' => $product->sub_category_name,
            'brand_name' => $product->brand_name,
        ];
    }





}