<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Contract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('admin/contracts/Index', [
            'contracts' => Contract::with('clauses')->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3'
        ]);

        $count = Contract::count();
        Contract::create([
            'name' => $request->name,
        ]);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $request->validate([
            'name' => 'required|min:3'
        ]);

        $contract->update([
            'name' => $request->name,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function sort(Request $request)
    {
        $contracts = Contract::orderBy('order')->get();
        $contracts->map(function($contract, $key) use ($request) {
            $target_contract = array_find($request->contracts, fn($item) => $item['id'] == $contract->id);
            $contract->update(['order' => $target_contract['order']]);
        });
    }
}
