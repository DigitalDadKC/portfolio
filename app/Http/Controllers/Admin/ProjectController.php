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
    public function index()
    {
        $projects = ProjectResource::collection(Project::with('skills')->orderBy('order')->get());
        $skills = SkillResource::collection(Skill::orderBy('name')->get());

        return Inertia::render('admin/projects/Index', compact('projects', 'skills'));
    }

    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'name' => 'required|min:3',
            'skills.*' => 'required',
            'url' => 'required'
        ]);

        $image = NULL;
        if ($request->hasFile('image')) {
            $image = $request->file('image')->store('projects');
        }

        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'url' => $request->url,
            'order' => Project::count() + 1,
            'active' => $request->active,
            'image' => $image,
        ]);
        $project->skills()->sync(array_column($request->skills, 'id'));

        return Redirect::route('projects.index')->with('message', 'Project created successfully!');
    }

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
            'url' => $request->url,
            'image' => $image,
            'active' => $request->active,
        ]);
        $project->skills()->sync(array_column($request->skills, 'id'));

        return Redirect::route('projects.index')->with('message', 'Project updated successfully!');
    }

    public function destroy(Project $project)
    {
        Storage::delete($project?->image ?? '');
        $project->delete();
        return Redirect::back()->with('message', 'Project deleted');
    }

    public function sort(Request $request)
    {
        $projects = Project::orderBy('order')->get();
        $projects->map(function($project, $key) use ($request) {
            $target_project = array_find($request->projects, fn($item) => $item['id'] == $project->id);
            $project->update(['order' => $target_project['order']]);
        });
    }
}
