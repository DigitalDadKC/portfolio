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

        return Inertia::render('masterformat/Index', compact('divisions', 'sections'));
    }
}
