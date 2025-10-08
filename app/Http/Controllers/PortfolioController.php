<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Inertia\Inertia;
use App\Models\Contract;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\MaterialCategory;
use App\Models\MaterialEffectiveDate;
use App\Http\Resources\ContractResource;
use App\Http\Resources\MaterialEffectiveDateResource;

class PortfolioController extends Controller
{
    public function index()
    {
        return Inertia::render('Portfolio/Index');
    }
}
