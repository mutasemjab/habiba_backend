<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\SiteGneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Events\OrderCreated;
use App\Models\OrderNotification;
use App\Models\DashboardNotification;



class CartController extends Controller
{
    public function my_cart()
    {
        $delivery_cost = SiteGneral::first()->delivery_price ?? 0;
        $user = Auth::user();
        $totalCost = 0;
        $totalDiscount = 0;
        $couponDiscountValue = 0;
        if ($user->cart && $user->cart->cartItems->count() > 0) {
            $cartItems = $user->cart->cartItems;
            $user->cart->delivery_cost = $delivery_cost;
            foreach ($cartItems as $item) {
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
                $totalCost += $itemTotalPrice;
                $item->product['price'] = round($itemPrice, 2);
                $item->product->category_name = $item->product->category->category_name;
                $item->product->sub_category_name = $item->product->sub_category->sub_category_name ?? null;
            }

            $user->cart['total_cost'] = round($totalCost, 2);

            $user->cart['total_discount'] = round($totalDiscount, 2);
            $user->cart['original_cost'] = $user->cart['total_cost'] + $user->cart['total_discount'];
            if ($user->cart->coupon_id) {
                $coupon = Coupon::find($user->cart->coupon_id);
                if ($coupon && !$coupon->isExpired()) {
                    $couponDiscountValue = intval(($user->cart['total_cost'] * $coupon->persentage) / 100);
                    $finalCost = $user->cart['total_cost'] - $couponDiscountValue;
                    $user->cart['coupon_discount_value'] = $couponDiscountValue;
                } else {
                    $user->cart->coupon_id = null;
                    $user->cart['coupon_discount_value'] = 0;
                }
            } else {
                $user->cart['coupon_discount_value'] = 0;
            }

            $user->cart->update([
                'total_cost' => $user->cart['total_cost'],
                'total_discount' => $user->cart['total_discount'],
                'original_cost' => $user->cart['original_cost'],
                'coupon_discount_value' => $user->cart['coupon_discount_value'] ?? 0,
                'cart_final_cost' => $user->cart['total_cost'] - $couponDiscountValue ?? 0,
            ]);

            return response()->json([
                'status' => true,
                'data' => $user->cart,
                'message' => __('messages.cart_load_success')
            ], 200);
        } elseif ($user->cart && $user->cart->cartItems->count() <= 0) {
            return response()->json([
                'status' => true,
                'data' => null,
                'message' => __('messages.shopping_cart_must_have_at_least_one_item')
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'data' => null,
                'message' => __('messages.cart_not_found')
            ], 200);
        }
    }
    public function add_to_cart(Request $request)
    {
        $user = Auth::user();
        $product_id = $request->product_id;
        $product_qty = $request->product_qty;
        $product = Product::find($product_id);
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }
        $userCart = Cart::where('client_id', $user->id)->first();
        if ($userCart) {
            $cartItem = $userCart->cartItems()->where('product_id', $product_id)->first();
            if ($cartItem) {
                $new_qty = $cartItem->product_qty + $product_qty;
                $cartItem->update([
                    'product_qty' => $new_qty
                ]);
            } else {
                CartItem::create([
                    'cart_id' => $userCart->id,
                    'product_id' => $product_id,
                    'product_qty' => $product_qty
                ]);
            }
            $userCart->load('cartItems.product');
        } else {
            $userCart = Cart::create([
                'client_id' => $user->id
            ]);
            CartItem::create([
                'cart_id' => $userCart->id,
                'product_id' => $product_id,
                'product_qty' => $product_qty
            ]);
            $userCart->load('cartItems.product');
        }
        foreach ($userCart->cartItems as $item) {
            $item->product->image = url("products/images/{$item->product->image}");

            $imageUrls = [];
            foreach ($item->product->gallary as $imageName) {
                $imageUrls[] = url("products/gallary/{$imageName}");
            }
            $item->product->gallary = $imageUrls;
        }
        $userCart->total_cost = intval($userCart->total_cost);
        $userCart->total_discount = intval($userCart->total_discount);
        $userCart->original_cost = intval($userCart->original_cost);
        $userCart->coupon_discount_value = intval($userCart->coupon_discount_value);
        $userCart->cart_final_cost = intval($userCart->cart_final_cost);
        $userCart->delivery_cost = intval($userCart->delivery_cost);
        return response()->json([
            'status' => true,
            'data' => $userCart,
            'message' => __('messages.cart_product_added')
        ], 200);
    }
    public function deleteFromCart(Request $request)
    {
        $user = Auth::user();
        $product_id = $request->product_id;
        $userCart = Cart::where('client_id', $user->id)->first();
        if (!$userCart) {
            return response()->json([
                'status' => false,
                'message' => __('messages.cart_not_found')
            ], 404);
        }
        $cartItem = $userCart->cartItems()->where('product_id', $product_id)->first();
        if (!$cartItem) {
            return response()->json([
                'status' => false,
                'message' => __('messages.cart_product_not_found')
            ], 404);
        }
        $cartItem->delete();
        $userCart->save();
        $userCart->load('cartItems.product');
        return response()->json([
            'status' => true,
            'data' => $userCart,
            'message' => __('messages.cart_product_remove'),
        ], 200);
    }
   
    public function placeOrder(Request $request)
    {
        $user = Auth::user();
        
        // Add validation for the photo
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'photo_payment' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);
        
        // Fetch the minimum order value from SiteGeneral
        $minOrderValue = SiteGneral::value('min_order');

        // Check if the total order cost meets the minimum order requirement
        $totalOrderPrice = $user->cart->cart_final_cost;
        if ($totalOrderPrice < $minOrderValue) {
            return response()->json([
                'status' => false,
                'message' => __('messages.minimum_order_not_met', ['min_order' => $minOrderValue]),
            ], 400);
        }

        $address = Address::find($request['address_id']);
        
        // Handle photo upload
        $photoPaymentPath = null;
        if ($request->hasFile('photo_payment')) {
            $photo = $request->file('photo_payment');
            $filename = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            
            // Store the photo in storage/app/public/payment_photos directory
            $photoPaymentPath = $photo->storeAs('payment_photos', $filename, 'public');
            
            // Alternative: If you want to store in public/payment_photos directory
            // $photo->move(public_path('payment_photos'), $filename);
            // $photoPaymentPath = 'payment_photos/' . $filename;
        }
        
        foreach ($user->cart->cartItems as $item) {
            $item->product->image = url("products/images/{$item->product->image}");
            $imageUrls = [];
            foreach ($item->product->gallary as $imageName) {
                $imageUrls[] = url("products/gallary/{$imageName}");
            }
            $item->product->gallary = $imageUrls;
            $activeOffer = $item->product->activeOffer();
            if ($activeOffer) {
                $originalPrice = $item->product->price;
                $discountedPrice = $originalPrice - ($originalPrice * ($item->product->offer_percentage / 100));
                $item_price = $discountedPrice * $item->product_qty;
                $item->product->original_price = $originalPrice;
                $item->product->discounted_price = $discountedPrice;
            } else {
                $item_price = $item->product->price * $item->product_qty;
                $item->product->original_price = $item->product->price;
                $item->product->discounted_price = null;
            }
        }
        
        $order = Order::create([
            'client_id' => $user->id,
            'coupon_id' => $user->cart->coupon_id ?? null,
            'coupon_discount_value' => $user->cart->coupon_discount_value,
            'order_final_cost' => $user->cart->cart_final_cost,
            'total_price' => $user->cart->cart_final_cost,
            'delivery_cost' => $user->cart->delivery_cost,
            'total_cost' => $user->cart->total_cost,
            'original_cost' => $user->cart->original_cost,
            'total_discount' => $user->cart->total_discount,
            'lat' => $address->lat,
            'long' => $address->long,
            'address_mark' => $address->mark,
            'address_title' => $address->title,
            'photo_payment' => $photoPaymentPath, // Add the photo path here
        ]);
        
        foreach ($user->cart->cartItems as $item) {
            $product = Product::find($item->product_id);
            if ($product->activeOffer()) {
                $originalPrice = $product->price;
                $discountedPrice = $originalPrice - ($originalPrice * ($product->offer_percentage / 100));
                $product_price = $discountedPrice;
            } else {
                $product_price = $product->price;
            }
            $order->orderItems()->create([
                'product_id' => $item->product_id,
                'product_qty' => $item->product_qty,
                'price_at_time' => $product_price,
            ]);
        }
        
        $notify = DashboardNotification::create([
            'order_id' => $order->id,
            'notification_title' => 'طلب جديد',
            'notification_body' => 'قام العميل ' . $order->client->name . ' بعمل طلب جديد',
        ]);
        
        broadcast(new OrderCreated($order))->toOthers();
        $user->cart->delete();
        
        return response()->json([
            'status' => true,
            'data' => $order->id + 1000,
            'message' => __('messages.order_placed'),
        ], 200);
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = $user->orders;
        if ($orders->isNotEmpty()) {
            foreach ($orders as $order) {
                $order->items_count = $order->orderItems->count();
                foreach ($order->orderItems as $item) {
                    $item->product->image = url("products/images/{$item->product->image}");
                    $imageUrls = [];
                    foreach ($item->product->gallary as $imageName) {
                        $imageUrls[] = url("products/gallary/{$imageName}");
                    }
                    $item->product->gallary = $imageUrls;
                }

                $order['order_items'] = $order->orderItems;
            }

            return response()->json([
                'status' => true,
                'data' => $orders
            ], 200);
        } else {
            return response()->json([
                'status' => true,
                'data' => []
            ], 200);
        }
    }
    public function update_product_qty(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty' => 'required|integer|min:1',
        ]);
        $product_qty = $request->qty;
        $product_id = $request->product_id;
        $product_id = $request->product_id;
        $userCart = Cart::where('client_id', $user->id)->first();
        if (!$userCart) {
            return response()->json([
                'status' => false,
                'message' => 'Cart not found'
            ], 404);
        }
        $cartItem = $userCart->cartItems()->where('product_id', $product_id)->first();
        if (!$cartItem) {
            return response()->json([
                'status' => false,
                'message' => __('messages.cart_product_not_found')
            ], 404);
        }
        $cartItem->update([
            'product_qty' => $product_qty
        ]);
        $userCart->load('cartItems.product');
        foreach ($userCart->cartItems as $item) {
            $item->product->image = url("products/images/{$item->product->image}");

            $imageUrls = [];
            foreach ($item->product->gallary as $imageName) {
                $imageUrls[] = url("products/gallary/{$imageName}");
            }
            $item->product->gallary = $imageUrls;
        }

        return response()->json([
            'status' => true,
            'data' => $userCart,
            'message' => __('messages.cart_product_qty_adjusted')
        ], 200);
    }
    public function apply_coupone(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required|string|exists:coupons,code',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }
        $coupon = Coupon::where('code', $request->coupon_code)->first();
        if (!$coupon || $coupon->isExpired() || $coupon->isUsedBy($user)) {
            return response()->json([
                'status' => false,
                'message' => __('messages.cart_coupon_invalid'),
            ], 400);
        }
        $cart = $user->cart;
        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => __('messages.cart_not_found'),
            ], 404);
        }
        $discountValue = intval(($cart->total_cost * $coupon->persentage) / 100);
        if ($discountValue > $cart->total_cost) {
            return response()->json([
                'status' => false,
                'message' => __('messages.discount_exceds_total_cost'),
            ], 400);
        }
        DB::transaction(function () use ($cart, $coupon, $discountValue, $user) {
            $cart->total_discount = $discountValue;
            $cart->coupon_id = $coupon->id;
            $cart->cart_final_cost = round($cart->total_cost - $discountValue);
            $cart->coupon_discount_value = $discountValue;
            $cart->save();
            DB::table('coupon_user')->insert([
                'client_id' => $user->id,
                'coupon_id' => $coupon->id,
            ]);
        });

        return response()->json([
            'status' => true,
            'message' => __('messages.coupon_applied_success'),
            'data' => [
                'total_cost' => round($cart->total_cost),
                'discount_value' => round($discountValue),
                'final_cost' => round($cart->cart_final_cost),
            ],
        ], 200);
    }
}
