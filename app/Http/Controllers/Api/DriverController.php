<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DashboardNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\OrderRefuse;
use App\Events\OrderStatusChanged;
use App\Services\FCMService;


class DriverController extends Controller
{
    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    public function ordersIndex()
    {
        $driver = Auth::user();
        $my_orders = Order::where('driver_id', $driver->id)->get();
        foreach ($my_orders as $order) {
            $totalDiscount = 0;
            $order->userName = $order->client->name;
            $order->tracking_no = 'hs-' . round($order->id + 1000);
            if($order->branch_id){
                $order->pickup_long = $order->branch->branch_long;
                $order->pickup_lat = $order->branch->branch_lat;
            }
            else{
                $order->pickup_long = null;
                $order->pickup_lat =null;
            }
            foreach ($order->orderItems as $item) {
                $product = $item->product;
                $product->image = url("storage/products/images/{$product->image}");
                $product->gallary = $product->gallary ? array_map(function ($imageName) {
                    return url("storage/products/gallary/{$imageName}");
                }, $product->gallary) : [];

                $activeOffer = $product->activeOffer();
                $originalPrice = $product->price;
                $product->original_price = round($originalPrice, 2);
                $product->discounted_price = null;

                if ($activeOffer) {
                    $offerPercentage = $activeOffer->persentage;
                    $discountedPrice = $originalPrice - ($originalPrice * ($offerPercentage / 100));
                    $product->discounted_price = round($discountedPrice, 2);
                    $totalDiscount += ($originalPrice - $discountedPrice) * $item->product_qty;
                }

                $product->price = round($activeOffer ? $discountedPrice : $originalPrice, 2);
                $product->category_name = $product->category->category_name ?? '';
                $product->sub_category_name = $product->sub_category->sub_category_name ?? '';
            }

            $order->coupon_discount_value = round($order->coupon_discount_value, 2);
            $order->order_final_cost = round($order->order_final_cost, 2);
            $order->delivery_cost = round($order->delivery_cost, 2);
            $order->total_cost = round($order->total_cost, 2);
            $order->total_discount = round($totalDiscount, 2);
            $order->original_cost = round($order->original_cost, 2);
            $order->total_price = round($order->total_price, 2);
        }
        
        return response()->json([
            'status' => true,
            'data' => $my_orders,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }
    function accept_delivery(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->update([
            'status' => 'pending_pickup'
        ]);
        $order->coupon_discount_value = round($order->coupon_discount_value,2);
        $order->order_final_cost = round($order->order_final_cost,2);
        $order->delivery_cost = round($order->delivery_cost,2);
        $order->total_cost = round($order->total_cost,2);
        $order->total_discount = round($order->total_discount,2);
        $order->original_cost = round($order->original_cost,2);
        $order->total_price = round($order->total_price,2);
        $order->tracking_no = 'hs-' . round($order->id + 1000);
        broadcast(new OrderStatusChanged([
            'client_name' => $order->client->name, 
            'order_id' => $order->id + 1000, 
            'status' => $order->status, 
        ]));
         $notify = DashboardNotification::create([
        'order_id' => $order->id,
        'notification_title' => __('messages.order_accepted'),
        'notification_body' => __('messages.order_accepted_message', [
            'driver_name' => $order->driver->name,
            'client_name' => $order->client->name,
        ]),
    ]);

        return response()->json([
            'status' => true,
            'data' => $order,
            'message' => __('messages.order_status_updated')
        ], 200);
    }
    
  public function delivery_pickup(Request $request)
{
    $order = Order::find($request->order_id);

    if (!$order) {
        return response()->json([
            'status' => false,
            'message' => __('messages.order_not_found'),
        ], 404);
    }

    $order->update([
        'status' => 'shipped'
    ]);

    $order->coupon_discount_value = round($order->coupon_discount_value, 2);
    $order->order_final_cost = round($order->order_final_cost, 2);
    $order->delivery_cost = round($order->delivery_cost, 2);
    $order->total_cost = round($order->total_cost, 2);
    $order->total_discount = round($order->total_discount, 2);
    $order->original_cost = round($order->original_cost, 2);
    $order->total_price = round($order->total_price, 2);
    $order->tracking_no = 'hs-' . round($order->id + 1000);

    // Broadcast the notification
    broadcast(new OrderStatusChanged([
        'client_name' => $order->client->name,
        'order_id' => $order->id + 1000,
        'status' => $order->status,
    ]));

    // Create a dashboard notification
    DashboardNotification::create([
        'order_id' => $order->id,
        'notification_title' => __('messages.order_shipped'),
        'notification_body' => __('messages.order_on_the_way_message', [
            'driver_name' => optional($order->driver)->name,
            'client_name' => $order->client->name,
        ]),
    ]);

    return response()->json([
        'status' => true,
        'data' => $order,
        'message' => 'تم تحديث البيانات بنجاح',
    ], 200);
}

    
    function near_delivery(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->update([
            'status' => 'ready'
        ]);
        $order->coupon_discount_value = round($order->coupon_discount_value,2);
        $order->order_final_cost = round($order->order_final_cost,2);
        $order->delivery_cost = round($order->delivery_cost,2);
        $order->total_cost = round($order->total_cost,2);
        $order->total_discount = round($order->total_discount,2);
        $order->original_cost = round($order->original_cost,2);
        $order->total_price = round($order->total_price,2);
        $order->tracking_no = 'hs-' . round($order->id + 1000);
        broadcast(new OrderStatusChanged([
            'client_name' => $order->client->name, 
            'order_id' => $order->id + 1000, 
            'status' => $order->status, 
        ]));
          $notify = DashboardNotification::create([
        'order_id' => $order->id,
        'notification_title' => __('messages.order_accepted'),
        'notification_body' => __('messages.order_accepted_message', [
            'driver_name' => $order->driver->name,
            'client_name' => $order->client->name,
        ]),
    ]);
        return response()->json([
            'status' => true,
            'data' => $order,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }
    public function delivery_done(Request $request)
{
    $order = Order::find($request->order_id);

    if (!$order) {
        return response()->json([
            'status' => false,
            'message' => __('messages.order_not_found'),
        ], 404);
    }

    $order->update([
        'status' => 'done'
    ]);

    $driver = Auth::user();
    $driver->update([
        'wallet' => round($driver->wallet, 2) + round($order->total_price, 2) + round($order->delivery_cost, 2)
    ]);

    $order->coupon_discount_value = round($order->coupon_discount_value, 2);
    $order->order_final_cost = round($order->order_final_cost, 2);
    $order->delivery_cost = round($order->delivery_cost, 2);
    $order->total_cost = round($order->total_cost, 2);
    $order->total_discount = round($order->total_discount, 2);
    $order->original_cost = round($order->original_cost, 2);
    $order->total_price = round($order->total_price, 2);
    $order->tracking_no = 'hs-' . round($order->id + 1000);

    // Broadcast the notification
    broadcast(new OrderStatusChanged([
        'client_name' => $order->client->name,
        'order_id' => $order->id + 1000,
        'status' => $order->status,
    ]));

    // Create a dashboard notification
    DashboardNotification::create([
        'order_id' => $order->id,
        'notification_title' => __('messages.order_delivered'),
        'notification_body' => __('messages.order_delivered_message', [
            'driver_name' => optional($order->driver)->name,
            'client_name' => $order->client->name,
        ]),
    ]);

    return response()->json([
        'status' => true,
        'data' => $order,
        'message' => 'تم تحميل البيانات بنجاح',
    ], 200);
}

    function refuse_delivery(Request $request)
    {
        $driver = Auth::user();
        $order = Order::find($request->order_id);
        $order->update([
            'status' => 'pending_driver',
            'driver_id' => null
        ]);
        $order->coupon_discount_value = round($order->coupon_discount_value,2);
        $order->order_final_cost = round($order->order_final_cost,2);
        $order->delivery_cost = round($order->delivery_cost,2);
        $order->total_cost = round($order->total_cost,2);
        $order->total_discount = round($order->total_discount,2);
        $order->original_cost = round($order->original_cost,2);
        $order->total_price = round($order->total_price,2);
        $order->tracking_no = 'hs-' . round($order->id + 1000);
        broadcast(new OrderRefuse([
            'driver_name' => $driver->name, 
            'client_name' => $order->client->name, 
            'order_id' => $order->id + 1000, 
        ]));
       $notify = DashboardNotification::create([
            'order_id' => $order->id,
            'notification_title' => __('messages.delivery_rejected_title'),
            'notification_body' => __('messages.delivery_rejected_message', [
                'driver_name' => $driver->name,
                'client_name' => $order->client->name,
            ]),
        ]);
        return response()->json([
            'status' => true,
            'data' => $order,
            'message' => 'تم تحميل البيانات بنجاح',
        ], 200);
    }
    function get_wallet()
    {
        return response()->json([
            'status' => true,
            'data' => round(Auth::user()->wallet,2),
            'message' => 'تم تحميل البيانات بنجاح'
        ]);
    }
}