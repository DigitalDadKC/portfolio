<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Contract;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('admin/contracts/Index', [
            'contracts' => Contract::orderBy('order')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3',
            'description' => 'nullable|string',
        ]);

        Contract::create([
            'title' => $request->title,
            'description' => $request->description,
            'order' => Contract::count() + 1,
        ]);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'title' => 'required|min:3',
            'description' => 'nullable|string',
        ]);

        $contract->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

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

    public function sort(Request $request)
    {
        $contracts = Contract::orderBy('order')->get();
        $contracts->map(function ($contract, $key) use ($request) {
            $target_contract = array_find($request->contracts, fn($item) => $item['id'] == $contract->id);
            $contract->update(['order' => $target_contract['order']]);
        });
    }

    public function browser(Request $request) {
        // dd($request);

        // $contract->load('invoice_items.material.material_unit_size', 'invoice_items.material.material_category');

        $data = [
            // 'contract' => ContractResource::make(COntract::all()),
            // 'company' => CompanyResource::collection(Company::all())->first(),
        ];

        $pdf = Pdf::loadView('pdf.contract', $data);
        return $pdf->stream('invoice-in-browser.pdf');
    }
}
