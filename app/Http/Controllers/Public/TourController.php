<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Services\TourService;

class TourController extends Controller
{
    public function __construct(private TourService $tourService)
    {}

    public function show(string $slug)
    {
        $attraction = Attraction::published()
            ->with(['publishedScenes.hotspots.targetScene', 'state', 'category'])
            ->where('slug', $slug)
            ->where('tour_available', true)
            ->firstOrFail();

        $tourConfig = $this->tourService->buildTourConfig($attraction);
        $sceneList = $this->tourService->getSceneList($attraction);

        return view('public.tour', compact('attraction', 'tourConfig', 'sceneList'));
    }
}
