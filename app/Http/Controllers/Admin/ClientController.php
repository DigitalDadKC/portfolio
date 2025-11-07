<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ClientResource;
use SKAgarwal\GoogleApi\PlacesNew\GooglePlaces;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = ClientResource::collection(Client::with('outreaches')->latest()->get());



        $query = $request->input('search');
        // $googlePlaces = new GooglePlacesApi();
        $results = [];
        $data = [];
        if($query) {
            $results = GooglePlaces::make()->autocomplete($query);
            $data = $results->collect();
            // dd($data);
        }

        return Inertia::render('admin/clients/Index', [
            'clients' => $clients,
            'data' => fn() => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company' => 'required',
            'email' => 'email',
        ]);

        Client::create([
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
            'email' => 'email',
        ]);

        $client->update([
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
