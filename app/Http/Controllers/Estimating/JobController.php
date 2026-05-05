<?php

namespace App\Http\Controllers\Estimating;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\JobResource;
use App\Http\Resources\StateResource;
use App\Models\Customer;
use App\Models\Job;
use App\Models\State;
use Illuminate\Http\Request;
use Inertia\Inertia;

class JobController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search'   => $request->search,
            'pages'    => $request->pages ?: 25,
            'states'   => $request->states ?: null,
            'customers' => $request->customers ?: null,
        ];

        $query = Job::query()
            ->with(['state', 'customer', 'proposals.user:id,name,email', 'proposals.scopes.lines.unit_of_measurement'])
            ->when($filters['search'], function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('number', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('address', 'like', "%{$search}%");
                });
            })
            ->when($request->states, fn($query, $states) => $query->whereIn('state_id', $states))
            ->when($request->customers, fn($query, $customers) => $query->whereIn('customer_id', $customers))
            ->orderBy('created_at', 'desc');      

        $paginated = $query->paginate($filters['pages'])->appends($filters);

        if ($paginated->isEmpty() && $paginated->currentPage() > 1) {
            $paginated = $query->paginate($filters['pages'], ['*'], 'page', 1)
                ->appends($filters);
        }

        $jobs = JobResource::collection($paginated);

        return Inertia::render('estimating/jobs/Index', [
            'jobs' => fn() => $jobs,
            'states' => StateResource::collection(State::orderBy('state')->get()),
            'customers' => CustomerResource::collection(Customer::orderBy('name')->get()),
            'filters' => $filters
        ]);
    }

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
            'zip' => $request->zip,
            'start_date' => $request->start_date,
            'prevailing_wage' => $request->prevailing_wage,
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
            'zip' => $request->zip,
            'start_date' => $request->start_date,
            'prevailing_wage' => $request->prevailing_wage,
            'notes' => $request->notes,
        ]);

        return back();
    }

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
