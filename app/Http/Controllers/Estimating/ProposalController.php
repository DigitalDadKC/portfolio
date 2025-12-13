<?php

namespace App\Http\Controllers\Estimating;

use App\Models\Job;
use App\Models\Line;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Scope;
use App\Models\State;
use App\Models\Company;
use App\Models\Proposal;
use Illuminate\Http\Request;
use App\Enums\ProposalTypeEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\UnitOfMeasurement;
use App\Http\Controllers\Controller;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\ProposalResource;

class ProposalController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Job $job, Request $request)
    {
        $proposal = Proposal::create([
            'job_id' => $job->id,
            'user_id' => User::query()->inRandomOrder()->first()->id,
        ]);

        return to_route('proposals.edit', ['proposal' => $proposal->id]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proposal $proposal)
    {
        $proposal = ProposalResource::collection(Proposal::where('id', $proposal->id)->with('job', 'scopes', 'scopes.lines', 'scopes.lines.unit_of_measurement')->get())->first();

        return Inertia::render('estimating/jobs/Proposal', [
            'new' => false,
            'proposal' => $proposal,
            'company' => CompanyResource::collection(Company::all())->first(),
            'states' => State::get(),
            'types' => ProposalTypeEnum::cases(),
            'unit_of_measurements' => UnitOfMeasurement::get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proposal $proposal)
    {
        $proposal->update([
            'name' => $request->name,
            'type' => $request->type,
            'exclusions' => $request->exclusions
        ]);

        return to_route('proposals.edit', ['proposal' => $proposal->id]);
    }

    /**
     * Remove the specified resource from storage.
     */

    public function updateProposal(Request $request, Proposal $proposal) {
        $proposal->update([
            'name' => $request->name,
            'type' => $request->type,
            'exclusions' => $request->exclusions
        ]);
    }
    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return to_route('jobs.index');
    }

    public function createScope(Proposal $proposal) {
        Scope::create([
            'proposal_id' => $proposal->id,
        ]);

        return back();
    }

    public function updateScope(Request $request, Scope $scope) {
        $scope->update([
            'name' => $request->name,
            'area' => $request->area,
        ]);

        return back();
    }

    public function destroyScope(Scope $scope) {
        $scope->delete();

        return back();
    }

    public function createLine(Scope $scope) {
        $line_count = Line::where('scope_id', $scope->id)->count();
        Line::create([
            'unit_of_measurement_id' => 1,
            'order' => $line_count,
            'scope_id' => $scope->id,
        ]);
    }

    public function updateLine(Request $request, Line $line) {
        $line->update([
            'description' => $request->description,
            'unit_of_measurement_id' => $request->unit_of_measurement_id,
            'price' => $request->price,
            'quantity' => $request->quantity
        ]);
    }

    public function destroyLine(Line $line) {
        $line->delete();
        $lines = Line::where('scope_id', $line->scope_id)->get();
        $lines->each(function($item, $key) {
            $item->order = $key;
            $item->save();
        });
    }

    public function sortLines(Request $request, Scope $scope) {
        $scope->load('lines');
        $scope->lines->map(function($line, $key) use ($request) {
            $new_order = array_search($line->id, array_column($request->lines, 'id'));
            $line->update(['order' => $new_order]);
        });
    }

    public function downloadPDF(Request $request, Proposal $proposal)
    {
        $proposal->load('job', 'scopes', 'scopes.lines', 'scopes.lines.unit_of_measurement');
        $data = [
            'proposal' => $proposal,
            'scopes' => $proposal->scopes,
            'company' => CompanyResource::collection(Company::all())->first()
        ];
        $pdf = Pdf::loadView('exports.estimate-export', $data);
        return $pdf->download('Estimate.pdf');
    }

    public function browserPDF(Request $request, Proposal $proposal)
    {
        $proposal->load('job', 'scopes', 'scopes.lines', 'scopes.lines.unit_of_measurement');

        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed'=> TRUE
            ]
        ]);

        $data = [
            'proposal' => $proposal,
            'scopes' => $proposal->scopes,
            'company' => CompanyResource::collection(Company::all())->first(),
        ];

        $pdf = Pdf::loadView('exports.estimate-export', $data);
        return $pdf->stream('estimating-in-browser.pdf');
    }
}
