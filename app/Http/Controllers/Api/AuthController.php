<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use App\Models\Driver;
use App\Models\UserFCMTokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;


class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
        ]);
    
        $client = Client::where('mobile', $request->mobile)->first();
        if ($client) {
            UserFCMTokens::where('token', $request->token)->delete(); // Optionally delete conflicting tokens
    
            UserFCMTokens::updateOrCreate(
                ['client_id' => $client->id],
                ['token' => $request->token]
            );
    
            // Generate OTP (6-digit random number)
            $otp = rand(1000, 9999);
            
            // Store OTP in session or cache for verification (expires in 5 minutes)
            Cache::put('otp_' . $client->mobile, $otp, 300);
    
            // Send OTP via new SMS gateway
            $senderid = 'HabibaStore';
            $accname = 'aliencode';
            $accpass = 'jU0nH9pI6mD4vQ2s';
            $message = "Your OTP code is: $otp. Valid for 2 minutes.";
            $mobile = ltrim($client->mobile, '+'); // Remove + from mobile number if present
            
            // Log the parameters being sent
            Log::info('SMS Gateway Parameters:', [
                'senderid' => $senderid,
                'numbers' => $mobile,
                'accname' => $accname,
                'message' => $message,
                'original_mobile' => $client->mobile,
                'formatted_mobile' => $mobile,
                'otp' => $otp
            ]);
            
            $url = "https://www.josms.net/SMSServices/Clients/Prof/RestSingleSMS/SendSMS?" . http_build_query([
                'senderid' => $senderid,
                'numbers' => $mobile,
                'accname' => $accname,
                'AccPass' => $accpass,
                'msg' => $message
                // Removed 'id' parameter since it's optional and causing issues
            ]);
            
            // Log the complete URL (without password for security)
            Log::info('SMS Gateway URL:', [
                'url' => str_replace($accpass, '***HIDDEN***', $url)
            ]);
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false, // Add this if SSL issues
                CURLOPT_SSL_VERIFYHOST => false, // Add this if SSL issues
            ));
    
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlError = curl_error($curl);
            $curlInfo = curl_getinfo($curl);
            curl_close($curl);
            
            // Log the complete response
            Log::info('SMS Gateway Response:', [
                'http_code' => $httpCode,
                'response' => $response,
                'curl_error' => $curlError,
                'curl_info' => $curlInfo
            ]);
    
            if ($httpCode == 200 && empty($curlError)) {
                Log::info('SMS sent successfully for mobile: ' . $mobile);
                return response()->json([
                    'status' => true,
                    'message' => __('messages.otp_sent'),
                    'data' => $client,
                    'token' => $client->createToken('auth_token')->plainTextToken,
                ], 200);
            } else {
                Log::error('SMS sending failed:', [
                    'http_code' => $httpCode,
                    'curl_error' => $curlError,
                    'response' => $response,
                    'mobile' => $mobile
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to send OTP. Please try again.',
                    'debug_info' => [
                        'http_code' => $httpCode,
                        'error' => $curlError,
                        'response' => $response
                    ]
                ], 500);
            }
        } else {
            return response()->json(['message' => __('messages.not_client')], 401);
        }
    }


      public function verifyUserOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'otp' => 'required',
        ]);
    
        // Check for test case
        if ($request->mobile === '+962795970357' && $request->otp === '2025') {
            $client = Client::where('mobile', $request->mobile)->first();
            if (!$client) {
                return response()->json([
                    'status' => false,
                    'message' => __('messages.not_client'),
                ], 404);
            }
    
            return response()->json([
                'status' => true,
                'message' => __('messages.otp_confirmed'),
                'data' => $client,
                'token' => $client->createToken('auth_token')->plainTextToken,
            ], 200);
        }
    
        $client = Client::where('mobile', $request->mobile)->first();
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => __('messages.not_client'),
            ], 404);
        }
    
        // Retrieve stored OTP from cache
        $storedOtp = Cache::get('otp_' . $client->mobile);
        
        if (!$storedOtp) {
            return response()->json([
                'status' => false,
                'message' => 'OTP has expired. Please request a new one.',
            ], 400);
        }
    
        if ($storedOtp == $request->otp) {
            // OTP is correct, remove it from cache
            Cache::forget('otp_' . $client->mobile);
            
            return response()->json([
                'status' => true,
                'message' => __('messages.otp_confirmed'),
                'data' => $client,
                'token' => $client->createToken('auth_token')->plainTextToken,
            ], 200);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP. Please try again.',
            ], 400);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'nullable',
            'mobile' => 'required|unique:clients,mobile',
            'name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }
        $client = Client::where('mobile',$request->mobile)->first();
        if($client){
            return response()->json([
                'status' => true,
                'message' => __('messages.welcome_back'),
                'data' => [
                    'client' => $client,
                ],
            ], 200);
        }else{
            $client = Client::create([
                'email' => $request->email,
                'mobile' => $request->mobile,
                'name' => $request->name,
            ]);
            return response()->json([
                'status' => true,
                'message' => __('messages.register_success'),
                'data' => [
                    'client' => $client,
                ],
            ], 201);
        }
    }

    public function logout(Request $request)
    {
        $client = Auth::user();
        if (!$client) {
            return response()->json([
                'status' => false,
                'message' => __('messages.not_client'),
            ], 401);
        }
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status' => true,
            'message' => __('messages.logout_success'),
        ], 200);
    }
    function profile()
    {
        $user = Auth::user();
        return response()->json($user);
    }
    public function profile_update(Request $request)
    {
        $user = Auth::user();
        $validator = Validator::make($request->all(), [
            'mobile' => 'unique:clients,mobile,' . $user->id,
            'name' => 'string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }
        $dataToUpdate = $request->only(['mobile', 'name']);
        foreach ($dataToUpdate as $key => $value) {
            if ($value !== null) {
                $user->$key = $value;
            }
        }
        $user->save();
        return response()->json([
            'status' => true,
            'data' => $user,
            'message' => __('messages.profile_update_success')
        ], 200);
    }


     public function driverLogin(Request $request)
    {
        $credentials = $request->only('nid', 'password');
    
        $validator = Validator::make($credentials, [
            'nid' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => __('messages.login_error'),
                'errors' => $validator->errors(),
            ], 422);
        }
    
        if (Auth::guard('driver')->attempt(['nid' => $credentials['nid'], 'password' => $credentials['password']])) {
            $driver = Auth::guard('driver')->user();
            $token = $driver->createToken('DriverAuthToken')->plainTextToken;
            $driver->image = url($driver->image ? asset("storage/drivers/images/{$driver->image}") : asset("images/default-avatar.png"));
            
            // Generate OTP (6-digit random number)
            $otp = rand(1000, 9999);
            
            // Store OTP in session or cache for verification (expires in 5 minutes)
            Cache::put('driver_otp_' . $driver->mobile, $otp, 300);
    
            // Send OTP via new SMS gateway
            $senderid = 'HabibaStore';
            $accname = 'aliencode';
            $accpass = 'jU0nH9pI6mD4vQ2s';
            $message = "Your OTP code is: $otp. Valid for 2 minutes.";
            $mobile = ltrim($driver->mobile, '+'); // Remove + from mobile number if present
            
            // Log the parameters being sent
            Log::info('Driver SMS Gateway Parameters:', [
                'senderid' => $senderid,
                'numbers' => $mobile,
                'accname' => $accname,
                'message' => $message,
                'original_mobile' => $driver->mobile,
                'formatted_mobile' => $mobile,
                'otp' => $otp,
                'driver_id' => $driver->id
            ]);
            
            $url = "https://www.josms.net/SMSServices/Clients/Prof/RestSingleSMS/SendSMS?" . http_build_query([
                'senderid' => $senderid,
                'numbers' => $mobile,
                'accname' => $accname,
                'AccPass' => $accpass,
                'msg' => $message
            ]);
            
            // Log the complete URL (without password for security)
            Log::info('Driver SMS Gateway URL:', [
                'url' => str_replace($accpass, '***HIDDEN***', $url)
            ]);
            
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
            ));
    
            $response = curl_exec($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            $curlError = curl_error($curl);
            $curlInfo = curl_getinfo($curl);
            curl_close($curl);
            
            // Log the complete response
            Log::info('Driver SMS Gateway Response:', [
                'http_code' => $httpCode,
                'response' => $response,
                'curl_error' => $curlError,
                'curl_info' => $curlInfo,
                'driver_id' => $driver->id
            ]);
    
            // Update FCM token
            UserFCMTokens::updateOrCreate(
                ['driver_id' => $driver->id],
                ['token' => $request->token]
            );
    
            if ($httpCode == 200 && empty($curlError)) {
                Log::info('Driver SMS sent successfully for mobile: ' . $mobile . ', driver_id: ' . $driver->id);
                return response()->json([
                    'status' => true,
                    'message' => __('messages.login_success'),
                    'token' => $token,
                    'driver' => $driver,
                ], 200);
            } else {
                Log::error('Driver SMS sending failed:', [
                    'http_code' => $httpCode,
                    'curl_error' => $curlError,
                    'response' => $response,
                    'mobile' => $mobile,
                    'driver_id' => $driver->id
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to send OTP. Please try again.',
                    'debug_info' => [
                        'http_code' => $httpCode,
                        'error' => $curlError,
                        'response' => $response
                    ]
                ], 500);
            }
        } else {
            return response()->json([
                'status' => false,
                'message' => __('messages.login_not_success'),
            ], 401);
        }
    }

    function verifyDriverOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'otp' => 'required',
        ]);
    
        $driver = Driver::where('mobile', $request->mobile)->first();
    
        if (!$driver) {
            return response()->json([
                'status' => false,
                'message' => __('messages.driver_not_registered'),
            ], 404);
        }
    
        // Log the verification attempt
        Log::info('Driver OTP Verification Attempt:', [
            'mobile' => $request->mobile,
            'otp_provided' => $request->otp,
            'driver_id' => $driver->id
        ]);
    
        // Retrieve stored OTP from cache
        $storedOtp = Cache::get('driver_otp_' . $driver->mobile);
        
        Log::info('Driver OTP Cache Check:', [
            'mobile' => $driver->mobile,
            'stored_otp' => $storedOtp,
            'provided_otp' => $request->otp,
            'cache_key' => 'driver_otp_' . $driver->mobile
        ]);
        
        if (!$storedOtp) {
            Log::warning('Driver OTP expired or not found:', [
                'mobile' => $driver->mobile,
                'driver_id' => $driver->id
            ]);
            return response()->json([
                'status' => false,
                'message' => 'OTP has expired. Please request a new one.',
            ], 400);
        }
    
        if ($storedOtp == $request->otp) {
            // OTP is correct, remove it from cache
            Cache::forget('driver_otp_' . $driver->mobile);
            
            Log::info('Driver OTP verified successfully:', [
                'mobile' => $driver->mobile,
                'driver_id' => $driver->id
            ]);
            
            return response()->json([
                'status' => true,
                'message' => __('messages.otp_confirmed'),
                'data' => $driver,
                'token' => $driver->createToken('auth_token')->plainTextToken,
            ], 200);
        } else {
            Log::warning('Driver OTP verification failed - Invalid OTP:', [
                'mobile' => $driver->mobile,
                'provided_otp' => $request->otp,
                'stored_otp' => $storedOtp,
                'driver_id' => $driver->id
            ]);
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP. Please try again.',
            ], 400);
        }
    }
    public function driver_logout(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => __('messages.logout_error'),
                ], 401);
            }
            $user->tokens()->delete();
            return response()->json([
                'status' => true,
                'message' => __('messages.logout_success'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => __('messages.logout_error'),
            ], 500);
        }
    }
}
