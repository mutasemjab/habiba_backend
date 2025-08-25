<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Offer;
use App\Models\Product;
use App\Services\FCMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferController extends Controller
{
    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    /**
     * Display a listing of the offers.
     */
    public function index()
    {
        $offers = Offer::with('product', 'user')->get();
        return view('offers.index', compact('offers'));
    }
    public function create()

    {
        // Get products that don't have an active offer (status = 1)
        $products = Product::whereDoesntHave('offers', function ($query) {
            $query->where('status', '1');
        })->get();

        return view('offers.create', compact('products'));
    }
    public function edit($id)
    {
        // Find the offer by ID
        $offer = Offer::find($id);

        // Get products that don't have an active offer or the product associated with this offer
        $products = Product::whereDoesntHave('offers', function ($query) use ($offer) {
            $query->where('status', '1')
                ->where('id', '!=', $offer->product_id);
        })->orWhere('id', $offer->product_id)->get();

        return view('offers.edit', compact('offer', 'products'));
    }


    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'persentage' => 'required|integer|min:0|max:100',
            'start_at' => 'required|date|after_or_equal:today',
            'end_at' => 'required|date|after:start_at',
            'status' => 'required|in:0,1',
        ]);

        // Check if the product already has an active offer
        $hasActiveOffer = Offer::where('product_id', $request->input('product_id'))
            ->where('status', '1')
            ->exists();

        if ($hasActiveOffer) {
            return redirect()->back()->withErrors(['product_id' => 'This product already has an active offer.']);
        }

        // Create the offer
        $offer = Offer::create([
            'product_id' => $request->input('product_id'),
            'persentage' => $request->input('persentage'),
            'start_at' => $request->input('start_at'),
            'end_at' => $request->input('end_at'),
            'status' => $request->input('status'),
            'user_id' => Auth::user()->id,
        ]);
        try {
            $clients = Client::with('fcmToken')->get();
            foreach ($clients as $client) {
                $deviceToken = optional($client->fcmToken)->token;
                if ($deviceToken) {
                    $title = 'عروض جديدة';
                    $body = 'تأكد من متابعتنا للوصول الى اخر عروضنا';
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
            return redirect(back()->with(['success' => __('messages.notification_success')]));
        } catch (\Exception $e) {
            return redurect(back()->with(['success' => __('messages.notification_error')]));
        }

        return redirect(route('offers.index'))->with('success', 'Offer created successfully');
    }

    public function update(Request $request, $id)
    {
        $offer = Offer::find($id);
        if (!$offer) {
            return response()->json(['message' => 'Offer not found'], 404);
        }
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'persentage' => 'required|integer|min:0|max:100',
            'start_at' => 'required|date',
            'end_at' => 'required|date',
            'status' => 'required|in:0,1',
        ]);

        // Check if the product already has an active offer excluding the current offer
        $hasActiveOffer = Offer::where('product_id', $request->input('product_id'))
            ->where('status', '1')
            ->where('id', '!=', $offer->id)
            ->exists();

        if ($hasActiveOffer) {
            return redirect()->back()->withErrors(['product_id' => 'This product already has an active offer.']);
        }

        // Update the offer
        $offer->update([
            'product_id' => $request->input('product_id'),
            'persentage' => $request->input('persentage'),
            'start_at' => $request->input('start_at'),
            'end_at' => $request->input('end_at'),
            'status' => $request->input('status'),
            'user_id' => Auth::user()->id,
        ]);

        return redirect(route('offers.index'))->with('success', 'Offer updated successfully');
    }


    /**
     * Display the specified offer.
     */
    public function show($id)
    {
        $offer = Offer::with('product', 'user')->find($id);
        if (!$offer) {
            return response()->json(['message' => 'Offer not found'], 404);
        }
        return response()->json($offer);
    }


    /**
     * Remove the specified offer from storage.
     */
    public function destroy($id)
    {
        $offer = Offer::find($id);
        try {
            $offer->delete();
            return redirect(route('offers.index'))->with('success', 'Offer deleted successfully');
        } catch (\Throwable $th) {
            return redirect(route('offers.index'))->with('error', 'Offer not deleted');
        }
    }
}
