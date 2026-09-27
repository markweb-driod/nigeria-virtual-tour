<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use App\Models\TourSession;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct(private AnalyticsService $analytics)
    {}

    public function index()
    {
        $stats = $this->analytics->getOverviewStats();
        $topAttractions = $this->analytics->getTopAttractions(10);

        $deviceBreakdown = [
            'mobile' => TourSession::where('device_type', 'mobile')->count(),
            'desktop' => TourSession::where('device_type', 'desktop')->count(),
            'tablet' => TourSession::where('device_type', 'tablet')->count(),
        ];

        $totalSessions = array_sum($deviceBreakdown);

        return view('admin.analytics', compact('stats', 'topAttractions', 'deviceBreakdown', 'totalSessions'));
    }
}
