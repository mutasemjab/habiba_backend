<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    function generalNotificationBlade(Request $request){
        return view('notifications.global.global');
    }
    function generalNotificationSend(Request $request){
        // Get All Clients FCM tokens and send a notification
        $server_key='';
        $icon=$request->icon;
        $title=$request->title;
        $message=$request->message;
    }
}
