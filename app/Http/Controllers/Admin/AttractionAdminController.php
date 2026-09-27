<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\Category;
use App\Models\State;
use App\Services\PanoramaService;
use Illuminate\Http\Request;

class AttractionAdminController extends Controller
{
    public function __construct(private PanoramaService $panoramaService)
    {}

    public function index(Request $request)
    {
        $query = Attraction::with(['category', 'state'])->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $attractions = $query->paginate(15)->withQueryString();
        $categories = Category::all();
        $states = State::orderBy('name')->get();

        return view('admin.attractions.index', compact('attractions', 'categories', 'states'));
    }

    public function create()
    {
        $categories = Category::all();
        $states = State::orderBy('name')->get();
        return view('admin.attractions.create', compact('categories', 'states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'state_id' => 'required|exists:states,id',
            'history' => 'nullable|string',
            'interesting_facts' => 'nullable|string',
            'address' => 'nullable|string',
            'lga' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'visiting_hours' => 'nullable|string',
            'entry_fee' => 'nullable|string',
            'is_featured' => 'boolean',
            'status' => 'required|in:published,draft,archived',
            'thumbnail' => 'nullable|image|max:5120',
            'hero_image' => 'nullable|image|max:10240',
            'audio' => 'nullable|mimes:mp3,wav,ogg|max:20480',
        ]);

        $attraction = new Attraction($validated);
        $attraction->is_featured = $request->boolean('is_featured');

        if ($request->hasFile('thumbnail')) {
            $attraction->thumbnail = $this->panoramaService->storeThumbnail(
                $request->file('thumbnail'), $attraction->slug ?? \Str::slug($request->name)
            );
        }

        if ($request->hasFile('hero_image')) {
            $attraction->hero_image = $this->panoramaService->storeHeroImage(
                $request->file('hero_image'), $attraction->slug ?? \Str::slug($request->name)
            );
        }

        if ($request->hasFile('audio')) {
            $attraction->audio = $this->panoramaService->storeAudio(
                $request->file('audio'), $attraction->slug ?? \Str::slug($request->name)
            );
        }

        $attraction->save();

        return redirect()->route('admin.attractions.show', $attraction)
            ->with('success', 'Attraction created successfully.');
    }

    public function show(Attraction $attraction)
    {
        $attraction->load(['category', 'state', 'scenes.hotspots', 'mediaItems']);
        return view('admin.attractions.show', compact('attraction'));
    }

    public function edit(Attraction $attraction)
    {
        $categories = Category::all();
        $states = State::orderBy('name')->get();
        return view('admin.attractions.edit', compact('attraction', 'categories', 'states'));
    }

    public function update(Request $request, Attraction $attraction)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'state_id' => 'required|exists:states,id',
            'history' => 'nullable|string',
            'interesting_facts' => 'nullable|string',
            'address' => 'nullable|string',
            'lga' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'visiting_hours' => 'nullable|string',
            'entry_fee' => 'nullable|string',
            'is_featured' => 'boolean',
            'status' => 'required|in:published,draft,archived',
            'thumbnail' => 'nullable|image|max:5120',
            'hero_image' => 'nullable|image|max:10240',
            'audio' => 'nullable|mimes:mp3,wav,ogg|max:20480',
        ]);

        $attraction->fill($validated);
        $attraction->is_featured = $request->boolean('is_featured');

        if ($request->hasFile('thumbnail')) {
            $attraction->thumbnail = $this->panoramaService->storeThumbnail(
                $request->file('thumbnail'), $attraction->slug
            );
        }
        if ($request->hasFile('hero_image')) {
            $attraction->hero_image = $this->panoramaService->storeHeroImage(
                $request->file('hero_image'), $attraction->slug
            );
        }
        if ($request->hasFile('audio')) {
            $attraction->audio = $this->panoramaService->storeAudio(
                $request->file('audio'), $attraction->slug
            );
        }

        $attraction->save();

        return redirect()->route('admin.attractions.show', $attraction)
            ->with('success', 'Attraction updated successfully.');
    }

    public function destroy(Attraction $attraction)
    {
        $attraction->delete();
        return redirect()->route('admin.attractions.index')
            ->with('success', 'Attraction deleted.');
    }

    public function publish(Attraction $attraction)
    {
        $attraction->update(['status' => $attraction->status === 'published' ? 'draft' : 'published']);
        $status = $attraction->status === 'published' ? 'published' : 'unpublished';
        return back()->with('success', "Attraction {$status} successfully.");
    }

    public function toggleFeatured(Attraction $attraction)
    {
        $attraction->update(['is_featured' => !$attraction->is_featured]);
        return back()->with('success', 'Featured status updated.');
    }
}
