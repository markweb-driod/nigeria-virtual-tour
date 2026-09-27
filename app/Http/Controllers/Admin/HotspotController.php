<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotspot;
use App\Models\Scene;
use Illuminate\Http\Request;

class HotspotController extends Controller
{
    public function store(Request $request, Scene $scene)
    {
        $validated = $request->validate([
            'type' => 'required|in:scene,info,media',
            'yaw' => 'required|numeric',
            'pitch' => 'required|numeric',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_scene_id' => 'nullable|exists:scenes,id',
            'icon' => 'nullable|string|max:100',
        ]);

        $hotspot = $scene->hotspots()->create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'hotspot' => $hotspot->load('targetScene'),
            ]);
        }

        return back()->with('success', 'Hotspot created.');
    }

    public function update(Request $request, Hotspot $hotspot)
    {
        $validated = $request->validate([
            'type' => 'required|in:scene,info,media',
            'yaw' => 'required|numeric',
            'pitch' => 'required|numeric',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target_scene_id' => 'nullable|exists:scenes,id',
            'icon' => 'nullable|string|max:100',
        ]);

        $hotspot->update($validated);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'hotspot' => $hotspot]);
        }

        return back()->with('success', 'Hotspot updated.');
    }

    public function destroy(Hotspot $hotspot)
    {
        $hotspot->delete();
        if (request()->expectsJson()) {
            return response()->json(['success' => true]);
        }
        return back()->with('success', 'Hotspot deleted.');
    }
}
