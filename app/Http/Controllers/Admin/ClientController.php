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
        return Inertia::render('admin/clients/Index', [
            'clients' => ClientResource::collection(Client::with('outreaches', 'state')->latest()->get()),
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
            'email' => 'nullable|email',
        ]);

        Client::create([
            'company' => $request->company,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'state_id' => $request->state_id,
            'zip' => $request->zip,
            'url' => $request->url,
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
            'email' => 'nullable|email',
        ]);

        $client->update([
            'company' => $request->company,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'state_id' => $request->state_id,
            'zip' => $request->zip,
            'url' => $request->url,
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

    public function send(Request $request)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', 'https://www.signwell.com/api/v1/documents/', [
            'body' => '{
                "test_mode":true,
                "files":
                    [
                        {
                            "name":"' . $request->file('document')->getClientOriginalName() . '",
                            "file_base64":"' . base64_encode(file_get_contents($request->file('document')->getRealPath())) . '"
                        }
                    ],
                "recipients":
                    [
                        {
                            "send_email":true,
                            "send_email_delay":0,
                            "id":"1",
                            "email":"raleighgroesbeck@gmail.com",
                            "name":"Stud Muffin"
                        }
                    ],
                "draft":false,
                "with_signature_page":false,
                "reminders":true,
                "apply_signing_order":false,
                "embedded_signing":true,
                "embedded_signing_notifications":false,
                "text_tags":false,
                "allow_decline":true,
                "allow_reassign":true,
                "fields":
                    [
                        [
                            {
                                "type":"initials",
                                "required":true,
                                "fixed_width":false,
                                "lock_sign_date":false,
                                "allow_other":false,
                                "x":50,
                                "y":50,
                                "page":1,
                                "recipient_id":"1"
                            }
                        ]
                    ],
                "name":"Web App Contract"}',
            'headers' => [
                'accept' => 'application/json',
                'content-type' => 'application/json',
                'X-Api-Key' => config('services.signwell.key'),
            ],
        ]);

        echo $response->getBody();
    }
}
