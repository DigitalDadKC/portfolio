<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Contract;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Exports\MaterialExport;
use App\Models\MaterialCategory;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MaterialEffectiveDate;
use App\Http\Resources\ContractResource;
use App\Http\Resources\MaterialResource;
use App\Http\Resources\MaterialEffectiveDateResource;

class DatatableController extends Controller
{
    public function __construct()
    {
        $this->base = collect(['id' => 0, 'Name' => 'Initial', 'Contract_No' => 'N/A', 'Effective_Date' => date('F j, Y'), 'Termination_Date' => 'N/A', 'Admin_Fee' => 0, 'Discount' => 0]);
    }
    public function index()
    {
        $materials = MaterialResource::collection(Material::with(['materialUnitSize', 'materialCategory'])->get());
        $contracts = ContractResource::collection(Contract::with(['materialEffectiveDate'])->orderBy('name', 'asc')->get());
        $book_price_effective_dates = MaterialEffectiveDateResource::collection(MaterialEffectiveDate::where('contract_id', NULL)->orderBy('effective_date', 'desc')->get());
        $categories = MaterialCategory::get();
        $statuses = collect([['id' => 1, 'Name' => 'New'], ['id' => 2, 'Name' => 'Active'], ['id' => 3, 'Name' => 'Removed'], ['id' => 4, 'Name' => 'Discontinued'], ['id' => 5, 'Name' => 'Obsolete']]);
        $discountables = collect([['id' => 1, 'Name' => 'Yes'], ['id' => 2, 'Name' => 'No']]);
        return Inertia::render('Datatable/Index', [
            'materials' => $materials,
            'contracts' => $contracts,
            'categories' => $categories,
            'book_price_effective_dates' => $book_price_effective_dates,
            'statuses' => $statuses,
            'discountables' => $discountables
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function export(Request $request)
    {
        $contract = Contract::where('id', $request->data['contract'])->first() ?? $this->base;
        $status = $request->data['effective_date'] . '-status';
        $materials = Material::with('materialUnitSize', 'materialCategory')->get();
        $materials = $materials->filter(function ($value) use ($request, $status) {
            return collect($request->data['statuses'])->where('checked', 1)->pluck('Name')->contains($value[$status]);
        })->filter(function ($value) use ($request) {
            return collect($request->data['categories'])->where('checked', 1)->pluck('Name')->contains($value['materialCategory']['Name']);
        })->filter(function ($value) use ($request) {
            return str_contains($value['Name'], $request->data['search']);
        })->filter(function ($value) use ($request) {
            return collect($request->data['discountables'])->where('checked', 1)->pluck('Value')->contains($value['Discountable']);
        })->filter(function ($value) use ($request) {
            return $value[$request->data['effective_date']] < $request->data['price'];
        });



        if ($request->data['sortSKU']) {
            $materials = $materials->sortBy('SKU')->values()->all();
        }

        $title = $contract['Name'] . ' - Effective ' . date('F j, Y', strtotime($request->data['effective_date']));

        return Excel::download(new MaterialExport($contract, $materials, $request->data['effective_date']), $title . '.xlsx');
    }
}
