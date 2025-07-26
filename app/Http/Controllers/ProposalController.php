<?php

namespace App\Http\Controllers;

use App\Models\Job;
use App\Models\Line;
use Inertia\Inertia;
use App\Models\Scope;
use App\Models\State;
use App\Models\Company;
use App\Models\Proposal;
use Illuminate\Http\Request;
use App\Enums\ProposalTypeEnum;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\UnitOfMeasurement;
use App\Http\Resources\JobResource;
use App\Http\Resources\LineResource;
use App\Http\Resources\ScopeResource;
use App\Http\Resources\StateResource;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\ProposalResource;
use App\Http\Resources\UnitofmeasurementResource;

class ProposalController extends Controller
{
    private $ref_state;
    private $ref_uom;
    private $ref_line;
    private $ref_scope;
    private $ref_proposal;
    private $ref_job;

    public function __construct()
    {
        $this->ref_state = collect([['id' => NULL, 'abbr' => '', 'state' => '']])->mapInto(State::class)->first();
        $this->ref_uom = collect([['id' => NULL, 'UOM' => '']])->mapInto(UnitOfMeasurement::class)->first();
        $this->ref_line = collect([['id' => NULL, 'description' => '', 'unit_of_measurement' => $this->ref_uom, 'price' => NULL, 'quantity' => NULL, 'total' => NULL, 'days' => NULL]])->mapInto(Line::class);
        $this->ref_scope = collect([['id' => NULL, 'name' => '', 'area' => NULL, 'days' => NULL, 'total' => NULL, 'lines' => $this->ref_line]])->mapInto(Scope::class);
        $this->ref_proposal = collect([['id' => NULL, 'name' => '', 'contingency' => 0, 'type' => 'Base']])->mapInto(Proposal::class)->first();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Job $job, Proposal $proposal)
    {
        if(!$proposal->id) {
            $proposal = Proposal::create([
                'job_id' => $job->id,
            ]);
        }
        $proposal->load('job', 'job.state');
        // $proposal = $this->ref_proposal;
        // $proposal->job = JobResource::make(Job::with('state')->where('id', $job->id)->get())->first();
        // $proposal->scopes = [
        //     ScopeResource::make(new Scope([
        //         'lines' => [
        //             LineResource::make(new Line([
        //                 'unit_of_measurement' => UnitofmeasurementResource::make(new UnitOfMeasurement())
        //             ]))
        //         ]
        //     ]))
        // ];

        return to_route('proposals.edit', ['proposal' => $proposal->id]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Job $job, Request $request)
    {
        $request->validate([
            'proposal.name' => 'required',
            'proposal.type' => 'required',
            'proposal.scopes.*.lines.*.description' => 'required',
            'proposal.scopes.*.lines.*.price' => 'required',
            'proposal.scopes.*.lines.*.quantity' => 'required',
            'proposal.scopes.*.lines.*.unit_of_measurement.id' => 'required'
        ]);

        $proposal = Proposal::create([
            'name' => $request->proposal['name'],
            'contingency' => $request->proposal['contingency'],
            'job_id' => $request->proposal['job']['id'],
            'type' => $request->proposal['type'],
        ]);

        foreach ($request->proposal['scopes'] as $scope) {
            $newScope = Scope::create(
                [
                    'name' => $scope['name'],
                    'area' => $scope['area'],
                    'proposal_id' => $proposal->id
                ]
            );

            foreach ($scope['lines'] as $key=>$line) {
                Line::create(
                    [
                        'order' => $key,
                        'description' => $line['description'],
                        'unit_of_measurement_id' => $line['unit_of_measurement']['id'],
                        'days' => $line['days'],
                        'price' => $line['price']*100,
                        'quantity' => $line['quantity']*100,
                        'scope_id' => $newScope->id
                    ]
                );
            }
        }

        return to_route('estimating.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proposal $proposal)
    {
        $proposal = ProposalResource::collection(Proposal::where('id', $proposal->id)->with('job', 'scopes', 'scopes.lines', 'scopes.lines.unit_of_measurement')->get())->first();
        // if (!$proposal->scopes->count()) {
        //     $proposal['scopes']->push($this->ref_scope[0]);
        // }
        // $proposal->scopes->map(fn($scope) => (!$scope->lines->count())
        //  ? $scope->lines->push($this->ref_line[0])
        //  : $scope->lines->map(function($line) {
        //     $line['price'] = $line['price']/100;
        //     $line['quantity'] = $line['quantity']/100;
        //     }
        // ));

        return Inertia::render('Estimating/Proposal', [
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

        // return Inertia::render('Estimating/Proposal', [
        //     'new' => false,
        //     'proposal' => $proposal,
        //     'company' => CompanyResource::collection(Company::all())->first(),
        //     'states' => State::get(),
        //     'types' => ProposalTypeEnum::cases(),
        //     'unit_of_measurements' => UnitOfMeasurement::get(),
        // ]);

        // $request->validate([
        //     'proposal.name' => 'required',
        //     'proposal.type' => 'required',
        //     'proposal.scopes.*.name' => '',
        //     'proposal.scopes.*.area' => '',
        //     'proposal.scopes.*.lines.*.description' => 'required',
        //     'proposal.scopes.*.lines.*.price' => 'required',
        //     'proposal.scopes.*.lines.*.quantity' => 'required',
        //     'proposal.scopes.*.lines.*.unit_of_measurement.id' => 'required'
        // ]);

        // $proposal->update([
        //     'name' => $request->proposal['name'],
        //     'contingency' => $request->proposal['contingency'],
        //     'type' => $request->proposal['type'],
        //     'exclusions' => $request->proposal['exclusions'],
        // ]);

        // Scope::where('proposal_id', $proposal->id)->whereNotIn('id', array_column($request->proposal['scopes'], 'id'))->delete();
        // foreach ($request->proposal['scopes'] as $scope) {
        //     $newScope = Scope::updateOrCreate(
        //         ['id' => $scope['id']],
        //         [
        //             'name' => $scope['name'],
        //             'area' => $scope['area'],
        //             'proposal_id' => $proposal->id
        //         ]
        //     );

        //     Line::whereIn('id', array_diff(Line::where(['scope_id' => $scope['id']])->pluck('id')->toArray(), array_column($scope['lines'], 'id')))->delete();
        //     foreach ($scope['lines'] as $key=>$line) {
        //         Line::updateOrCreate(
        //             ['id' => $line['id']],
        //             [
        //                 'order' => $key,
        //                 'description' => $line['description'],
        //                 'unit_of_measurement_id' => $line['unit_of_measurement']['id'],
        //                 'days' => $line['days'],
        //                 'price' => $line['price']*100,
        //                 'quantity' => $line['quantity']*100,
        //                 'scope_id' => $newScope->id
        //             ]
        //         );
        //     }
        // }

        // if ($request->pdf === 'false') {
        //     return to_route('estimating.index');
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return to_route('estimating.index');
    }

    public function createScope(Proposal $proposal) {
        Scope::create([
            'proposal_id' => $proposal->id,
        ]);
    }

    public function updateScope(Request $request, Scope $scope) {
        $scope->update([
            'name' => $request->name,
            'area' => $request->area,
        ]);
    }

    public function destroyScope(Scope $scope) {
        $scope->delete();
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
