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
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ContractResource;
use App\Services\SignWell\SignWellService;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $signwell = new SignWellService(config('services.signwell.api_key'), config('services.signwell.base_url'));

        return Inertia::render('admin/contracts/Index', [
            'contracts' => ContractResource::collection(Contract::with('client', 'employee', 'services')->get()),
            'clients' => Client::with('employees')->orderBy('company')->get(),
            'services' => Service::orderBy('name')->get(),
            'webhooks' => $signwell->getWebhooks(),
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
            'employee_id' => 'nullable|required',
        ]);

        $contract = Contract::create([
            'price' => $request->price,
            'client_id' => $request->client_id,
            'employee_id' => $request->employee_id,
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
    public function update(Request $request, Contract $contract, SignWellService $signWell)
    {
        $request->validate([
            'price' => 'required',
            'client_id' => 'nullable|required',
            'employee_id' => 'nullable|required',
        ]);

        $contract->update([
            'price' => $request->price,
            'client_id' => $request->client_id,
            'employee_id' => $request->employee_id,
        ]);
        $contract->services()->sync($request->services);

        // DELETE PDF
        Storage::disk('local')->delete('contracts/' . $contract->file_path);

        // DELETE SIGNWELL CONTRACT
        if($contract->signwell_id) {
            $signWell->delete($contract->signwell_id);
            $contract->update([
                'sent' => 0,
                'signwell_id' => NULL,
            ]);
        }

        $pdf = Pdf::loadView('pdf.contract', compact('contract'));
        $content = $pdf->output();

        $fileName = 'contract-' . $contract->id . '-' . uniqid() . '.pdf';
        Storage::disk('local')->put('contracts/' . $fileName, $content);

        $contract->update([
            'file_path' => $fileName,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract, SignWellService $signWell)
    {
        Storage::disk('local')->delete('contracts/' . $contract->file_path);
        if($contract->signwell_id) {
            $signWell->delete($contract->signwell_id);
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

    public function send(Contract $contract, SignWellService $signWell)
    {
        $contract->load('client');
        $document = Storage::disk('local')->get('contracts/' . $contract->file_path);

        $response = $signWell->create([
                'test_mode' => true,
                'name' => 'Web App Contract',
                'subject' => 'Web App Contact from DigitalDad, LLC',
                'embedded_signing' => true,
                'embedded_signing_notifications' => false,
                'recipients' => [
                    [
                        'id' => 1,
                        'name' => $contract->employee['name'],
                        'email' => $contract->employee['email'],
                        'passcode' => 'WEBDEVCONTRACT',
                        'subject' => 'Web App Contract from DigitalDad',
                        'message' => 'Please review and sign this contract for your new web app. Passcode is \'WEBDEVCONTRACT\'',
                        'send_email' => true,
                    ]
                ],
                'fields' => [
                    [
                        [
                            'type' => 'initials',
                            'required' => true,
                            'fixed_width' => false,
                            'lock_sign_date' => false,
                            'allow_other' => false,
                            'x' => 700,
                            'y' => 900,
                            'page' => 1,
                            'recipient_id' => 1
                        ]
                    ]
                        ],
                'files' => [
                    [
                        'name' => $contract->file_path,
                        'file_base64' => base64_encode($document),
                    ]
                ],
        ]);

        $contract->update([
            'sent' => 1,
            'signwell_id' => $response['id'],
        ]);

        return back();
    }

    public function webhook() {
        dd('idiot');
    }
}
