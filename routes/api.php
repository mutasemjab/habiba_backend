<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\DriverController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductRateController;
use App\Http\Controllers\AppRateController;
use App\Http\Controllers\ContactUsController;
use App\Http\Controllers\DriverNotificationController;
use App\Models\DriverNotification;
use App\Models\Favorite;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;



// Guest APIs
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('login/otp/verify', [AuthController::class, 'verifyUserOtp'])->name('login.verify');
 Route::post('/products/search', [ProductController::class, 'searchAndFilter']);

Route::middleware(['site.status'])->group(function () {
Route::get('sub_categories', [HomeController::class, 'sub_categories'])->name('sub_categories');
Route::get('categories', [HomeController::class, 'categories'])->name('categories');
Route::get('offers', [HomeController::class, 'offers'])->name('offers');
Route::get('product/{id}', [ProductController::class, 'show'])->name('product');
Route::get('home', [HomeController::class, 'index'])->name('home');
Route::get('sub_categories/{id}', [HomeController::class, 'category_sub_categories'])->name('category_sub_categories');
Route::get('sub_categories/{id}/products', [HomeController::class, 'sub_categories_products'])->name('sub_categories_products');
Route::get('categories/{id}/products', [HomeController::class, 'categories_products'])->name('categories_products');
Route::get('slider', [HomeController::class, 'slider'])->name('slider');
Route::get('/site-generals', [HomeController::class, 'generals_index']);
Route::post('/contact_us/create', [ContactUsController::class, 'create']);
Route::get('/products/slider', [ProductController::class, 'productSlider']);

Route::group(['middleware' => ['auth:sanctum'], 'prefix' => 'client'], function () {
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [AuthController::class, 'profile_update'])->name('profile.update');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/cart/index', [CartController::class, 'my_cart'])->name('my_cart');
    Route::post('/cart/add/product', [CartController::class, 'add_to_cart'])->name('cart.add.product');
    Route::post('/cart/apply_coupone', [CartController::class, 'apply_coupone'])->name('cart.apply_coupone');
    Route::post('/cart/remove/product', [CartController::class, 'deleteFromCart'])->name('cart.remove.product');
    Route::post('/cart/update/product', [CartController::class, 'update_product_qty'])->name('cart.update.product');
    Route::post('/place_order', [CartController::class, 'placeOrder'])->name('placeOrder');
    Route::post('/add/address', [HomeController::class, 'add_address'])->name('add.addresses');
    Route::post('/remove/address', [HomeController::class, 'remove_address'])->name('add.addresses');
    Route::get('/addresses', [HomeController::class, 'addresses'])->name('my.addresses');
    Route::get('/orders/index', [OrderController::class, 'my_orders'])->name('my.orders');
    Route::post('/cansel_order', [OrderController::class, 'cansel_order'])->name('my.orders');
    Route::get('/my/favorite', [FavoriteController::class, 'index'])->name('my.favorite');
    Route::post('/toggle/favorite', [FavoriteController::class, 'toggle'])->name('toggle.fav');
    Route::post('/products/rate', [ProductRateController::class, 'rateProduct']);
    Route::post('/delete/account', [HomeController::class, 'delete_account']);
    Route::post('/orders/reorder', [OrderController::class, 'reorder']);

    Route::get('/favlist', function () {
        return Favorite::where('client_id', Auth::user()->id)->pluck('product_id')->toArray();
    })->name('client.favlist');
    Route::get('/rated_products', function (Request $request) {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }
        $ratedProductIds = $user->ratings->pluck('product_id')->toArray();
        return response()->json([
            'rated_product_ids' => $ratedProductIds,
        ], 200);
    })->name('client.rated_products');
    Route::post('/app/rate/create', [AppRateController::class, 'create']);
    Route::get('/notifications', function () {
        $notifications = OrderNotification::where('is_read', false)->where('client_id', Auth::user()->id)->get()->map(function ($notify) {
            $notify['notification_time'] = Carbon::parse($notify->created_at)->diffForHumans();
            return $notify;
        });
        return response()->json([
            'status' => true,
            'data' => $notifications,
            'message' => 'تم تحميل بيانات الاشعارات'
        ], 200);
    })->name('my.notifications');
    Route::post('/notifications/read', function () {
        $notifications = OrderNotification::where('is_read', false)->where('client_id', Auth::user()->id)->get();
        foreach ($notifications as $notification) {
            $notification->update([
                'is_read' => true
            ]);
        }
        return response()->json([
            'status' => true,
            'data' => $notifications,
            'message' => 'تم قراءة جميع الاشعارات'
        ], 200);
    })->name('my.notifications.read');
    Route::get('/order/details/{id}', function ($id) {
        $client = Auth::user();
        $totalCost = 0;
        $totalDiscount = 0;
        $couponDiscountValue = 0;
        $order = Order::find($id);
        $order->client;
        $order->tracking_no = 'hs-' . round($order->id + 1000);
        foreach ($order->orderItems as $item) {
            $item->product->image = url("products/images/{$item->product->image}");
            $imageUrls = array_map(function ($imageName) {
                return url("products/gallary/{$imageName}");
            }, $item->product->gallary);
            $item->product->gallary = $imageUrls;
            $activeOffer = $item->product->activeOffer();
            $originalPrice = $item->product->price;
            $itemPrice = $originalPrice;
            $item->product->original_price = round($originalPrice, 2);
            $item->product->discounted_price = null;
            if ($activeOffer) {
                $offerPercentage = $activeOffer->persentage;
                $discountedPrice = $originalPrice - ($originalPrice * ($offerPercentage / 100));
                $itemPrice = $discountedPrice;
                $discountAmount = $originalPrice - $discountedPrice;
                $totalDiscount += ($discountAmount * $item->product_qty);
                $item->product->discounted_price = round($discountedPrice, 2);
            }
            $itemTotalPrice = $item->product_qty * $itemPrice;
            $item->product['price'] = round($itemPrice, 2);
            $item->product->category_name = $item->product->category->category_name;
            $item->product->sub_category_name = $item->product->sub_category->sub_category_name;
        }
        $order->coupon_discount_value = round($order->coupon_discount_value, 2);
        $order->order_final_cost = round($order->order_final_cost, 2);
        $order->delivery_cost = round($order->delivery_cost, 2);
        $order->total_cost = round($order->total_cost, 2);
        $order->total_discount = round($order->total_discount, 2);
        $order->original_cost = round($order->original_cost, 2);
        $order->total_price = round($order->total_price, 2);
        return response()->json([
            'status' => true,
            'data' => $order,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    })->name('order.details');
});
});






Route::group(['prefix' => 'driver'], function () {
    Route::post('login', [AuthController::class, 'driverLogin'])->name('login');
    Route::post('login/otp/verify', [AuthController::class, 'verifyDriverOtp'])->name('login.verify');
    Route::group(['middleware' => ['auth:sanctum']], function () {
        Route::get('/orders/index', [DriverController::class, 'ordersIndex']);
        Route::get('/orders/index', [DriverController::class, 'ordersIndex']);
        Route::post('/order/accept', [DriverController::class, 'accept_delivery']);
        Route::post('/order/pick_up', [DriverController::class, 'delivery_pickup']);
        Route::post('/order/refuse', [DriverController::class, 'refuse_delivery']);
        Route::post('/order/ready', [DriverController::class, 'near_delivery']);
        Route::post('/order/done', [DriverController::class, 'delivery_done']);
        Route::get('/wallet', [DriverController::class, 'get_wallet']);
        Route::post('/logout', [AuthController::class, 'driver_logout'])->name('logout');
        Route::get('/notifications', function () {
            $notifications = DriverNotification::where('driver_id', Auth::user()->id)->where('is_read', false)->get()->map(function ($notify) {
                $notify['notification_time'] = Carbon::parse($notify->created_at)->diffForHumans();
                return $notify;
            });
            return response()->json([
                'status' => true,
                'data' => $notifications,
                'message' => 'تم تحميل بيانات الاشعارات'
            ], 200);
        })->name('driver.notifications');
        Route::post('/notifications/read', function () {
            $notifications = DriverNotification::where('is_read', false)->where('driver_id', Auth::user()->id)->get();

            foreach ($notifications as $notification) {
                $notification->update([
                    'is_read' => true
                ]);
            }
            return response()->json([
                'status' => true,
                'data' => $notifications,
                'message' => 'تم قراءة جميع الاشعارات'
            ], 200);
        })->name('my.notifications.read');
        Route::get('/order/details/{id}', function ($id) {
            $totalCost = 0;
            $totalDiscount = 0;
            $couponDiscountValue = 0;
            $order = Order::find($id);
            $order->client;
            $order->tracking_no = 'hs-' . round($order->id + 1000);
            foreach ($order->orderItems as $item) {
                $item->product->image = url("products/images/{$item->product->image}");
                $imageUrls = array_map(function ($imageName) {
                    return url("products/gallary/{$imageName}");
                }, $item->product->gallary);
                $item->product->gallary = $imageUrls;
                $activeOffer = $item->product->activeOffer();
                $originalPrice = $item->product->price;
                $itemPrice = $originalPrice;
                $item->product->original_price = round($originalPrice, 2);
                $item->product->discounted_price = null;
                if ($activeOffer) {
                    $offerPercentage = $activeOffer->persentage;
                    $discountedPrice = $originalPrice - ($originalPrice * ($offerPercentage / 100));
                    $itemPrice = $discountedPrice;
                    $discountAmount = $originalPrice - $discountedPrice;
                    $totalDiscount += ($discountAmount * $item->product_qty);
                    $item->product->discounted_price = round($discountedPrice, 2);
                }
                $itemTotalPrice = $item->product_qty * $itemPrice;
                $item->product['price'] = round($itemPrice, 2);
                $item->product->category_name = $item->product->category->category_name;
                $item->product->sub_category_name = $item->product->sub_category->sub_category_name;
            }
            $order->coupon_discount_value = round($order->coupon_discount_value, 2);
            $order->order_final_cost = round($order->order_final_cost, 2);
            $order->delivery_cost = round($order->delivery_cost, 2);
            $order->total_cost = round($order->total_cost, 2);
            $order->total_discount = round($order->total_discount, 2);
            $order->original_cost = round($order->original_cost, 2);
            $order->total_price = round($order->total_price, 2);
            $order->pickup_lat = $order->branch->branch_lat;
            $order->pickup_long =$order->branch->branch_long;
            $order->branch =$order->branch->branch_title;
            return response()->json([
                'status' => true,
                'data' => $order,
                'message' => 'تم تحميل البيانات بنجاح',
            ], 200);
        })->name('order.details');
    });
});
