<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderNotification;
use App\Models\DashboardNotification;
use App\Models\SiteGneral;
use App\Events\OrderCancelled;

class OrderController extends Controller
{
    public function my_orders()
    {
        $client = Auth::user();
        $totalCost = 0;
        $totalDiscount = 0;
        $couponDiscountValue = 0;
        if ($client) {
            $my_orders = Order::where('client_id', $client->id)
                ->get();
                foreach ($my_orders as $order) {
                    $order->tracking_no = 'hs-'.round($order->id + 1000);
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
                    $order->coupon_discount_value = round($order->coupon_discount_value,2);
                    $order->order_final_cost = round($order->order_final_cost,2);
                    $order->delivery_cost = round($order->delivery_cost,2);
                    $order->total_cost = round($order->total_cost,2);
                    $order->total_discount = round($order->total_discount,2);
                    $order->original_cost = round($order->original_cost,2);
                    $order->total_price = round($order->total_price,2);
                }
            return response()->json([
                'status' => true,
                'data' => $my_orders,
                'message' => __('messages.card_load_success'),
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => __('messages.please_login'),
            ], 401);
        }
    }
    public function cansel_order(Request $request)
    {
        $client = Auth::user();
         if ($client) {
        $order = Order::find($request->order_id);
        if ($client->id == $order->client_id) {
            if ($order->status == 'pending') {
                $order->update([
                    'status' => 'canceled',
                ]);
                OrderNotification::create([
                    'client_id' => $client->id,
                    'order_id' => $order->id,
                    'icon' => 'canceled',
                    'title' => __('messages.order_cancellation_confirmation'),
                    'message' => __('messages.order_cancellation_message', ['order_id' => $order->id + 1000]),
                ]);
                DashboardNotification::create([
                    'order_id' => $order->id,
                    'notification_title' => __('messages.dashboard_cancellation_title'),
                    'notification_body' => __('messages.dashboard_cancellation_message', [
                        'client_name' => $order->client->name,
                    ]),
                ]);
    
                broadcast(new OrderCancelled([
                    'client_name' => $order->client->name,
                    'order_id' => $order->id + 1000,
                ]));
    
                return response()->json([
                    'status' => true,
                    'message' => __('messages.order_canceled'),
                ], 200);
            } else {
                return response()->json([
                    'status' => true,
                    'message' => __('messages.order_can\'t_be_canceled'),
                ], 401);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => __('messages.you\'re_not_the_owner'),
            ], 401);
        }
    
        return response()->json([
            'status' => true,
            'data' => $order,
            'message' => __('messages.card_load_success'),
        ], 200);
    } else {
        return response()->json([
            'status' => false,
            'message' => __('messages.please_login'),
        ], 401);
    }

    }
    function reorder(Request $request){
        $order = Order::find($request->order_id);
        $order->update([
            'status'=>'pending'
        ]);
        return response()->json([
            'status'=>true,
            'data'=>$order,
            'message'=>__('messages.order_canceled')
        ],200);
    }
}