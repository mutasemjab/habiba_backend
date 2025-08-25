<?php

namespace App\Http\Controllers;
use App\Models\ContactUs;


use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    function contact(){
       
        return view('contact');
    }
    
     public function store(Request $request)
    {
        $this->validate($request, [
            'user_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        ContactUs::create([
            'user_name' => $request->input('user_name'),
            'mobile' => $request->input('mobile'),
            'subject' => $request->input('subject'),
            'message' => $request->input('message'),
        ]);

        return redirect()->back()->with('success', 'Thanks for contacting us! We will get back to you as soon as possible.');
    }
    
    
    function index(){
        $contact_us_messages = ContactUs::all();
        return view('contact_us.index',compact('contact_us_messages'));
    }
    function create(Request $request){
        $this->validate($request,[
            'user_name'=>'string',
            'mobile'=>'string',
            'subject'=>'string',
            'message'=>'string',
        ]);
        ContactUs::create($request->all());
        return response()->json([
            'status'=>true,
            'data'=>null,
            'message'=>'Thank you'
        ],200);
    }
}