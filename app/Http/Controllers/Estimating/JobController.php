<?php

namespace App\Http\Controllers\Estimating;

use App\Models\Job;
use Inertia\Inertia;
use App\Models\State;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Http\Resources\JobResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\StateResource;

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
            ->where(fn($query) => $query->where('number', 'like', "%{$filters['search']}%")->orWhere('address', 'like', "%{$filters['search']}%"))
            ->when($request->state, fn($query, $state) => $query->where('state_id', $state))
            ->when($request->customer, fn($query, $customer) => $query->where('customer_id', $customer))
            ->orderBy('created_at', 'desc');

        $customers = Customer::whereIn('id', $jobs->get()->pluck('customer_id'))->orderBy('name', 'asc')->get();
        $jobs = ($jobs->paginate($filters['pages'])->isEmpty()) ? JobResource::collection($jobs->paginate($filters['pages'], ['*'], 'page', 1)->withQueryString()) : JobResource::collection($jobs->paginate($filters['pages'])->withQueryString());

        return Inertia::render('estimating/jobs/Index', [
            'jobs' => fn() => $jobs,
            'states' => StateResource::collection(State::orderBy('state')->get()),
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

        return back();
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

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Job $job)
    {
        $job->delete();

        return back();
    }

    public function report(Request $request)
    {
        $jobs = JobResource::collection(Job::with(['state', 'customer', 'proposals.user:id,name,email', 'proposals.scopes.lines.unit_of_measurement'])->orderBy('created_at', 'desc')->get());

        return Inertia::render('estimating/Report', [
            'jobs' => $jobs
        ]);
    }
}
