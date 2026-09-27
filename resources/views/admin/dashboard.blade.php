@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('breadcrumb', 'Welcome back, ' . auth()->user()->name)

@section('header-actions')
<a href="{{ route('admin.attractions.create') }}" class="btn-primary btn-sm">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    New Attraction
</a>
@endsection

@section('content')

{{-- Main Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    @foreach([
        ['value' => $stats['attractions'], 'label' => 'Total Attractions', 'sub' => $stats['published'].' published', 'color' => 'bg-forest-50 text-forest-600'],
        ['value' => $stats['scenes'], 'label' => '360° Scenes', 'sub' => 'panoramic views', 'color' => 'bg-blue-50 text-blue-600'],
        ['value' => $stats['hotspots'], 'label' => 'Hotspots', 'sub' => 'navigation markers', 'color' => 'bg-gold-50 text-gold-600'],
        ['value' => number_format($stats['tour_views']), 'label' => 'Tour Sessions', 'sub' => 'all time', 'color' => 'bg-purple-50 text-purple-600'],
    ] as $stat)
    <div class="stat-card">
        <div class="flex items-start justify-between">
            <div>
                <p class="text-3xl font-heading font-bold text-dark">{{ $stat['value'] }}</p>
                <p class="text-sm font-semibold text-gray-600 mt-1">{{ $stat['label'] }}</p>
                <p class="text-xs text-gray-400 mt-0.5">{{ $stat['sub'] }}</p>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- Performance Stats --}}
@if($analyticsStats['avg_load_time'] || $analyticsStats['avg_fps'])
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Avg Load Time</p>
        <p class="text-2xl font-heading font-bold text-dark">{{ $analyticsStats['avg_load_time'] ? number_format($analyticsStats['avg_load_time']).'ms' : '—' }}</p>
        <p class="text-xs {{ ($analyticsStats['avg_load_time'] ?? 9999) < 3000 ? 'text-emerald-500' : 'text-orange-500' }} font-semibold mt-1">
            {{ ($analyticsStats['avg_load_time'] ?? 9999) < 3000 ? '✓ Under 3s target' : '↑ Above 3s target' }}
        </p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Avg Scene Transition</p>
        <p class="text-2xl font-heading font-bold text-dark">{{ $analyticsStats['avg_transition'] ? number_format($analyticsStats['avg_transition']).'ms' : '—' }}</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Avg Viewer FPS</p>
        <p class="text-2xl font-heading font-bold text-dark">{{ $analyticsStats['avg_fps'] ? $analyticsStats['avg_fps'].' FPS' : '—' }}</p>
        <p class="text-xs {{ ($analyticsStats['avg_fps'] ?? 0) >= 30 ? 'text-emerald-500' : 'text-orange-500' }} font-semibold mt-1">
            {{ ($analyticsStats['avg_fps'] ?? 0) >= 30 ? '✓ Above 30 FPS target' : '↓ Below 30 FPS target' }}
        </p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Mobile Sessions</p>
        <p class="text-2xl font-heading font-bold text-dark">
            {{ $analyticsStats['total_views'] > 0 ? round(($analyticsStats['mobile_sessions'] / max($analyticsStats['mobile_sessions'] + $analyticsStats['desktop_sessions'] + $analyticsStats['tablet_sessions'], 1)) * 100) : '0' }}%
        </p>
    </div>
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Top Attractions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Top Attractions</h3>
        @if(count($topAttractions))
        <div class="space-y-3">
            @foreach($topAttractions as $idx => $item)
            <div class="flex items-center gap-3">
                <span class="w-6 h-6 rounded-lg bg-gray-100 text-gray-500 text-xs font-bold flex items-center justify-center">{{ $idx+1 }}</span>
                <span class="flex-1 text-sm font-medium text-dark">{{ $item['name'] }}</span>
                <span class="text-sm text-gray-400 font-medium">{{ number_format($item['view_count']) }}</span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-400 text-sm">No tour data yet.</p>
        @endif
    </div>

    {{-- Recent Attractions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between mb-5">
            <h3 class="font-heading font-bold text-base text-dark">Recent Attractions</h3>
            <a href="{{ route('admin.attractions.index') }}" class="text-forest text-sm font-semibold hover:underline">View all</a>
        </div>
        @if($recentAttractions->count())
        <div class="space-y-3">
            @foreach($recentAttractions as $attraction)
            <div class="flex items-center gap-3">
                <img src="{{ $attraction->thumbnail_url }}" alt="{{ $attraction->name }}"
                     class="w-10 h-10 rounded-xl object-cover flex-shrink-0"
                     onerror="this.src='/images/placeholder.webp'">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-dark truncate">{{ $attraction->name }}</p>
                    <p class="text-xs text-gray-400">{{ $attraction->state->name }}</p>
                </div>
                <span class="badge {{ $attraction->status === 'published' ? 'badge-green' : 'badge bg-gray-100 text-gray-500' }}">
                    {{ ucfirst($attraction->status) }}
                </span>
                <a href="{{ route('admin.attractions.edit', $attraction) }}" class="text-gray-400 hover:text-forest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <p class="text-gray-400 text-sm mb-3">No attractions yet.</p>
            <a href="{{ route('admin.attractions.create') }}" class="btn-primary btn-sm">Create First Attraction</a>
        </div>
        @endif
    </div>
</div>
@endsection
