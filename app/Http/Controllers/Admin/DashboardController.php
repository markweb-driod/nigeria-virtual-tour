<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\Scene;
use App\Models\Hotspot;
use App\Models\TourSession;
use App\Services\AnalyticsService;

class DashboardController extends Controller
{
    public function __construct(private AnalyticsService $analytics)
    {}

    public function index()
    {
        $stats = [
            'attractions' => Attraction::count(),
            'scenes' => Scene::count(),
            'hotspots' => Hotspot::count(),
            'tour_views' => TourSession::count(),
            'published' => Attraction::where('status', 'published')->count(),
            'draft' => Attraction::where('status', 'draft')->count(),
        ];

        $analyticsStats = $this->analytics->getOverviewStats();
        $topAttractions = $this->analytics->getTopAttractions(5);

        $recentAttractions = Attraction::with(['category', 'state'])
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'analyticsStats', 'topAttractions', 'recentAttractions'));
    }
}
