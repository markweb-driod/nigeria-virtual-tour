@extends('layouts.app')
@section('title', 'Explore Nigeria')

@section('content')
<div class="pt-28 pb-20">

    {{-- Header --}}
    <div class="bg-gradient-to-br from-forest-900 to-forest-700 py-16">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <h1 class="font-heading font-bold text-white text-4xl md:text-5xl mb-3">Explore Nigeria</h1>
            <p class="text-white/70">Discover {{ $attractions->total() }} tourist attractions across Nigeria</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 md:px-8 mt-8">
        {{-- Filters --}}
        <form method="GET" action="{{ route('explore') }}" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-8"
              x-data="{ expanded: false }">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search destinations..."
                           class="form-input">
                </div>
                <div class="flex gap-3 flex-wrap">
                    <select name="category" class="form-select w-auto min-w-[140px]">
                        <option value="">All Categories</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') === $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <select name="state" class="form-select w-auto min-w-[140px]">
                        <option value="">All States</option>
                        @foreach($states as $state)
                        <option value="{{ $state->slug }}" {{ request('state') === $state->slug ? 'selected' : '' }}>{{ $state->name }}</option>
                        @endforeach
                    </select>
                    <select name="sort" class="form-select w-auto">
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>A–Z</option>
                    </select>
                    <button type="submit" class="btn-primary btn-sm">Search</button>
                    @if(request()->hasAny(['search','category','state','sort','tour','featured']))
                    <a href="{{ route('explore') }}" class="btn-ghost btn-sm">Clear</a>
                    @endif
                </div>
            </div>

            {{-- Quick filters --}}
            <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100">
                <a href="{{ route('explore') }}" class="badge {{ !request()->hasAny(['tour','featured']) ? 'bg-forest text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} cursor-pointer px-4 py-1.5">All</a>
                <a href="{{ route('explore') }}?tour=1" class="badge {{ request('tour') ? 'bg-forest text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} cursor-pointer px-4 py-1.5">360° Tours Only</a>
                <a href="{{ route('explore') }}?featured=1" class="badge {{ request('featured') ? 'bg-gold text-dark' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} cursor-pointer px-4 py-1.5">Featured</a>
                @foreach($categories->take(5) as $cat)
                <a href="{{ route('attractions.category', $cat->slug) }}" class="badge bg-gray-100 text-gray-600 hover:bg-forest-50 hover:text-forest cursor-pointer px-4 py-1.5">{{ $cat->name }}</a>
                @endforeach
            </div>
        </form>

        {{-- Results grid --}}
        @if($attractions->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
            @foreach($attractions as $attraction)
            <div class="attraction-card">
                <a href="{{ route('attractions.show', $attraction->slug) }}">
                    <div class="relative overflow-hidden h-52">
                        <img src="{{ $attraction->thumbnail_url }}" alt="{{ $attraction->name }}"
                             class="w-full h-52 object-cover transition-transform duration-500 group-hover:scale-105"
                             loading="lazy" onerror="this.src='/images/placeholder.webp'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute top-3 left-3 flex gap-1.5">
                            @if($attraction->is_featured)
                            <span class="badge bg-gold/90 text-dark text-xs">Featured</span>
                            @endif
                            @if($attraction->tour_available)
                            <span class="badge bg-forest/90 text-white text-xs">360°</span>
                            @endif
                        </div>
                        <div class="absolute bottom-3 left-3">
                            <p class="text-white font-heading font-bold text-base leading-tight">{{ $attraction->name }}</p>
                            <p class="text-white/70 text-xs">{{ $attraction->state->name }}</p>
                        </div>
                    </div>
                </a>
                <div class="p-4">
                    <p class="text-gray-500 text-xs leading-relaxed line-clamp-2">{{ $attraction->short_description }}</p>
                    <div class="flex items-center justify-between mt-3">
                        <span class="badge-forest text-xs">{{ $attraction->category->name }}</span>
                        @if($attraction->tour_available)
                        <a href="{{ route('tour.show', $attraction->slug) }}"
                           class="text-xs font-semibold text-forest hover:text-gold flex items-center gap-1 transition-colors">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                            View Tour
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-10">
            {{ $attractions->links() }}
        </div>

        @else
        <div class="text-center py-24">
            <div class="text-6xl mb-4">🗺</div>
            <h3 class="font-heading font-bold text-xl text-dark mb-2">No Attractions Found</h3>
            <p class="text-gray-500 mb-6">Try adjusting your filters or search terms.</p>
            <a href="{{ route('explore') }}" class="btn-primary">Clear Filters</a>
        </div>
        @endif
    </div>
</div>
@endsection
