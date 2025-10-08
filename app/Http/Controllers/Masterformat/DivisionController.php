<?php

namespace App\Http\Controllers\Masterformat;

use Inertia\Inertia;
use App\Models\CsiSection;
use App\Models\CsiDivision;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $divisions = CsiDivision::with('csi_section', 'csi_section.csi_subsection')->get();
        $sections = CsiSection::with('csi_division', 'csi_subsection')->get();
        return Inertia::render('Masterformat/Index', compact('divisions', 'sections'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
