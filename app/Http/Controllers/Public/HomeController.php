<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\Category;
use App\Models\State;

class HomeController extends Controller
{
    public function index()
    {
        $featuredAttractions = Attraction::published()
            ->featured()
            ->with(['category', 'state'])
            ->withTour()
            ->orderByDesc('view_count')
            ->limit(6)
            ->get();

        $popularAttractions = Attraction::published()
            ->with(['category', 'state'])
            ->orderByDesc('view_count')
            ->limit(8)
            ->get();

        $categories = Category::withCount(['attractions' => function ($q) {
            $q->published();
        }])->orderByDesc('attractions_count')->get();

        $states = State::withCount(['attractions' => function ($q) {
            $q->published();
        }])->orderByDesc('attractions_count')->limit(12)->get();

        $totalAttractions = Attraction::published()->count();
        $totalTours = Attraction::published()->withTour()->count();

        return view('public.home', compact(
            'featuredAttractions',
            'popularAttractions',
            'categories',
            'states',
            'totalAttractions',
            'totalTours'
        ));
    }
}
