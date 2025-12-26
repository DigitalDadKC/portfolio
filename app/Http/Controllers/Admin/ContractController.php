<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Client;
use App\Models\Service;
use App\Models\Contract;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ContractResource;
use App\Services\Pdf\PdfService;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(protected PdfService $pdf) {

    }
    public function index()
    {
        return Inertia::render('admin/contracts/Index', [
            'contracts' => ContractResource::collection(Contract::with('client', 'employee', 'services')->get()),
            'clients' => Client::with('employees')->orderBy('company')->get(),
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
            'employee_id' => 'nullable|required',
        ]);

        $contract = Contract::create([
            'price' => $request->price,
            'client_id' => $request->client_id,
            'employee_id' => $request->employee_id,
        ]);
        $contract->services()->sync($request->services);

        $this->pdf->createContract($contract);

        return back()->with('message', 'Contract created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
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

        $this->pdf->destroyContract($contract);
        $this->pdf->createContract($contract);
        
        return back()->with('message', 'Contract updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        $this->pdf->destroyContract($contract);
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
            'employee' => [
                'name' => 'John Doe'
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
        $contract->load('client');
        $this->pdf->sendContract($contract);

        return back()->with('message', 'Contract sent');
    }
}
