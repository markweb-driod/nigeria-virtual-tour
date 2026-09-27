<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\Scene;
use App\Services\TourService;

class TourApiController extends Controller
{
    public function __construct(private TourService $tourService)
    {}

    public function config(string $slug)
    {
        $attraction = Attraction::published()
            ->with(['publishedScenes.hotspots.targetScene'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($this->tourService->buildTourConfig($attraction));
    }

    public function scene(Scene $scene)
    {
        $scene->load(['hotspots.targetScene', 'attraction']);
        return response()->json($scene);
    }

    public function hotspots(Scene $scene)
    {
        return response()->json($scene->hotspots()->with('targetScene')->get());
    }
}
