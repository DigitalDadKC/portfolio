<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Skill;
use App\Models\State;
use App\Models\Company;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\SkillResource;
use App\Http\Resources\StateResource;
use App\Http\Resources\CompanyResource;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
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
        return Inertia::render('Estimating/Company', [
            'new' => $new,
            'company' => $company,
            'states' => StateResource::collection(State::all()),
            'users' => UserResource::collection(User::with('roles')->get()),
            'roles' => RoleResource::collection(Role::all()),
            'skills' => SkillResource::collection(Skill::all()),
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
                'zip_code' => $request->zip,
                'terms' => $request->terms,
                'logo' => $logo
            ]);
        } else {
            return abort(403);
        }

        // dd($company);
        return back()->with('message', $company);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $logo = $request->logo;
        $request->validate([
            'name' => 'required|string:255',
            'address' => 'required',
            'city' => 'required',
            'state_id' => 'required',
            'zip' => 'required',
            'terms' => 'required',
        ]);

        $company -> update([
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state_id' => $request->state_id,
            'zip_code' => $request->zip,
            'terms' => $request->terms,
        ]);

        if ($request->hasFile('logo')) {
            Storage::delete($company->logo);
            $logo = $request->file('logo')->store('companies');
            $company->update([
                'logo' => $logo
            ]);
        }

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
