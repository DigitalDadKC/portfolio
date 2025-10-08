<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Feature;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FeatureResource;

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Features/Index', [
            'features' => FeatureResource::collection(Feature::orderBy('order')->get())
        ]);
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
        $request->validate([
            'name' => 'required|min:3'
        ]);

        $count = Feature::count();
        Feature::create([
            'name' => $request->name,
            'order' => $count+1,
        ]);

        return back();
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
    public function update(Request $request, Feature $feature)
    {
        $request->validate([
            'name' => 'required|min:3'
        ]);

        $feature->update([
            'name' => $request->name,
        ]);

        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Feature $feature)
    {
        $feature->delete();
        $this->sort($request);
        return back();
    }

    public function sort(Request $request)
    {
        $current = Feature::get();
        $current->map(function ($feature) use ($request) {
            $order = $request->features[array_search($feature->id, array_column($request->features, 'id'))]['order'];
            $feature->update(['order' => $order]);
        });

        return back();
    }
}
