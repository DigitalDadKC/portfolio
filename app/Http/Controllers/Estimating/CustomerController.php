<?php

namespace App\Http\Controllers\Estimating;

use Inertia\Inertia;
use App\Models\State;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StateResource;
use App\Http\Resources\CustomerResource;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

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

        return Inertia::render('Estimating/Customers', [
            'customers' => fn() => $customers,
            'states' => StateResource::collection(State::orderBy('state')->get()),
            'filtered_states' => $filtered_states,
            'filters' => fn() => $filters
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
    public function store(StoreCustomerRequest $request)
    {
        Customer::create([
            'name' => $request->name,
            'state_id' => $request->state_id
        ]);

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
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
