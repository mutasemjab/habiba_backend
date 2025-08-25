<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountDeleteRequestController extends Controller
{
    function account_delete_requests_index(){
        return view('accounts_delete_requests.index');
    }
}