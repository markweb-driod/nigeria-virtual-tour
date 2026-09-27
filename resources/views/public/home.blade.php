@extends('layouts.app')
@section('title', 'Explore Nigeria in 360°')

@section('content')

{{-- ===== HERO SECTION ===== --}}
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    {{-- Background gradient (replaces panorama background for initial load) --}}
    <div class="absolute inset-0 bg-gradient-to-br from-forest-900 via-forest to-dark"></div>
    {{-- Decorative overlay --}}
    <div class="absolute inset-0 bg-[url('/images/nigeria-pattern.svg')] opacity-5"></div>
    {{-- Bottom fade --}}
    <div class="absolute bottom-0 left-0 right-0 h-40 bg-gradient-to-t from-bglight to-transparent"></div>

    <div class="relative z-10 text-center px-4 max-w-5xl mx-auto">
        <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/20 text-gold text-xs font-semibold tracking-widest uppercase px-4 py-2 rounded-full mb-8">
            <span class="w-2 h-2 bg-gold rounded-full animate-pulse"></span>
            360° Immersive Virtual Tours
        </div>

        <h1 class="font-heading font-bold text-white text-5xl md:text-7xl leading-[1.05] mb-6 text-shadow">
            Explore Nigeria<br>
            <span class="text-gold">Like Never Before</span>
        </h1>

        <p class="text-white/80 text-lg md:text-xl max-w-2xl mx-auto mb-10 leading-relaxed">
            Experience Nigeria's breathtaking destinations in immersive 360°.
            From Olumo Rock to Zuma Rock — discover the beauty of Nigeria from anywhere.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('explore') }}" class="btn-primary text-base px-8 py-4 rounded-2xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"/>
                </svg>
                Start Exploring
            </a>
            <a href="{{ route('explore') }}?tour=1" class="btn-ghost border-white text-white hover:bg-white hover:text-forest text-base px-8 py-4 rounded-2xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.069A1 1 0 0121 8.882v6.236a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                </svg>
                View 360° Tours
            </a>
        </div>

        {{-- Stats --}}
        <div class="mt-16 flex flex-col sm:flex-row items-center justify-center gap-8 md:gap-16">
            <div class="text-center">
                <p class="text-white font-heading font-bold text-4xl">{{ $totalAttractions }}+</p>
                <p class="text-white/60 text-sm font-medium mt-1">Tourist Attractions</p>
            </div>
            <div class="hidden sm:block w-px h-12 bg-white/20"></div>
            <div class="text-center">
                <p class="text-white font-heading font-bold text-4xl">{{ $totalTours }}+</p>
                <p class="text-white/60 text-sm font-medium mt-1">360° Virtual Tours</p>
            </div>
            <div class="hidden sm:block w-px h-12 bg-white/20"></div>
            <div class="text-center">
                <p class="text-white font-heading font-bold text-4xl">36</p>
                <p class="text-white/60 text-sm font-medium mt-1">States Covered</p>
            </div>
        </div>
    </div>

    {{-- Scroll indicator --}}
    <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-white/40 animate-bounce">
        <span class="text-xs font-medium tracking-wider uppercase">Scroll</span>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
        </svg>
    </div>
</section>

{{-- ===== FEATURED DESTINATIONS ===== --}}
@if($featuredAttractions->count())
<section class="max-w-7xl mx-auto px-4 md:px-8 py-20">
    <div class="flex items-end justify-between mb-12">
        <div>
            <p class="section-label mb-2">Handpicked for you</p>
            <h2 class="section-title">Featured Destinations</h2>
        </div>
        <a href="{{ route('explore') }}?featured=1" class="hidden md:block text-forest font-semibold text-sm hover:text-forest-600 transition-colors">
            View all →
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($featuredAttractions as $attraction)
        <a href="{{ route('attractions.show', $attraction->slug) }}" class="attraction-card">
            <div class="relative overflow-hidden h-64">
                <img src="{{ $attraction->hero_image_url }}" alt="{{ $attraction->name }}"
                     class="attraction-card-img h-64"
                     loading="lazy" onerror="this.src='/images/placeholder.webp'">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>
                {{-- Badges --}}
                <div class="absolute top-3 left-3 flex gap-2">
                    <span class="badge bg-gold/90 text-dark text-xs">Featured</span>
                    @if($attraction->tour_available)
                    <span class="badge bg-forest/90 text-white text-xs">360° Tour</span>
                    @endif
                </div>
                {{-- State label --}}
                <div class="absolute bottom-3 left-3 right-3">
                    <h3 class="font-heading font-bold text-white text-xl leading-tight">{{ $attraction->name }}</h3>
                    <p class="text-white/80 text-sm mt-0.5">{{ $attraction->state->name }}</p>
                </div>
            </div>
            <div class="attraction-card-body">
                <p class="text-gray-500 text-sm leading-relaxed line-clamp-2">{{ $attraction->short_description }}</p>
                <div class="flex items-center justify-between mt-4">
                    <span class="badge-forest">{{ $attraction->category->name }}</span>
                    @if($attraction->tour_available)
                    <span class="text-forest font-semibold text-xs flex items-center gap-1">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        Virtual Tour
                    </span>
                    @endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ===== CATEGORIES ===== --}}
@if($categories->count())
<section class="bg-white py-20">
    <div class="max-w-7xl mx-auto px-4 md:px-8">
        <div class="text-center mb-12">
            <p class="section-label mb-2">Browse by type</p>
            <h2 class="section-title">Explore by Category</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @foreach($categories as $category)
            <a href="{{ route('attractions.category', $category->slug) }}"
               class="group flex flex-col items-center p-6 rounded-2xl border border-gray-100 hover:border-forest/30 hover:bg-forest-50/50 transition-all duration-200 text-center">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3 text-2xl transition-transform group-hover:scale-110"
                     style="background-color: {{ $category->color }}20">
                    <span>{{ $category->icon ?? '🏛' }}</span>
                </div>
                <h3 class="font-semibold text-sm text-dark group-hover:text-forest transition-colors">{{ $category->name }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $category->attractions_count }} sites</p>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===== POPULAR ATTRACTIONS ===== --}}
@if($popularAttractions->count())
<section class="max-w-7xl mx-auto px-4 md:px-8 py-20">
    <div class="flex items-end justify-between mb-12">
        <div>
            <p class="section-label mb-2">Most visited</p>
            <h2 class="section-title">Popular Virtual Tours</h2>
        </div>
        <a href="{{ route('explore') }}" class="hidden md:block text-forest font-semibold text-sm hover:text-forest-600 transition-colors">View all →</a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach($popularAttractions as $attraction)
        <a href="{{ $attraction->tour_available ? route('tour.show', $attraction->slug) : route('attractions.show', $attraction->slug) }}"
           class="attraction-card">
            <div class="relative overflow-hidden h-48">
                <img src="{{ $attraction->thumbnail_url }}" alt="{{ $attraction->name }}"
                     class="w-full h-48 object-cover transition-transform duration-500 group-hover:scale-105"
                     loading="lazy" onerror="this.src='/images/placeholder.webp'">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                @if($attraction->tour_available)
                <div class="absolute top-3 right-3">
                    <span class="badge bg-forest/90 text-white text-xs">360°</span>
                </div>
                @endif
            </div>
            <div class="p-4">
                <h3 class="font-heading font-bold text-dark text-sm leading-tight">{{ $attraction->name }}</h3>
                <p class="text-gray-400 text-xs mt-1">{{ $attraction->state->name }}</p>
            </div>
        </a>
        @endforeach
    </div>
</section>
@endif

{{-- ===== CTA BAND ===== --}}
<section class="bg-gradient-to-r from-forest-700 via-forest to-forest-600 py-20">
    <div class="max-w-4xl mx-auto text-center px-4">
        <h2 class="font-heading font-bold text-white text-3xl md:text-4xl mb-4">
            Ready to Explore Nigeria Virtually?
        </h2>
        <p class="text-white/70 text-lg mb-8">
            Start your immersive 360° journey through Nigeria's most iconic destinations.
        </p>
        <a href="{{ route('explore') }}?tour=1" class="inline-flex items-center gap-2 bg-gold text-dark font-bold px-8 py-4 rounded-2xl hover:bg-gold-300 transition-all duration-200 shadow-xl text-base">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Launch Virtual Tour
        </a>
    </div>
</section>

@endsection
