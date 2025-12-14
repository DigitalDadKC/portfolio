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
use Illuminate\Support\Facades\Storage;

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

        $contract = Contract::create([
            'price' => $request->price,
            'client_id' => $request->client_id,
        ]);
        $contract->services()->sync($request->services);

        $data = [
            'contract' => $contract,
            'client' => $contract->client,
            'services' => $contract->services,
        ];

        $pdf = Pdf::loadView('pdf.contract', $data);
        $content = $pdf->output();

        $fileName = 'contract-' . $contract->id . '-' . uniqid() . '.pdf';

        Storage::disk('local')->put('contracts/' . $fileName, $content);

        $contract->file_path = $fileName;
        $contract->save();

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

        if (Storage::disk('local')->exists('contracts/' . $contract->file_path)) {
            Storage::disk('local')->delete('contracts/' . $contract->file_path);
        }

        $pdf = Pdf::loadView('pdf.contract', compact('contract'));
        $content = $pdf->output();

        $fileName = 'contract-' . $contract->id . '-' . uniqid() . '.pdf';
        Storage::disk('local')->put('contracts/' . $fileName, $content);

        $contract->file_path = $fileName;
        $contract->save();

        $this->send($contract);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        if (Storage::disk('local')->exists('contracts/' . $contract->file_path)) {
            Storage::disk('local')->delete('contracts/' . $contract->file_path);
        }
        $contract->delete();

        return back()->with('message', 'Contract deleted');
    }

    public function browser(Request $request)
    {
        $contract = [
            'price' => 1000,
            'client' => [
                'company' => 'company',
                'email' => 'email',
                'address' => '123 Main Street',
                'city' => 'City',
                'state' => [
                    'abbr' => 'STATE',
                ],
                'zip' => '12345',
                'url' => 'https://example.com',
                'placeId' => NULL,
            ],
            'services' => [
                [
                    'name' => 'Service 1',
                    'price' => 500,
                ],
                [
                    'name' => 'Service 2',
                    'price' => 1500,
                ],
                [
                    'name' => 'Service 3',
                    'price' => 2500,
                ],
            ]
        ];

        $pdf = Pdf::loadView('pdf.contract', compact('contract'));
        $content = $pdf->output();

        if (Storage::disk('local')->exists('/contract.pdf')) {
            Storage::disk('local')->delete('/contract.pdf');
        }

        $fileName = 'contract.pdf';
        $filePath = Storage::disk('local')->put('/' . $fileName, $content);

        $pdf->save($filePath);
        return $pdf->stream();
    }

    public function send(Contract $contract)
    {
        $client = new \GuzzleHttp\Client();
        $contract->load('client');

        $document = Storage::disk('local')->get('contracts/' . $contract->file_path);

        $response = $client->request('POST', 'https://www.signwell.com/api/v1/documents/', [
            'body' => '{
                "test_mode":true,
                "files":
                    [
                        {
                            "name":"' . $contract->file_path . '",
                            "file_base64":"' . base64_encode($document) . '"
                        }
                    ],
                "recipients":
                    [
                        {
                            "send_email":true,
                            "send_email_delay":0,
                            "id":"1",
                            "email":"' . $contract->client->email . '",
                            "name":"' . $contract->client->company . '"
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
                                "x":700,
                                "y":900,
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

        $contract->update([
            'sent' => 1,
            'signwell_id' => json_decode($response->getBody())->id,
        ]);

        return back();
    }
}
