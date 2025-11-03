<?php

namespace App\Http\Controllers\Estimating;

use Inertia\Inertia;
use App\Models\State;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StateResource;
use App\Http\Resources\CustomerResource;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [];
        $filters['search'] = $request->search;
        $filters['order'] = $request->input('order', 'asc');
        $filters['state'] = $request->input('state', NULL);

        $customers = CustomerResource::collection(Customer::with('state', 'jobs')
            ->when($request->search, fn($query, $search) => $query->where('name', 'like', "%{$search}%"))
            ->when($request->state, fn($query, $state) => $query->where('state_id', $state))
            ->orderBy('name', $filters['order'])
            ->get());

        $filtered_states = StateResource::collection(State::whereIn('id', $customers->pluck('state_id'))->orderBy('state', 'asc')->get());

        return Inertia::render('estimating/customers/Index', [
            'customers' => fn() => $customers,
            'states' => StateResource::collection(State::orderBy('state')->get()),
            'filtered_states' => $filtered_states,
            'filters' => fn() => $filters
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'state_id' => 'required'
        ]);

        Customer::create([
            'name' => $request->name,
            'state_id' => $request->state_id
        ]);

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|unique:customers,name,'.$customer->id,
            'state_id' => 'required'
        ]);

        $customer->update([
            'name' => $request->name,
            'state_id' => $request->state_id
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
    }
}
