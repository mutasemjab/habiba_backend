<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::orderBy('order')->get();

        foreach ($categories as $category) {
            $category->image = url("categories/images/{$category->image}");
            foreach ($category->products as $product) {
                $product->category = $product->category->category_name;
                $product->sub_category = $product->sub_category->sub_category_name;
                $product->brand = $product->brand->brand_name;
                $product->product_unit = $product->product_unit->product_unit;
                $product->image = url("products/images/{$product->image}");
                foreach ($product->gallary as $imageName) {
                    $imageUrls[] = url("products/gallary/{$imageName}");
                }
                $product->gallary = $imageUrls;
            }
        }
        return response()->json([
            'state' => 'true',
            'data' => $categories,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }
}
