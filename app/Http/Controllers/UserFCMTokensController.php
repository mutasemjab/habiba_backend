<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use App\Models\UserFCMTokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Google\Client as GoogleClient;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Services\FCMService;


class UserFCMTokensController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function __construct(FCMService $fcmService)
    {
        $this->fcmService = $fcmService;
    }
    public function create()
    {
        $clients = Client::with('fcmToken')->get();
        return view('notifications.per_user.create', compact('clients'));
    }

    public function send_notification(Request $request)
    {
        $request->validate([
            'icon' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'client_id' => 'required|exists:clients,id',
        ]);

        try {
            $client = Client::with('fcmToken')->findOrFail($request->client_id);
            $deviceToken = optional($client->fcmToken)->token;

            if (!$deviceToken) {
                return response()->json(['status' => 'error', 'message' => 'No FCM token found for this client.'], 404);
            }

            $title = $request->title;
            $body = $request->message;
            $icon = $request->icon;
            $data = $request->get('data', []);

            // Construct notification data
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
            return redirect()->back()->with(['success' => __('messages.notification_success')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => __('messages.notification_error')]);
        }
    }

    public function globalcreate()
    {

        return view('notifications.global.global');
    }
   public function global_send_notification(Request $request)
    {
        $request->validate([
            'icon' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
    
        try {
            $clients = Client::with('fcmToken')->get();
    
            foreach ($clients as $client) {
                $deviceToken = optional($client->fcmToken)->token;
    
                if ($deviceToken) {
                    $title = trim(strip_tags($request->title));
                    $body = trim(strip_tags($request->message));
                    $icon = trim(strip_tags($request->icon));
                    $data = $request->get('data', []);
    
                    $notificationData = [
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                            'icon' => $icon,
                        ],
                        'data' => array_merge((is_array($data) ? $data : []), [
                            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                            'sound' => 'default',
                        ]),
                    ];
    
                    try {
                        $response = $this->fcmService->sendPushNotification($deviceToken, $title, $body, $notificationData['data']);
                        \Log::info('Notification sent successfully to token: ' . $deviceToken);
                    } catch (\Exception $e) {
                        \Log::error('Failed to send notification to token: ' . $deviceToken . ' - Error: ' . $e->getMessage());
                    }
                }
            }
    
            return back()->with(['success' => __('messages.notification_success')]);
        } catch (\Exception $e) {
            \Log::error('Global Notification Error: ' . $e->getMessage());
            return back()->with(['error' => __('messages.notification_error')]);
        }
    }

}
