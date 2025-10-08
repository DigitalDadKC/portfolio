<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Skill;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SkillResource;
use App\Http\Resources\ProjectResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = ProjectResource::collection(Project::with('skills')->orderBy('project_order')->get());
        return Inertia::render('admin/projects/Index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('admin/projects/Project', [
            'new' => true,
            'skills' => Skill::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3',
            'skills.*' => 'required',
            'project_url' => 'required'
        ]);

        $image = NULL;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('projects');
        }

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image,
            'project_url' => $request->project_url,
            'project_order' => Project::count() + 1,
            'active' => $request->active,
        ]);
        $project->skills()->sync($request->skills);

        return Redirect::route('projects.index')->with('message', 'Project created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $project->skills;
        return Inertia::render('admin/projects/Project', [
            'new' => false,
            'project' => ProjectResource::make($project),
            'skills' => SkillResource::collection(Skill::all()),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'name' => 'required|min:3',
            'skills.*' => 'required',
        ]);

        $image = $project->image;
        if ($request->hasFile('image')) {
            if($image) {
                Storage::delete($project->image);
            }
            $image = $request->file('image')->store('projects');
        }

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'project_url' => $request->project_url,
            'image' => $image,
            'active' => $request->active,
        ]);
        $project->skills()->sync($request->skills);

        return Redirect::route('projects.index')->with('message', 'Project updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        Storage::delete($project->image);
        $project->delete();
        return Redirect::back()->with('message', 'Project deleted');
    }

    public function sort(Request $request)
    {
        $current = Project::get();
        $current->map(function ($project) use ($request) {
            $order = $request->projects[array_search($project->id, array_column($request->projects, 'id'))]['project_order'];
            $project->update(['project_order' => $order]);
        });
    }
}
