<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use App\Models\SliderImage;
use App\Models\SubCategory;
use App\Models\Order;
use App\Models\AccountDeleteRequest;
use App\Models\SiteGneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $bearerToken = request()->bearerToken();
        $favListResponse = Http::withToken($bearerToken)->get(route('client.favlist'));
        $favoriteProductIds = $favListResponse->json() ?? [];
        $products = Product::with(['category', 'sub_category', 'brand', 'productRates'])
            ->where('product_status', '1')
            ->get()
            ->map(function ($product) use ($favoriteProductIds) {
                $productData = [
                    'id' => $product->id,
                    'product_name' => $product->product_name,
                    'category_name' => $product->category->category_name ?? null,
                    'sub_category_name' => $product->sub_category->sub_category_name ?? null,
                    'brand_name' => $product->brand->brand_name ?? null,
                    'image' => url("products/images/{$product->image}"),
                    'gallary' => collect($product->gallary)->map(fn($imageName) => url("products/gallary/{$imageName}"))->toArray(),
                    'is_favorite' => in_array($product->id, $favoriteProductIds),
                    'average_rate' => $product->getAverageRateAttribute(),
                ];

                // Pricing: original and discounted prices
                $originalPrice = $product->price;
                $activeOffer = $product->activeOffer();
                $productData['original_price'] = round($originalPrice, 2);
                $productData['discounted_price'] = null;

                if ($activeOffer) {
                    $offerPercentage = $activeOffer->persentage ?? 0;
                    $discountedPrice = $originalPrice - ($originalPrice * ($offerPercentage / 100));
                    $productData['discounted_price'] = round($discountedPrice, 2);
                    $productData['offer_percentage'] = $offerPercentage;
                }

                return $productData;
            });

        return response()->json([
            'status' => true,
            'data' => $products,
            'message' => 'المنتجات تم تحميلها بنجاح',
        ], 200);
    }

    function slider()
    {
        $images = SliderImage::orderBy('order', 'asc')->get(); // Order by 'order' column ascending
        
        // Map images to include only URLs
        $imageUrls = $images->map(function ($image) {
            return url("slider/images/{$image->image}");
        });
        
        return response()->json([
            'status' => true,
            'images' => $imageUrls,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }

    public function products()
    {
        $products = Product::all();
        foreach ($products as $product) {
            $imageUrls = [];
            $product->category = $product->category->category_name;
            $product->sub_category = $product->sub_category->sub_category_name;
           $product->brand = $product->brand ? $product->brand->brand_name : null;

            $product->product_unit = $product->product_unit->product_unit;
            $product->image = url("products/images/{$product->image}");
            foreach ($product->gallary as $imageName) {
                $imageUrls[] = url("products/gallary/{$imageName}");
            }
            $product->gallary = $imageUrls;
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
            'message' => 'المنتجات تم تحميلها بنجاح',
        ], 200);
    }
    public function categories()
    {
       $categories = Category::orderBy('order')->get();

        foreach ($categories as $cat) {
            $cat->image = url("categories/images/{$cat->image}");
        }
        return response()->json([
            'status' => true,
            'categories' => $categories,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }
    public function category_sub_categories($id)
    {
        $category = Category::find($id);
        foreach ($category->sub_categories as $cat) {
            $cat->image = url("sub_categories/images/{$cat->image}");
        }
        return response()->json([
            'status' => true,
            'data' => $category->sub_categories,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }
    public function sub_categories()
    {
        $sub_categories = SubCategory::all();
        foreach ($sub_categories as $cat) {
            $cat->parent_category = $cat->category->category_name;
            $cat->image = url("sub_categories/images/{$cat->image}");
        }
        return response()->json([
            'status' => true,
            'categories' => $sub_categories,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }
    public function sub_categories_products($id)
    {
        $bearerToken = request()->bearerToken();
        $favListResponse = Http::withToken($bearerToken)->get(route('client.favlist'));
        $favoriteProductIds = $favListResponse->json() ?? [];
        $sub_category = SubCategory::find($id);
        foreach ($sub_category->products as $product) {
            $imageUrls = [];
            $product->category = $product->category->category_name;
            $product->sub_category = $product->sub_category->sub_category_name;
            $product->brand = $product->brand ? $product->brand->brand_name : null;

            $product->image = url("products/images/{$product->image}");
            foreach ($product->gallary as $imageName) {
                $imageUrls[] = url("products/gallary/{$imageName}");
            }
            $product->gallary = $imageUrls;
            $product->is_favorite = in_array($product->id, $favoriteProductIds) ?? false;
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
            'status' => true,
            'data' => $sub_category->products,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }
    public function categories_products($id)
    {
        $bearerToken = request()->bearerToken();
        $favListResponse = Http::withToken($bearerToken)->get(route('client.favlist'));
        $favoriteProductIds = $favListResponse->json() ?? [];
        $category = Category::find($id);
        foreach ($category->products as $product) {
            $imageUrls = [];
            $product->category = $product->category->category_name;
            $product->brand = $product->brand ? $product->brand->brand_name : null;

            $product->image = url("products/images/{$product->image}");
            foreach ($product->gallary as $imageName) {
                $imageUrls[] = url("products/gallary/{$imageName}");
            }
            $product->gallary = $imageUrls;
            $product->is_favorite = in_array($product->id, $favoriteProductIds) ?? false;
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
            'status' => true,
            'data' => $category->products,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }
    public function offers()
    {
        $offers = Offer::all();
        return response()->json([
            'state' => true,
            'data' => $offers,
            'message' => 'المنتجات تم تحميلها بنجاح',
        ], 200);
    }
    public function add_address(Request $request)
    {
        $client = Auth::user();
        $address = Address::create([
            'client_id' => $client->id,
            'address_title' => $request->address_title,
            'mark' => $request->mark,
            'long' => $request->long,
            'lat' => $request->lat,
        ]);
        return response()->json([
            'status' => true,
            'data' => $address,
        ], 200);
    }
    public function remove_address(Request $request)
    {
        $client = Auth::user();
        $address = Address::find($request->address_id);
        $address->delete();
        $addresses = Address::where('client_id', Auth::user()->id)->get();
        return response()->json([
            'status' => true,
            'data' => $addresses,
            'message' => 'تم حذف العنوان بنجاح',
        ], 200);
    }
    public function addresses()
    {
        $addresses = Address::where('client_id', Auth::user()->id)->get();
        return response()->json([
            'state' => true,
            'data' => $addresses,
            'message' => 'قائمة العناوين تم تحميلها بنجاح',
        ], 200);
    }
    function delete_account(Request $request)
    {
        $user = Auth::user();
        // Order::where('client_id', $user->id)->delete();
        // if ($user->cart) {
        //     $user->cart->cartItems()->delete();
        //     $user->cart->delete();
        // }
        // Address::where('user_id', $user->id)->delete();
        AccountDeleteRequest::create([
            'user_name'=>$user->name,
            'mobile'=>$user->mobile,
            'comment'=>$request->comment,
        ]);
        $user->update([
            'status'=>false
        ]);

        return response()->json([
            'status' => true,
            'message' => __('Account deleted successfully'),
        ], 200);
    }
    function generals_index(){
        $siteGenerals = SiteGneral::first();
        return response()->json([
            'status' => true,
            'data' => $siteGenerals,
            'message' => __('Site general settings loaded successfully')
        ], 200);
    }
}
