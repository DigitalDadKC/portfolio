<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = ClientResource::collection(Client::with('outreaches')->latest()->get());

        return Inertia::render('admin/clients/Index', [
            'clients' => $clients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company' => 'required',
            'email' => 'required|email',
        ]);

        Client::create([
            'name' => $request->name,
            'company' => $request->company,
            'email' => $request->email,
        ]);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'company' => 'required',
            'email' => 'required|email',
        ]);

        $client->update([
            'name' => $request->name,
            'company' => $request->company,
            'email' => $request->email,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return back();
    }

    public function all_clients() {
        $clients = Client::orderBy('id', 'desc')->get();

        return response()->json([
            'clients' => $clients
        ], 200);
    }
}
