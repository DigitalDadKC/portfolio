<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Client;
use App\Models\Service;
use App\Models\Contract;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use App\Http\Resources\ContractResource;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('admin/contracts/Index', [
            'contracts' => ContractResource::collection(Contract::with('client', 'services')->get()),
            'clients' => Client::orderBy('company')->get(),
            'services' => Service::orderBy('name')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'price' => 'required',
            'client_id' => 'nullable|required',
        ]);

        // dd($request);
        $contract = Contract::create([
            'price' => $request->price,
            'client_id' => $request->client_id,
        ]);
        $contract->services()->sync($request->services);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'price' => 'required',
            'client_id' => 'nullable|required',
        ]);

        $contract->update([
            'price' => $request->price,
            'client_id' => $request->client_id,
        ]);
        $contract->services()->sync($request->services);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        $contract->delete();
        return back()->with('message', 'Contract deleted');
    }

    public function browser(Request $request) {
        $client = [
                'company' => 'company',
                'email' => 'email',
                'address' => '123 Main Street',
                'city' => 'City',
                'state' => 'State',
                'zip' => '12345',
                'url' => 'https://example.com',
                'placeId' => NULL,
        ];
        $services = [
            [
                'title' => 'Service 1',
                'description' => 'Description for service 1',
                'amount' => 50000,
            ],
            [
                'title' => 'Service 2',
                'description' => 'Description for service 2',
                'amount' => 75000,
            ],
        ];

        $data = [
            'client' => $client,
            'services' => $services,
        ];

        $pdf = Pdf::loadView('pdf.contract', $data);

        $filename = 'contract.pdf';
        $filePath = public_path('pdfs/' . $filename);

        File::deleteDirectory(public_path('pdfs'));
        if (!file_exists(public_path('pdfs'))) {
            mkdir(public_path('pdfs'), 0777, true);
        }

        $pdf->save($filePath);
        return $pdf->stream();
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
