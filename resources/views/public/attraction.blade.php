@extends('layouts.app')
@section('title', $attraction->name)
@section('meta_description', $attraction->short_description)

@section('content')
<div class="pt-16">
    {{-- Hero --}}
    <div class="relative h-[60vh] min-h-[400px] overflow-hidden">
        <img src="{{ $attraction->hero_image_url }}" alt="{{ $attraction->name }}"
             class="w-full h-full object-cover" onerror="this.src='/images/placeholder.webp'">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/10"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="badge-forest">{{ $attraction->category->name }}</span>
                    @if($attraction->is_featured)
                    <span class="badge bg-gold/90 text-dark">Featured</span>
                    @endif
                    @if($attraction->tour_available)
                    <span class="badge bg-emerald-500/90 text-white">360° Tour Available</span>
                    @endif
                </div>
                <h1 class="font-heading font-bold text-white text-4xl md:text-6xl text-shadow">{{ $attraction->name }}</h1>
                <p class="text-white/80 mt-2 text-lg">{{ $attraction->address ?? $attraction->state->name }}, Nigeria</p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 md:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- Main content --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Start Tour CTA --}}
                @if($attraction->tour_available)
                <a href="{{ route('tour.show', $attraction->slug) }}"
                   class="flex items-center gap-5 bg-gradient-to-r from-forest to-forest-600 rounded-2xl p-6 shadow-lg hover:shadow-xl transition-all group">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-heading font-bold text-white text-xl">Start 360° Virtual Tour</h3>
                        <p class="text-white/70 text-sm mt-0.5">
                            {{ $attraction->publishedScenes->count() }} panoramic scenes available
                        </p>
                    </div>
                    <svg class="w-6 h-6 text-white/60 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
                @endif

                {{-- Tabs --}}
                <div x-data="{ tab: 'about' }">
                    <div class="flex gap-1 border-b border-gray-200 mb-6">
                        @foreach(['about' => 'About', 'history' => 'History', 'facts' => 'Interesting Facts', 'gallery' => 'Gallery'] as $key => $label)
                        <button @click="tab = '{{ $key }}'"
                                class="px-5 py-3 text-sm font-semibold transition-all border-b-2 -mb-px"
                                :class="tab === '{{ $key }}' ? 'border-forest text-forest' : 'border-transparent text-gray-500 hover:text-dark'">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>

                    <div x-show="tab === 'about'" class="prose prose-sm max-w-none text-gray-600">
                        {!! nl2br(e($attraction->description)) !!}
                    </div>

                    <div x-show="tab === 'history'" class="prose prose-sm max-w-none text-gray-600">
                        @if($attraction->history)
                        {!! nl2br(e($attraction->history)) !!}
                        @else
                        <p class="text-gray-400 italic">Historical information coming soon.</p>
                        @endif
                    </div>

                    <div x-show="tab === 'facts'">
                        @if($attraction->interesting_facts)
                        <div class="prose prose-sm max-w-none text-gray-600">
                            {!! nl2br(e($attraction->interesting_facts)) !!}
                        </div>
                        @else
                        <p class="text-gray-400 italic">Facts coming soon.</p>
                        @endif
                    </div>

                    <div x-show="tab === 'gallery'">
                        @if($attraction->mediaItems->where('type','photo')->count())
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($attraction->mediaItems->where('type','photo') as $media)
                            <a href="{{ $media->url }}" target="_blank" class="group overflow-hidden rounded-xl aspect-square block">
                                <img src="{{ $media->url }}" alt="{{ $media->caption }}"
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                     loading="lazy">
                            </a>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-400 italic">No gallery images yet.</p>
                        @endif
                    </div>
                </div>

                {{-- Scene preview --}}
                @if($attraction->publishedScenes->count())
                <div>
                    <h2 class="font-heading font-bold text-xl text-dark mb-4">Tour Scenes</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach($attraction->publishedScenes as $scene)
                        <a href="{{ route('tour.show', $attraction->slug) }}#{{ $scene->slug }}"
                           class="relative group overflow-hidden rounded-xl aspect-video block bg-gray-100">
                            @if($scene->thumbnail)
                            <img src="{{ $scene->thumbnail_url }}" alt="{{ $scene->name }}"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                            @else
                            <div class="w-full h-full bg-gradient-to-br from-forest-100 to-forest-200 flex items-center justify-center">
                                <span class="text-forest-600 text-2xl">🌐</span>
                            </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                            <p class="absolute bottom-2 left-2 right-2 text-white text-xs font-semibold">{{ $scene->name }}</p>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Info card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <h3 class="font-heading font-bold text-base text-dark mb-4">Attraction Details</h3>
                    <dl class="space-y-3 text-sm">
                        <div class="flex gap-3">
                            <dt class="text-gray-400 w-24 flex-shrink-0">Category</dt>
                            <dd class="font-medium text-dark">{{ $attraction->category->name }}</dd>
                        </div>
                        <div class="flex gap-3">
                            <dt class="text-gray-400 w-24 flex-shrink-0">State</dt>
                            <dd class="font-medium text-dark">{{ $attraction->state->name }}</dd>
                        </div>
                        @if($attraction->lga)
                        <div class="flex gap-3">
                            <dt class="text-gray-400 w-24 flex-shrink-0">LGA</dt>
                            <dd class="font-medium text-dark">{{ $attraction->lga }}</dd>
                        </div>
                        @endif
                        @if($attraction->visiting_hours)
                        <div class="flex gap-3">
                            <dt class="text-gray-400 w-24 flex-shrink-0">Hours</dt>
                            <dd class="font-medium text-dark">{{ $attraction->visiting_hours }}</dd>
                        </div>
                        @endif
                        @if($attraction->entry_fee)
                        <div class="flex gap-3">
                            <dt class="text-gray-400 w-24 flex-shrink-0">Entry Fee</dt>
                            <dd class="font-medium text-dark">{{ $attraction->entry_fee }}</dd>
                        </div>
                        @endif
                        @if($attraction->latitude && $attraction->longitude)
                        <div class="flex gap-3">
                            <dt class="text-gray-400 w-24 flex-shrink-0">GPS</dt>
                            <dd class="font-medium text-dark text-xs font-mono">{{ $attraction->latitude }}, {{ $attraction->longitude }}</dd>
                        </div>
                        @endif
                    </dl>
                </div>

                {{-- Audio intro --}}
                @if($attraction->audio)
                <div class="bg-forest-50 border border-forest-100 rounded-2xl p-5">
                    <h3 class="font-heading font-semibold text-sm text-forest mb-3">🎧 Audio Introduction</h3>
                    <audio controls class="w-full" preload="none">
                        <source src="{{ asset('storage/' . $attraction->audio) }}">
                    </audio>
                </div>
                @endif

                {{-- Map --}}
                @if($attraction->latitude && $attraction->longitude)
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100">
                    <div id="attraction-map" class="h-52"
                         data-lat="{{ $attraction->latitude }}"
                         data-lng="{{ $attraction->longitude }}"
                         data-name="{{ $attraction->name }}"></div>
                </div>
                @endif

                {{-- Related --}}
                @if($relatedAttractions->count())
                <div>
                    <h3 class="font-heading font-bold text-base text-dark mb-4">Related Attractions</h3>
                    <div class="space-y-3">
                        @foreach($relatedAttractions as $related)
                        <a href="{{ route('attractions.show', $related->slug) }}"
                           class="flex gap-3 items-center group hover:bg-gray-50 p-2 rounded-xl transition-colors">
                            <img src="{{ $related->thumbnail_url }}" alt="{{ $related->name }}"
                                 class="w-14 h-14 object-cover rounded-xl flex-shrink-0"
                                 onerror="this.src='/images/placeholder.webp'">
                            <div>
                                <p class="font-semibold text-sm text-dark group-hover:text-forest transition-colors">{{ $related->name }}</p>
                                <p class="text-xs text-gray-400">{{ $related->state->name }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($attraction->latitude && $attraction->longitude)
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    const mapEl = document.getElementById('attraction-map');
    if (mapEl) {
        const lat = parseFloat(mapEl.dataset.lat);
        const lng = parseFloat(mapEl.dataset.lng);
        const name = mapEl.dataset.name;
        const map = L.map('attraction-map').setView([lat, lng], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© <a href="https://www.openstreetmap.org/">OpenStreetMap</a>'
        }).addTo(map);
        L.marker([lat, lng]).addTo(map).bindPopup(name).openPopup();
    }
</script>
@endif
@endpush
