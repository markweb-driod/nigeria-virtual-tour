@extends('layouts.admin')
@section('title', 'Analytics')
@section('page-title', 'Analytics & Performance')

@section('content')
{{-- Performance KPIs --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="stat-card">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-2">Avg Load Time</p>
        <p class="text-3xl font-heading font-bold text-dark">{{ $stats['avg_load_time'] ? number_format($stats['avg_load_time']).'ms' : '—' }}</p>
        <div class="mt-2 flex items-center gap-1.5">
            @php $good = ($stats['avg_load_time'] ?? 9999) < 3000; @endphp
            <span class="w-2 h-2 rounded-full {{ $good ? 'bg-emerald-500' : 'bg-orange-500' }}"></span>
            <span class="text-xs font-medium {{ $good ? 'text-emerald-600' : 'text-orange-600' }}">{{ $good ? 'Under 3s target' : 'Above 3s target' }}</span>
        </div>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-2">Avg Viewer FPS</p>
        <p class="text-3xl font-heading font-bold text-dark">{{ $stats['avg_fps'] ? $stats['avg_fps'] : '—' }}</p>
        <div class="mt-2 flex items-center gap-1.5">
            @php $goodFps = ($stats['avg_fps'] ?? 0) >= 30; @endphp
            <span class="w-2 h-2 rounded-full {{ $goodFps ? 'bg-emerald-500' : 'bg-orange-500' }}"></span>
            <span class="text-xs font-medium {{ $goodFps ? 'text-emerald-600' : 'text-orange-600' }}">{{ $goodFps ? 'Above 30 FPS' : 'Below 30 FPS' }}</span>
        </div>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-2">Avg Transition</p>
        <p class="text-3xl font-heading font-bold text-dark">{{ $stats['avg_transition'] ? number_format($stats['avg_transition']).'ms' : '—' }}</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-2">Total Sessions</p>
        <p class="text-3xl font-heading font-bold text-dark">{{ number_format($totalSessions) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Device breakdown --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Device Breakdown</h3>
        @if($totalSessions > 0)
        <div class="space-y-3">
            @foreach(['mobile' => ['label' => 'Mobile', 'color' => 'bg-forest'], 'desktop' => ['label' => 'Desktop', 'color' => 'bg-blue-500'], 'tablet' => ['label' => 'Tablet', 'color' => 'bg-gold']] as $type => $info)
            @php $count = $deviceBreakdown[$type]; $pct = $totalSessions > 0 ? round(($count / $totalSessions) * 100) : 0; @endphp
            <div>
                <div class="flex items-center justify-between text-sm mb-1.5">
                    <span class="font-medium text-dark">{{ $info['label'] }}</span>
                    <span class="text-gray-500">{{ number_format($count) }} ({{ $pct }}%)</span>
                </div>
                <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full {{ $info['color'] }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-400 text-sm">No session data recorded yet.</p>
        @endif
    </div>

    {{-- Top Attractions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Most Visited Attractions</h3>
        @if(count($topAttractions))
        <div class="space-y-3">
            @foreach($topAttractions as $idx => $item)
            <div class="flex items-center gap-3">
                <span class="w-7 h-7 rounded-lg bg-forest-50 text-forest-600 text-xs font-bold flex items-center justify-center">{{ $idx + 1 }}</span>
                <span class="flex-1 text-sm font-medium text-dark">{{ $item['name'] }}</span>
                <span class="text-sm text-gray-400 font-mono">{{ number_format($item['view_count']) }}</span>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-400 text-sm">No attraction data yet.</p>
        @endif
    </div>
</div>
@endsection
