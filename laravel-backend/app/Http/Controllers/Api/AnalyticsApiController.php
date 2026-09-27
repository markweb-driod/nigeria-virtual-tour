<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use App\Models\Attraction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AnalyticsApiController extends Controller
{
    public function __construct(private AnalyticsService $analytics)
    {}

    public function startSession(Request $request)
    {
        $request->validate(['attraction_id' => 'required|exists:attractions,id']);

        $ua = $request->userAgent() ?? '';
        $deviceType = $this->detectDevice($ua);
        $browser = $this->detectBrowser($ua);

        $session = $this->analytics->recordSession([
            'attraction_id' => $request->attraction_id,
            'session_uuid' => Str::uuid(),
            'device_type' => $deviceType,
            'browser' => $browser,
            'started_at' => now(),
        ]);

        Attraction::find($request->attraction_id)->increment('view_count');

        return response()->json(['session_uuid' => $session->session_uuid, 'session_id' => $session->id]);
    }

    public function endSession(Request $request)
    {
        $request->validate([
            'session_uuid' => 'required|string',
            'avg_fps' => 'nullable|numeric',
            'load_time_ms' => 'nullable|numeric',
            'transition_time_ms' => 'nullable|numeric',
            'scenes_visited' => 'nullable|integer',
            'completed' => 'nullable|boolean',
        ]);

        $this->analytics->updateSession($request->session_uuid, [
            'avg_fps' => $request->avg_fps,
            'load_time_ms' => $request->load_time_ms,
            'transition_time_ms' => $request->transition_time_ms,
            'scenes_visited' => $request->scenes_visited ?? 0,
            'completed' => $request->boolean('completed'),
            'ended_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }

    public function recordSceneView(Request $request)
    {
        $request->validate([
            'tour_session_id' => 'required|exists:tour_sessions,id',
            'scene_id' => 'required|exists:scenes,id',
        ]);

        $this->analytics->recordSceneView($request->tour_session_id, $request->scene_id);

        return response()->json(['success' => true]);
    }

    private function detectDevice(string $ua): string
    {
        if (preg_match('/Mobile|Android|iPhone/i', $ua)) return 'mobile';
        if (preg_match('/iPad|Tablet/i', $ua)) return 'tablet';
        return 'desktop';
    }

    private function detectBrowser(string $ua): string
    {
        if (str_contains($ua, 'Chrome')) return 'Chrome';
        if (str_contains($ua, 'Firefox')) return 'Firefox';
        if (str_contains($ua, 'Safari')) return 'Safari';
        if (str_contains($ua, 'Edge')) return 'Edge';
        return 'Other';
    }
}
