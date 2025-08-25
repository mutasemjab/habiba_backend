<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Coupon;
use App\Services\FCMService;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    // Display a list of coupons
    public function index()
    {
        $coupons = Coupon::all();
        return view('coupons.index', compact('coupons'));
    }

    // Show form for creating a new coupon
    public function create()
    {
        return view('coupons.create');
    }

    // Store a new coupon
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|unique:coupons,title',
            'code' => 'required|string|unique:coupons,code',
            'persentage' => 'required|numeric|min:1|max:100',
            'start_at' => 'required|date|after:today',
            'end_at' => 'required|date|after:start_at',
        ]);
        Coupon::create($request->all());
        try {
            $clients = Client::with('fcmToken')->get();
            foreach ($clients as $client) {
                $deviceToken = optional($client->fcmToken)->token;
                if ($deviceToken) {
                    $title = 'قسائم شرائية جديدة';
                    $body = 'تأكد من متابعتنا للحصول على افضل قسائم الشراء على متجرنا';
                    $icon = 'offers';
                    $data = $request->get('data', []);
                    $notificationData = [
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                            'icon' => $icon,
                        ],
                        'data' => array_merge($data, [
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'sound' => 'default',
                        ]),
                    ];
                    $response = $this->fcmService->sendPushNotification($deviceToken, $title, $body, $notificationData['data']);
                }
            }
        } catch (\Exception $e) {
            return redurect(back()->with(['success' => __('messages.notification_error')]));
        }
        return redirect()->route('coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit($id)
    {
        $coupon = Coupon::find($id);
        return view('coupons.edit', compact('coupon'));
    }

    // Update an existing coupon
    public function update(Request $request, $id)
    {
        $coupon = Coupon::find($id);
        $request->validate([
            'title' => 'required|string|unique:coupons,title,'.$id,
            'code' => 'required|string|unique:coupons,code,'.$id,
            'persentage' => 'required|numeric|min:1|max:100',
            'start_at' => 'required|date|after:today',
            'end_at' => 'required|date|after:start_at',
        ]);
        $coupon->update($request->all());
        return redirect()->route('coupons.index')->with('success', 'Coupon updated successfully.');
    }
    public function destroy($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return redirect()->route('coupons.index')->with('success', 'Coupon deleted successfully.');
    }
}
