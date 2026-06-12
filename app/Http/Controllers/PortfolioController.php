<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PortfolioController extends Controller
{
    public function index()
    {
        return Inertia::render('portfolio/Index');
    }
}
