<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\SiteGneral;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function contact()
    {
        
        return view('contact');
    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $users = User::count();
        $widget = [
            'users' => $users,
        ];
        return view('home', compact('widget'));
    }
    public function site_generals_index()
    {
        $siteGenerals = SiteGneral::first();
        return view('generals.index', compact('siteGenerals'));
    }
    public function site_generals_create()
    {
        return view('generals.create');
    }
    public function site_generals_store(Request $request)
    {
        $this->validate($request, [
            'min_order' => 'nullable',
            'whatsapp_link' => 'string|nullable',
            'facebook_link' => 'string|nullable',
            'instagram_link' => 'string|nullable',
            'delivery_price' => 'integer|nullable',
            'terms' => 'string|nullable',
            'about_us' => 'string|nullable',
            'return_policy' => 'string|nullable',
            'onboarding_1' => 'required|string',
            'onboarding_2' => 'required|string',
            'onboarding_3' => 'required|string',
        ]);

        $siteGeneral = SiteGneral::first() ?? new SiteGneral();
        $siteGeneral->min_order = $request->min_order;
        $siteGeneral->whatsapp_link = $request->whatsapp_link;
        $siteGeneral->facebook_link = $request->facebook_link;
        $siteGeneral->instagram_link = $request->instagram_link;
        $siteGeneral->delivery_price = $request->delivery_price;
        $siteGeneral->terms = $request->terms;
        $siteGeneral->about_us = $request->about_us;
        $siteGeneral->return_policy = $request->return_policy;
        $siteGeneral->onboarding_1 = $request->onboarding_1;
        $siteGeneral->onboarding_2 = $request->onboarding_2;
        $siteGeneral->onboarding_3 = $request->onboarding_3;
        $siteGeneral->save();

        return redirect(route('site_generals.index'));
    }
    public function site_generals_edit()
    {
        $siteGeneral = SiteGneral::first();
        return view('generals.edit', compact('siteGeneral'));
    }
    public function get_petty_cash()
    {
        $drivers = Driver::all();
        return view('petty_cash.get', compact('drivers'));
    }
    public function empty_petty_cash(Request $request)
    {
        // Validate the request
        $this->validate($request, [
            'profit_percentage' => 'required|numeric|min:0|max:100', // Ensure percentage is valid
            'driver_id' => 'required|exists:drivers,id',
            'final_amount' => 'required|numeric',
        ]);
        $driver = Driver::find($request->driver_id);
        $walletBalance = $driver->wallet;
        $profitPercentage = $request->profit_percentage;
        $finalAmount = $walletBalance - ($walletBalance * ($profitPercentage / 100));
        $siteGeneral = SiteGneral::first();        
        $siteGeneral->update([
            'profit'=>$siteGeneral->profit + $finalAmount,
        ]);
        if ($finalAmount <= 0) {
            return redirect()->back()->withErrors(['final_amount' => __('The final amount to withdraw must be greater than zero.')]);
        }
        $driver->update([
            'wallet' => 0,
        ]);
        return redirect()->back()->with('success', __('Cash withdrawn successfully. The final amount is :amount.', ['amount' => number_format($finalAmount, 2)]));
    }

}