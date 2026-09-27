<?php

namespace App\Services;

use App\Models\TourSession;
use App\Models\SceneView;
use App\Models\Attraction;
use App\Models\Scene;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getOverviewStats(): array
    {
        return [
            'total_attractions' => Attraction::published()->count(),
            'total_scenes' => Scene::where('status', 'published')->count(),
            'total_tours' => TourSession::count(),
            'total_views' => Attraction::sum('view_count'),
            'avg_load_time' => round(TourSession::whereNotNull('load_time_ms')->avg('load_time_ms'), 0),
            'avg_fps' => round(TourSession::whereNotNull('avg_fps')->avg('avg_fps'), 1),
            'avg_transition' => round(TourSession::whereNotNull('transition_time_ms')->avg('transition_time_ms'), 0),
            'mobile_sessions' => TourSession::where('device_type', 'mobile')->count(),
            'desktop_sessions' => TourSession::where('device_type', 'desktop')->count(),
            'tablet_sessions' => TourSession::where('device_type', 'tablet')->count(),
        ];
    }

    public function getTopAttractions(int $limit = 10): array
    {
        return Attraction::published()
            ->orderByDesc('view_count')
            ->limit($limit)
            ->get(['id', 'name', 'slug', 'view_count', 'thumbnail'])
            ->toArray();
    }

    public function recordSession(array $data): TourSession
    {
        return TourSession::create($data);
    }

    public function updateSession(string $uuid, array $data): void
    {
        TourSession::where('session_uuid', $uuid)->update($data);
    }

    public function recordSceneView(int $sessionId, int $sceneId): void
    {
        SceneView::create([
            'tour_session_id' => $sessionId,
            'scene_id' => $sceneId,
            'viewed_at' => now(),
        ]);
    }
}
