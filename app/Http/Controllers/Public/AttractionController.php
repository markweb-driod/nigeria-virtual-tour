<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\Category;
use App\Models\State;
use Illuminate\Http\Request;

class AttractionController extends Controller
{
    public function index(Request $request)
    {
        $query = Attraction::published()->with(['category', 'state']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%")
                  ->orWhereHas('state', fn($s) => $s->where('name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->filled('state')) {
            $query->whereHas('state', fn($q) => $q->where('slug', $request->state));
        }

        if ($request->filled('tour')) {
            $query->withTour();
        }

        if ($request->filled('featured')) {
            $query->featured();
        }

        $sort = $request->get('sort', 'popular');
        match ($sort) {
            'newest' => $query->latest(),
            'name' => $query->orderBy('name'),
            default => $query->orderByDesc('view_count'),
        };

        $attractions = $query->paginate(12)->withQueryString();
        $categories = Category::all();
        $states = State::orderBy('name')->get();

        return view('public.explore', compact('attractions', 'categories', 'states'));
    }

    public function show(string $slug)
    {
        $attraction = Attraction::published()
            ->with(['category', 'state', 'publishedScenes', 'mediaItems'])
            ->where('slug', $slug)
            ->firstOrFail();

        $attraction->incrementViews();

        $relatedAttractions = Attraction::published()
            ->where('category_id', $attraction->category_id)
            ->where('id', '!=', $attraction->id)
            ->limit(4)
            ->get();

        return view('public.attraction', compact('attraction', 'relatedAttractions'));
    }

    public function search(Request $request)
    {
        return $this->index($request);
    }

    public function byCategory(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $attractions = Attraction::published()
            ->where('category_id', $category->id)
            ->with(['category', 'state'])
            ->orderByDesc('view_count')
            ->paginate(12);
        $categories = Category::all();
        $states = State::orderBy('name')->get();
        return view('public.explore', compact('attractions', 'categories', 'states', 'category'));
    }

    public function byState(string $slug)
    {
        $state = State::where('slug', $slug)->firstOrFail();
        $attractions = Attraction::published()
            ->where('state_id', $state->id)
            ->with(['category', 'state'])
            ->orderByDesc('view_count')
            ->paginate(12);
        $categories = Category::all();
        $states = State::orderBy('name')->get();
        return view('public.explore', compact('attractions', 'categories', 'states', 'state'));
    }
}
