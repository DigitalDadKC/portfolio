<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Skill;
use App\Models\Feature;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Resources\SkillResource;
use App\Http\Resources\FeatureResource;
use App\Http\Resources\ProjectResource;

class WelcomeController extends Controller
{
    public function welcome()
    {
        $skills = SkillResource::collection(Skill::all());
        $projects = ProjectResource::collection(Project::with('skills')->where('active', 1)->orderBy('order')->get());
        $features = FeatureResource::collection(Feature::orderBy('order')->get());

        return Inertia::render('Welcome', compact('skills', 'projects', 'features'));
    }
}
