<?php

namespace App\Http\Controllers\Estimating;

use App\Models\Job;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Skill;
use App\Models\State;
use App\Models\Company;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\JobResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\SkillResource;
use App\Http\Resources\StateResource;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
   /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(!Company::count()) {
            $new = true;
            $company = new CompanyResource(new Company([
                'id' => NULL,
                'name' => '',
                'logo' => NULL,
                'address' => '',
                'city' => '',
                'state_id' => NULL,
                'zip' => NULL
            ]));
        } else {
            $new = false;
            $company = CompanyResource::collection(Company::with('state')->get())->first();
        };
        $jobs = JobResource::collection(Job::with(['state', 'customer', 'proposals.user:id,name,email', 'proposals.scopes.lines.unit_of_measurement'])->orderBy('created_at', 'desc')->get());

        return Inertia::render('estimating/Admin', [
            'new' => $new,
            'company' => $company,
            'jobs' => $jobs,
            'states' => StateResource::collection(State::all()),
            'users' => UserResource::collection(User::with('roles')->get()),
            'roles' => RoleResource::collection(Role::all()),
            'skills' => SkillResource::collection(Skill::all()),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCompany(Request $request)
    {
        $request->validate([
            'name' => 'required|string:255',
            'address' => 'required|string:255',
            'city' => 'required|string:255',
            'state_id' => 'required',
            'zip' => 'required|integer',
            'terms' => 'required'
        ]);

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo')->store('companies');
            $company = Company::create([
                'name' => $request->name,
                'address' => $request->address,
                'city' => $request->city,
                'state_id' => $request->state_id,
                'zip' => $request->zip,
                'terms' => $request->terms,
                'logo' => $logo
            ]);
        } else {
            return abort(403);
        }

        return back()->with('message', $company);
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCompany(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string:255',
            'address' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'zip' => 'required',
            'terms' => 'required',
        ]);

        $logo = $company->logo;
        if ($request->hasFile('logo')) {
            Storage::delete($company->logo);
            $logo = $request->file('logo')->store('companies');
        }

        $company -> update([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state_id' => $request->state_id,
            'zip' => $request->zip,
            'logo' => $logo,
            'terms' => $request->terms,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        //
    }
    public function update_user(User $user, Request $request) {
        $user->syncRoles($request->input('user.roles.*.name'));
    }
}
