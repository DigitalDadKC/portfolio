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
        return Inertia::render('admin/features/Index', [
            'features' => FeatureResource::collection(Feature::orderBy('order')->get())
        ]);
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
        $features = Feature::orderBy('order')->get();
        $features->map(function($feature) use ($request) {
            $target_feature = array_find($request->features ?? [], fn($item) => $item['id'] == $feature->id);
            if($target_feature) {
                $feature->update(['order' => $target_feature['order']]);
            };
        });

        return back();
    }
}
