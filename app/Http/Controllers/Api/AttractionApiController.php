<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use Illuminate\Http\Request;

class AttractionApiController extends Controller
{
    public function index(Request $request)
    {
        $attractions = Attraction::published()
            ->with(['category', 'state'])
            ->when($request->category, fn($q) => $q->whereHas('category', fn($c) => $c->where('slug', $request->category)))
            ->when($request->state, fn($q) => $q->whereHas('state', fn($s) => $s->where('slug', $request->state)))
            ->when($request->featured, fn($q) => $q->featured())
            ->when($request->tour, fn($q) => $q->withTour())
            ->orderByDesc('view_count')
            ->paginate(20);

        return response()->json($attractions);
    }

    public function show(string $slug)
    {
        $attraction = Attraction::published()
            ->with(['category', 'state', 'publishedScenes', 'mediaItems'])
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json($attraction);
    }
}
