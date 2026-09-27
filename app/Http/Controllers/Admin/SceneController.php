<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\Scene;
use App\Services\PanoramaService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SceneController extends Controller
{
    public function __construct(private PanoramaService $panoramaService)
    {}

    public function index(Attraction $attraction)
    {
        $scenes = $attraction->scenes()->with('hotspots')->get();
        return view('admin.scenes.index', compact('attraction', 'scenes'));
    }

    public function create(Attraction $attraction)
    {
        return view('admin.scenes.create', compact('attraction'));
    }

    public function store(Request $request, Attraction $attraction)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'initial_yaw' => 'nullable|numeric|between:-180,180',
            'initial_pitch' => 'nullable|numeric|between:-90,90',
            'initial_hfov' => 'nullable|numeric|between:30,150',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:published,draft',
            'is_default' => 'boolean',
            'panorama' => 'nullable|image|max:51200',
            'thumbnail' => 'nullable|image|max:5120',
            'audio_narration' => 'nullable|mimes:mp3,wav,ogg|max:20480',
        ]);

        $slug = Str::slug($request->name);
        $sortOrder = $attraction->scenes()->max('sort_order') + 1;

        $scene = new Scene($validated);
        $scene->attraction_id = $attraction->id;
        $scene->slug = $slug;
        $scene->sort_order = $sortOrder;
        $scene->is_default = $request->boolean('is_default');

        if ($request->hasFile('panorama')) {
            $validation = $this->panoramaService->validatePanorama($request->file('panorama'));
            if (!$validation['valid']) {
                return back()->withErrors(['panorama' => $validation['message']]);
            }
            $scene->panorama_path = $this->panoramaService->storePanorama(
                $request->file('panorama'), $attraction->slug, $slug
            );
            $attraction->update(['tour_available' => true]);
        }

        if ($request->hasFile('thumbnail')) {
            $scene->thumbnail = $this->panoramaService->storeSceneThumbnail(
                $request->file('thumbnail'), $attraction->slug, $slug
            );
        }

        if ($request->hasFile('audio_narration')) {
            $scene->audio_narration = $this->panoramaService->storeAudio(
                $request->file('audio_narration'), $attraction->slug
            );
        }

        if ($scene->is_default) {
            $attraction->scenes()->update(['is_default' => false]);
        }

        $scene->save();

        return redirect()->route('admin.scenes.index', $attraction)
            ->with('success', 'Scene created successfully.');
    }

    public function edit(Scene $scene)
    {
        $attraction = $scene->attraction;
        $scenes = $attraction->scenes()->where('id', '!=', $scene->id)->get();
        $hotspots = $scene->hotspots()->with('targetScene')->get();
        return view('admin.scenes.edit', compact('scene', 'attraction', 'scenes', 'hotspots'));
    }

    public function update(Request $request, Scene $scene)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'initial_yaw' => 'nullable|numeric|between:-180,180',
            'initial_pitch' => 'nullable|numeric|between:-90,90',
            'initial_hfov' => 'nullable|numeric|between:30,150',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:published,draft',
            'is_default' => 'boolean',
            'panorama' => 'nullable|image|max:51200',
            'thumbnail' => 'nullable|image|max:5120',
            'audio_narration' => 'nullable|mimes:mp3,wav,ogg|max:20480',
        ]);

        $scene->fill($validated);
        $scene->is_default = $request->boolean('is_default');

        if ($request->hasFile('panorama')) {
            $validation = $this->panoramaService->validatePanorama($request->file('panorama'));
            if (!$validation['valid']) {
                return back()->withErrors(['panorama' => $validation['message']]);
            }
            $scene->panorama_path = $this->panoramaService->storePanorama(
                $request->file('panorama'), $scene->attraction->slug, $scene->slug
            );
            $scene->attraction->update(['tour_available' => true]);
        }

        if ($request->hasFile('thumbnail')) {
            $scene->thumbnail = $this->panoramaService->storeSceneThumbnail(
                $request->file('thumbnail'), $scene->attraction->slug, $scene->slug
            );
        }

        if ($request->hasFile('audio_narration')) {
            $scene->audio_narration = $this->panoramaService->storeAudio(
                $request->file('audio_narration'), $scene->attraction->slug
            );
        }

        if ($scene->is_default) {
            $scene->attraction->scenes()->where('id', '!=', $scene->id)->update(['is_default' => false]);
        }

        $scene->save();

        return redirect()->route('admin.scenes.edit', $scene)
            ->with('success', 'Scene updated successfully.');
    }

    public function destroy(Scene $scene)
    {
        $attraction = $scene->attraction;
        $scene->delete();
        return redirect()->route('admin.scenes.index', $attraction)
            ->with('success', 'Scene deleted.');
    }

    public function setDefault(Scene $scene)
    {
        $scene->attraction->scenes()->update(['is_default' => false]);
        $scene->update(['is_default' => true]);
        return back()->with('success', 'Default scene updated.');
    }

    public function reorder(Request $request)
    {
        $request->validate(['scenes' => 'required|array']);
        foreach ($request->scenes as $index => $sceneId) {
            Scene::where('id', $sceneId)->update(['sort_order' => $index]);
        }
        return response()->json(['success' => true]);
    }
}
