@extends('layouts.admin')
@section('title', 'Scenes - ' . $attraction->name)
@section('page-title', $attraction->name . ' — Tour Scenes')
@section('breadcrumb', 'Attractions / ' . $attraction->name . ' / Scenes')

@section('header-actions')
<a href="{{ route('admin.attractions.edit', $attraction) }}" class="btn-ghost btn-sm">Edit Attraction</a>
@if($attraction->tour_available)
<a href="{{ route('tour.show', $attraction->slug) }}" target="_blank" class="btn-secondary btn-sm">Preview Tour</a>
@endif
<a href="{{ route('admin.scenes.create', $attraction) }}" class="btn-primary btn-sm">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    Add Scene
</a>
@endsection

@section('content')
@if($scenes->count())
<div class="space-y-4" id="scenes-list">
    @foreach($scenes as $scene)
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col md:flex-row gap-4 items-start" data-scene-id="{{ $scene->id }}">
        {{-- Panorama thumb --}}
        <div class="w-full md:w-32 h-24 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
            @if($scene->thumbnail)
            <img src="{{ $scene->thumbnail_url }}" alt="{{ $scene->name }}" class="w-full h-full object-cover">
            @else
            <div class="w-full h-full flex items-center justify-center text-gray-300">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            @endif
        </div>

        <div class="flex-1 min-w-0">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <h3 class="font-heading font-bold text-dark">{{ $scene->name }}</h3>
                        @if($scene->is_default)
                        <span class="badge bg-gold-50 text-gold-600 text-xs">Default</span>
                        @endif
                        <span class="badge {{ $scene->status === 'published' ? 'badge-green' : 'bg-gray-100 text-gray-500' }} text-xs">
                            {{ ucfirst($scene->status) }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-400 font-mono">/{{ $scene->slug }}</p>
                    @if($scene->description)
                    <p class="text-sm text-gray-500 mt-1 line-clamp-1">{{ $scene->description }}</p>
                    @endif
                    <div class="flex gap-4 mt-2 text-xs text-gray-400">
                        <span>Yaw: {{ $scene->initial_yaw }}°</span>
                        <span>Pitch: {{ $scene->initial_pitch }}°</span>
                        <span>FOV: {{ $scene->initial_hfov }}°</span>
                        <span class="font-semibold {{ $scene->hotspots->count() ? 'text-forest-600' : 'text-gray-300' }}">
                            {{ $scene->hotspots->count() }} hotspot{{ $scene->hotspots->count() !== 1 ? 's' : '' }}
                        </span>
                    </div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('admin.scenes.edit', $scene) }}" class="btn-ghost btn-sm text-xs">Edit + Hotspots</a>
                    @if(!$scene->is_default)
                    <form method="POST" action="{{ route('admin.scenes.set-default', $scene) }}">
                        @csrf
                        <button class="text-xs text-gray-400 hover:text-gold-600 font-medium">Set Default</button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('admin.scenes.destroy', $scene) }}"
                          onsubmit="return confirm('Delete this scene?')">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-400 hover:text-red-600 font-medium">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div class="text-center py-20 bg-white rounded-2xl border border-gray-100">
    <div class="text-5xl mb-4">🌐</div>
    <h3 class="font-heading font-bold text-xl text-dark mb-2">No Scenes Yet</h3>
    <p class="text-gray-500 mb-6 max-w-sm mx-auto">Upload 360° panorama images to create scenes for this virtual tour.</p>
    <a href="{{ route('admin.scenes.create', $attraction) }}" class="btn-primary">Upload First Panorama</a>
</div>
@endif
@endsection
