<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\State;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StateResource;
use App\Http\Resources\ClientResource;
use SKAgarwal\GoogleApi\PlacesNew\GooglePlaces;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = ClientResource::collection(Client::with('outreaches', 'state')->latest()->get());

        return Inertia::render('admin/clients/Index', [
            'clients' => $clients,
            'states' => StateResource::collection(State::orderBy('state')->get()),
            'place' => Inertia::optional(fn() => GooglePlaces::make()->placeDetails($request->placeId)->collect()),
            'places' => Inertia::optional(fn() => GooglePlaces::make()->autocomplete($request->search ?? '')->collect())
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
            'address' => $request->address,
            'city' => $request->city,
            'state_id' => $request->state_id,
            'zip' => $request->zip,
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
            'address' => $request->address,
            'city' => $request->city,
            'state_id' => $request->state_id,
            'zip' => $request->zip,
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
}
