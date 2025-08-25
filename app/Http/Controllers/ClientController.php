<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    function index()
    {
        $clients = Client::all();
        return view('clients.index', compact('clients'));
    }

    function activate($id)
    {
        $client = Client::find($id);
        $client->update([
            'status' => true
        ]);
        return redirect(route('clients.index'));
    }
    function disable($id)
    {
        $client = Client::find($id);
        $client->update([
            'status' => false
        ]);
        return redirect(route('clients.index'));
    }
}
