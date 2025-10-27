<?php

namespace App\Http\Controllers\Estimating;

use App\Models\Job;
use Inertia\Inertia;
use App\Models\State;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Resources\JobResource;
use App\Http\Controllers\Controller;

class JobController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = [];
        $filters['search'] = $request->search;
        $filters['pages'] = $request->pages ? $request->pages : 25;
        $filters['state'] = $request->state ? $request->state : NULL;
        $filters['customer'] = $request->customer ? $request->customer : NULL;

        $jobs = Job::query()->with(['state', 'customer', 'proposals.user:id,name,email', 'proposals.scopes.lines.unit_of_measurement'])
            ->when($request->search, fn($query, $search) => $query->where('number', 'like', "%{$search}%")->orWhere('address', 'like', "%{$search}%")->orWhere('city', 'like', "%{$search}%"))
            ->when($request->state, fn($query, $state) => $query->where('state_id', $state))
            ->when($request->customer, fn($query, $customer) => $query->where('customer_id', $customer))
            ->orderBy('created_at', 'desc');

        $states = State::whereIn('id', $jobs->get()->pluck('state_id'))->orderBy('state', 'asc')->get();
        $customers = Customer::whereIn('id', $jobs->get()->pluck('customer_id'))->orderBy('name', 'asc')->get();
        $jobs = ($jobs->paginate($filters['pages'])->isEmpty()) ? JobResource::collection($jobs->paginate($filters['pages'], ['*'], 'page', 1)->withQueryString()) : JobResource::collection($jobs->paginate($filters['pages'])->withQueryString());

        return Inertia::render('estimating/Index', [
            'jobs' => fn() => $jobs,
            'states' => $states,
            'customers' => $customers,
            'filters' => $filters
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'number' => 'required|unique:jobs|min:3',
            'address' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'zip' => 'required|min:5|max:5',
            'customer_id' => 'required',
            'start_date' => 'required',
        ]);

        Job::create([
            'number' => $request->number,
            'address' => $request->address,
            'city' => $request->city,
            'state_id' => $request->state_id,
            'start_date' => $request->start_date,
            'zip' => $request->zip,
            'notes' => $request->notes,
            'customer_id' => $request->customer_id,
        ]);

        return to_route('estimating.index');
    }
    public function update(Request $request, Job $job)
    {
        $request->validate([
            'number' => 'required|min:3|unique:jobs,number,'.$job->id,
            'address' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'zip' => 'required',
        ]);

        $job->update([
            'number' => $request->number,
            'address' => $request->address,
            'city' => $request->city,
            'state_id' => $request->state_id,
            'start_date' => $request->start_date,
            'zip' => $request->zip,
            'notes' => $request->notes,
        ]);

        return to_route('estimating.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function report(Request $request)
    {
        $jobs = JobResource::collection(Job::with(['state', 'customer', 'proposals.user:id,name,email', 'proposals.scopes.lines.unit_of_measurement'])->orderBy('created_at', 'desc')->get());
        return Inertia::render('estimating/Report', [
            'jobs' => $jobs
        ]);
    }
}
