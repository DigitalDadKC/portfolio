<?php

namespace App\Http\Controllers\Lodging;

use App\Http\Controllers\Controller;
use App\Models\State;
use App\Services\SamGovService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LodgingController extends Controller
{
    public function index(Request $request, SamGovService $samGov) {
        $request->validate([
            'year' => ['nullable', 'integer', 'digits:4', 'between:1900,' . date('Y')],
        ]);

        $filters = [
            'year' => $request->input('year', date('Y')),
            'state' => $request->input('state', NULL),
        ];

        if($filters['year']) {
            $abbr = State::where('id', $filters['state'])->first()?->abbr;
            $results = collect(array_filter($samGov->searchLodging($filters['year']), fn($state) => $state['State'] === $abbr))->values()->toArray();
        }

        return Inertia::render('lodging/Index', [
            'states' => State::orderBy('state')->get(),
            'filters' => fn() => $filters,
            'results' => fn() => $results ?? [],
        ]);
    }
}
