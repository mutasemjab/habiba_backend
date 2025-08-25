<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Driver;
use App\Models\DriverNotification;
use App\Models\UserFCMTokens;
use App\Models\OrderNotification;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FCMService;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    /**
     * Update delivery cost for an order
     */
    public function updateDeliveryCost(Request $request, $orderId)
    {
        try {
            DB::beginTransaction();
            
            $request->validate([
                'delivery_cost' => 'required|numeric|min:0'
            ]);

            $order = Order::findOrFail($orderId);
            
            // Calculate the difference in delivery cost
            $oldDeliveryCost = $order->delivery_cost ?? 0;
            $newDeliveryCost = $request->delivery_cost;
            $difference = $newDeliveryCost - $oldDeliveryCost;
            
            // Update order costs
            $order->update([
                'delivery_cost' => $newDeliveryCost,
                'order_final_cost' => $order->order_final_cost + $difference,
                'total_cost' => $order->total_cost + $difference
            ]);

            DB::commit();
            return redirect()->back()->with('success', __('messages.delivery_cost_updated'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Add product to existing order
     */
   public function addProductToOrder(Request $request, $orderId)
{
    try {
        DB::beginTransaction();
        
        // Log the incoming request
        \Log::info('addProductToOrder started', [
            'order_id' => $orderId,
            'request_data' => $request->all(),
            'method' => $request->method(),
            'url' => $request->url()
        ]);
        
        // Validate request
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);
        
        \Log::info('Validation passed', [
            'product_id' => $request->product_id,
            'quantity' => $request->quantity
        ]);

        // Find order
        $order = Order::findOrFail($orderId);
        \Log::info('Order found', [
            'order_id' => $order->id,
            'order_status' => $order->status,
            'current_items_count' => $order->orderItems()->count()
        ]);
        
        // Find product
        $product = Product::findOrFail($request->product_id);
        \Log::info('Product found', [
            'product_id' => $product->id,
            'product_name' => $product->product_name,
            'product_price' => $product->price,
            'product_status' => $product->product_status
        ]);
        
        // Calculate price
        $productPrice = $product->price;
        \Log::info('Product price calculated', [
            'original_price' => $product->price,
            'final_price' => $productPrice
        ]);

        // Check if product already exists in order
        $existingItem = $order->orderItems()->where('product_id', $request->product_id)->first();
        
        if ($existingItem) {
            \Log::info('Product already exists in order, updating quantity', [
                'existing_item_id' => $existingItem->id,
                'old_quantity' => $existingItem->product_qty,
                'adding_quantity' => $request->quantity,
                'new_quantity' => $existingItem->product_qty + $request->quantity
            ]);
            
            // Update quantity if product already exists
            $result = $existingItem->update([
                'product_qty' => $existingItem->product_qty + $request->quantity
            ]);
            
            \Log::info('Existing item update result', [
                'update_success' => $result,
                'updated_item' => $existingItem->fresh()->toArray()
            ]);
            
        } else {
            \Log::info('Adding new product to order', [
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price_at_time' => $productPrice
            ]);
            
            // Add new item to order
            $newItem = $order->orderItems()->create([
                'product_id' => $request->product_id,
                'product_qty' => $request->quantity,
                'price_at_time' => $productPrice
            ]);
            
            \Log::info('New item created', [
                'new_item_id' => $newItem->id,
                'new_item_data' => $newItem->toArray()
            ]);
        }

        // Check current order items after addition
        $currentItems = $order->fresh()->orderItems;
        \Log::info('Current order items after addition', [
            'total_items' => $currentItems->count(),
            'items' => $currentItems->map(function($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->product_qty,
                    'price' => $item->price_at_time
                ];
            })->toArray()
        ]);

        // Update order totals
        \Log::info('Recalculating order totals');
        $this->recalculateOrderTotals($order);
        
        // Log final order state
        $finalOrder = $order->fresh();
        \Log::info('Final order state', [
            'total_price' => $finalOrder->total_price,
            'total_cost' => $finalOrder->total_cost,
            'order_final_cost' => $finalOrder->order_final_cost,
            'delivery_cost' => $finalOrder->delivery_cost
        ]);

        DB::commit();
        \Log::info('Transaction committed successfully');
        
        return redirect()->back()->with('success', __('messages.product_added_to_order'));
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        \Log::error('Validation failed in addProductToOrder', [
            'errors' => $e->errors(),
            'request_data' => $request->all()
        ]);
        return redirect()->back()->withErrors($e->errors())->withInput();
        
    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        DB::rollBack();
        \Log::error('Model not found in addProductToOrder', [
            'exception' => $e->getMessage(),
            'model' => $e->getModel(),
            'order_id' => $orderId,
            'request_data' => $request->all()
        ]);
        return redirect()->back()->with('error', 'Order or Product not found: ' . $e->getMessage());
        
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Exception in addProductToOrder', [
            'exception_message' => $e->getMessage(),
            'exception_trace' => $e->getTraceAsString(),
            'order_id' => $orderId,
            'request_data' => $request->all(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
        return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
    }
}

    /**
     * Update order item quantity
     */
    public function updateOrderItem(Request $request, $orderId, $itemId)
    {
        try {
            DB::beginTransaction();
            
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $order = Order::findOrFail($orderId);
            $item = $order->orderItems()->findOrFail($itemId);
            
            $item->update([
                'product_qty' => $request->quantity
            ]);

            // Recalculate order totals
            $this->recalculateOrderTotals($order);

            DB::commit();
            return redirect()->back()->with('success', __('messages.item_updated'));
            
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    /**
     * Recalculate order totals after modifications
     */
    private function recalculateOrderTotals($order)
    {
        // Reload the order with items and their products
        $order->load(['orderItems.product']);
        
        $totalItemsCost = 0;
        $originalCost = 0;

        foreach ($order->orderItems as $item) {
            $product = $item->product;
            $itemTotal = $item->price_at_time * $item->product_qty;
            $totalItemsCost += $itemTotal;

            // Calculate original cost
            if ($product) {
                $originalItemCost = $product->price * $item->product_qty;
                $originalCost += $originalItemCost;
            } else {
                $originalCost += $itemTotal;
            }
        }

        // Apply coupon discount if exists
        $couponDiscount = $order->coupon_discount_value ?? 0;
        $deliveryCost = $order->delivery_cost ?? 0;
        $finalCost = $totalItemsCost - $couponDiscount + $deliveryCost;

        $order->update([
            'total_price' => $totalItemsCost,
            'total_cost' => $totalItemsCost + $deliveryCost,
            'original_cost' => $originalCost,
            'order_final_cost' => $finalCost
        ]);
    }

    /**
     * Search products for Select2
     */
    public function searchProducts(Request $request)
    {
        $search = $request->get('q');
        $page = $request->get('page', 1);
        $perPage = 10;

        $query = Product::where('product_status', 1);
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('product_name', 'LIKE', "%{$search}%")
                ->orWhere('ar_product_name', 'LIKE', "%{$search}%")
                ->orWhere('barcode', 'LIKE', "%{$search}%");
                
            });
        }

        $total = $query->count();
        $products = $query->skip(($page - 1) * $perPage)
                        ->take($perPage)
                        ->get(['id', 'product_name', 'ar_product_name', 'price', 'image']);

        $items = $products->map(function($product) {
            return [
                'id' => $product->id,
                'text' => $product->product_name . ' (' . $product->ar_product_name . ')',
                'product_name' => $product->product_name,
                'price' => number_format($product->price, 2),
                'image' => asset('products/images/' . $product->image)
            ];
        });

        return response()->json([
            'items' => $items,
            'pagination' => [
                'more' => ($page * $perPage) < $total
            ]
        ]);
    }

      public function preparationInvoice(Order $order)
    {
        // Load relationships
        $order->load([
            'client',
            'orderItems.product',
            'branch',
            'driver',
        ]);

        return view('orders.prepare', compact('order'));
    }

    public function index()
    {
        $statuses = ['pending', 'pending_driver', 'pending_pickup', 'shipped', 'ready', 'done', 'canceled'];
        $orders = Order::with(['client', 'driver'])->get();
        return view('orders.index', compact('orders', 'statuses'));
    }

    public function change_status(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'status' => 'required',
        ]);
        $order->update([
            'status' => $request->status
        ]);
        OrderNotification::create([
            'icon' => $request->status,
            'client_id' => $order->client_id,
            'order_id' => $order->id,
            'title' => 'تغيير حالة طلبكم',
            'message' => 'تم تغيير حالة الطلب الى' . $request->status,
        ]);

        $deviceToken = optional($order->client->fcmToken)->token;
        if ($deviceToken) {
            $title = __('messages.order_status_updated');
            $body = __('messages.your_order_status_changed_to') . __('messages.' . $request->status);
            $icon = 'offers';
            $data = $request->get('data', []);
            $notificationData = [
                'notification' => [
                    'title' => __('messages.order_status_updated'),
                    'body' => __('messages.your_order_status_changed_to') . __('messages.' . $request->status),
                    'icon' => $request->status,
                ],
                'data' => array_merge($data, [
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'sound' => 'default',
                ]),
            ];
            $response = $this->fcmService->sendPushNotification($deviceToken, $title, $body, $notificationData['data']);
        }
        return redirect()->route('orders.index')->with('success', __('messages.order_update_success'));
    }
    
    public function assignOrderToDriver(Request $request, $id)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $order = Order::findOrFail($id);
        $driver = Driver::findOrFail($request->driver_id);
        $order->driver_id = $request->driver_id;
        $order->branch_id = $request->branch_id;
        $order->status = 'pending_driver';
        OrderNotification::create([
            'icon' => 'pending_driver',
            'client_id' => $order->client_id,
            'driver_id' => $order->driver_id,
            'order_id' => $order->id,
            'title' => 'تغيير حالة طلبكم',
            'message' => 'تم شحن طلبك',
        ]);
        $order->save();
        $tracking_no = $order->tracking_no = 'hs-' . round($order->id + 1000);

        DriverNotification::create([
            'driver_id' => $order->driver_id,
            'title' => 'طلب توصيل جديد',
            'message' => " طلب التوصيل رقم" . $tracking_no . " في انتظار ردك",
        ]);

        // $deviceToken = optional($order->driver->fcmToken)->token;
        $deviceToken = $driver->fcmToken->token;
        if ($deviceToken) {
            $title = __('messages.new_order');
            $body = __('messages.new_order_waitting_response');
            $icon = 'offers';
            $data = $request->get('data', []);
            $notificationData = [
                'notification' => [
                    'title' => __('messages.new_order'),
                    'body' => __('messages.new_order_waitting_response'),
                    'icon' => $request->status,
                ],
                'data' => array_merge($data, [
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                    'sound' => 'default',
                ]),
            ];
            $response = $this->fcmService->sendPushNotification($deviceToken, $title, $body, $notificationData['data']);
            return redirect()->route('orders.index')->with('success', 'Order status updated successfully.');
        }
        return redirect()->route('orders.index')->with('success', 'messages.order_assign_success');
    }
    function assign($id)
    {
        $order = Order::find($id);
        $drivers = Driver::all();
        return view('orders.assign', compact('order', 'drivers'));
    }
    public function show($id)
    {
        $order = Order::with(['client', 'driver'])->findOrFail($id);
        return view('orders.show', compact('order'));
    }
    
   public function deleteOrderItem($orderId, $itemId)
{
    try {
        DB::beginTransaction();
        
        $order = Order::findOrFail($orderId);
        $orderItem = $order->orderItems()->findOrFail($itemId);
    
        // Delete the order item
        $orderItem->delete();
    
        // Reload the order with remaining items and their products
        $order->load(['orderItems.product']);
        $remainingItems = $order->orderItems;
    
        if ($remainingItems->isEmpty()) {
            // If no items are left, delete the order
            $order->delete();
            DB::commit();
            return redirect()->route('orders.index')->with('success', __('messages.order_deleted'));
        }
    
        // Calculate new totals
        $totalPrice = $remainingItems->sum(function ($item) {
            return $item->price_at_time * $item->product_qty;
        });
    
        // Calculate original cost (make sure product relationship is loaded)
        $originalCost = $remainingItems->sum(function ($item) {
            return $item->product->price * $item->product_qty;
        });
        
        // Calculate total cost including delivery and discounts
        $totalCost = $totalPrice + ($order->delivery_cost ?? 0) - ($order->coupon_discount_value ?? 0);
    
        // Update the order totals
        $order->update([
            'total_price' => $totalPrice,
            'total_cost' => $totalCost,
            'original_cost' => $originalCost,
            'order_final_cost' => $totalCost, // Adjust this based on your business logic
        ]);
        
        DB::commit();
    
        return redirect()->route('orders.show', $orderId)->with('success', __('messages.item_deleted_and_totals_updated'));
        
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'An error occurred while deleting the item: ' . $e->getMessage());
    }
}




}
