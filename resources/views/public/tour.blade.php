@extends('layouts.tour')
@section('title', $attraction->name)

@section('content')
<div id="panorama-container" class="absolute inset-0"></div>

{{-- ===== TOUR UI OVERLAY ===== --}}
<div class="absolute inset-0 pointer-events-none z-10"
     x-data="tourUI()"
     @scene:changed.window="onSceneChanged($event.detail)"
     @hotspot:open.window="openHotspot($event.detail)"
     @audio:available.window="audioUrl = $event.detail.url; hasAudio = true"
     @audio:unavailable.window="hasAudio = false; audioPlaying = false"
     @audio:stopped.window="audioPlaying = false"
     @audio:ended.window="audioPlaying = false"
     @panels:close-all.window="closePanels()">

    {{-- ===== TOP BAR ===== --}}
    <div class="absolute top-0 left-0 right-0 pointer-events-auto">
        <div class="flex items-center justify-between px-4 py-3 bg-gradient-to-b from-black/70 to-transparent">
            {{-- Back --}}
            <a href="{{ route('attractions.show', $attraction->slug) }}"
               class="flex items-center gap-2 text-white/90 hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <span class="text-sm font-semibold hidden sm:block">{{ $attraction->name }}</span>
            </a>

            {{-- Controls --}}
            <div class="flex items-center gap-2">
                {{-- FPS counter (debug) --}}
                <span id="fps-counter" class="text-white/30 text-xs font-mono hidden lg:block"></span>

                {{-- Zoom --}}
                <button @click="window.tourEngine?.zoom(10)" class="tour-control-btn" title="Zoom In">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                    </svg>
                </button>
                <button @click="window.tourEngine?.zoom(-10)" class="tour-control-btn" title="Zoom Out">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM13 10H7"/>
                    </svg>
                </button>

                {{-- Auto rotate --}}
                <button @click="autoRotate = window.tourEngine?.toggleAutoRotate()" class="tour-control-btn" :class="autoRotate && 'bg-forest/80'"
                        title="Auto Rotate">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>

                {{-- Audio --}}
                <button x-show="hasAudio"
                        @click="audioPlaying = window.tourEngine?.toggleAudio(audioUrl)"
                        class="tour-control-btn"
                        :class="audioPlaying && 'bg-gold/80 text-dark'"
                        title="Audio Narration">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM14.657 2.929a1 1 0 011.414 0A9.972 9.972 0 0119 10a9.972 9.972 0 01-2.929 7.071 1 1 0 01-1.414-1.414A7.971 7.971 0 0017 10c0-2.21-.894-4.208-2.343-5.657a1 1 0 010-1.414zm-2.829 2.828a1 1 0 011.415 0A5.983 5.983 0 0115 10a5.984 5.984 0 01-1.757 4.243 1 1 0 01-1.415-1.415A3.984 3.984 0 0013 10a3.983 3.983 0 00-1.172-2.828 1 1 0 010-1.415z" clip-rule="evenodd"/>
                    </svg>
                </button>

                {{-- Scene list toggle --}}
                <button @click="sceneListOpen = !sceneListOpen" class="tour-control-btn" :class="sceneListOpen && 'bg-forest/70'"
                        title="Scene List">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Fullscreen --}}
                <button @click="window.tourEngine?.toggleFullscreen()" class="tour-control-btn" title="Fullscreen (F)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== BOTTOM SCENE BAR ===== --}}
    <div class="absolute bottom-0 left-0 right-0 pointer-events-auto">
        <div class="px-4 pb-safe">
            {{-- Current scene name --}}
            <div class="glass-dark rounded-2xl px-5 py-3 mb-3 mx-auto max-w-lg text-center">
                <p class="text-white/50 text-xs uppercase tracking-widest font-medium mb-0.5">Current Location</p>
                <p id="current-scene-name" class="text-white font-heading font-bold text-base">Loading...</p>
            </div>
        </div>
    </div>

    {{-- ===== SCENE LIST PANEL ===== --}}
    <div x-show="sceneListOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-x-4"
         x-transition:enter-end="opacity-100 translate-x-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-end="opacity-0 translate-x-4"
         class="absolute right-0 top-0 bottom-0 w-72 sm:w-80 glass-dark overflow-y-auto pointer-events-auto">

        <div class="p-4 border-b border-white/10">
            <div class="flex items-center justify-between">
                <h3 class="font-heading font-bold text-white text-base">Tour Scenes</h3>
                <button @click="sceneListOpen = false" class="text-white/60 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-white/50 text-xs mt-1">{{ count($sceneList) }} scenes</p>
        </div>

        <div class="p-3 space-y-2">
            @foreach($sceneList as $scene)
            <button @click="loadScene('{{ $scene['slug'] }}')"
                    data-scene-id="{{ $scene['slug'] }}"
                    class="w-full flex items-center gap-3 p-3 rounded-xl hover:bg-white/10 transition-all text-left group">
                <div class="w-16 h-12 flex-shrink-0 rounded-lg overflow-hidden bg-white/10">
                    <img src="{{ $scene['thumbnail'] }}" alt="{{ $scene['name'] }}"
                         class="w-full h-full object-cover" onerror="this.src='/images/scene-placeholder.webp'">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white text-sm font-semibold truncate">{{ $scene['name'] }}</p>
                    @if($scene['is_default'])
                    <p class="text-gold text-xs">Start scene</p>
                    @endif
                </div>
                <svg class="w-4 h-4 text-white/40 group-hover:text-white flex-shrink-0 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            @endforeach
        </div>
    </div>

    {{-- ===== INFO HOTSPOT PANEL ===== --}}
    <div x-show="hotspotOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute left-4 bottom-24 w-80 glass-dark rounded-2xl overflow-hidden pointer-events-auto">
        <div class="p-5">
            <div class="flex items-start justify-between mb-3">
                <h3 class="font-heading font-bold text-white text-base" x-text="hotspot.text ?? 'Information'"></h3>
                <button @click="hotspotOpen = false" class="text-white/60 hover:text-white ml-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <p class="text-white/70 text-sm leading-relaxed" x-text="hotspot.description ?? ''"></p>
        </div>
    </div>

    {{-- ===== HELP overlay ===== --}}
    <div x-show="helpOpen"
         x-transition
         class="absolute inset-0 bg-black/60 flex items-center justify-center pointer-events-auto">
        <div class="glass rounded-2xl p-8 max-w-sm w-full mx-4 text-center">
            <h3 class="font-heading font-bold text-white text-xl mb-6">Tour Controls</h3>
            <div class="space-y-3 text-left">
                @foreach([
                    ['Drag / Swipe', 'Rotate the view'],
                    ['Scroll / Pinch', 'Zoom in or out'],
                    ['Click hotspot ◉', 'Navigate or get info'],
                    ['F key', 'Toggle fullscreen'],
                    ['R key', 'Reset view'],
                    ['+ / - keys', 'Zoom in / out'],
                ] as [$key, $desc])
                <div class="flex items-center justify-between">
                    <span class="bg-white/10 text-white text-xs font-mono px-2.5 py-1 rounded-lg">{{ $key }}</span>
                    <span class="text-white/70 text-sm">{{ $desc }}</span>
                </div>
                @endforeach
            </div>
            <button @click="helpOpen = false" class="btn-primary w-full justify-center mt-6">Got it</button>
        </div>
    </div>

    {{-- Help button --}}
    <button @click="helpOpen = true"
            class="absolute left-4 top-16 tour-control-btn pointer-events-auto">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </button>

</div>

@push('scripts')
@vite(['resources/js/tour.js'])
<script>
const TOUR_CONFIG = @json($tourConfig);
TOUR_CONFIG.attractionId = {{ $attraction->id }};

function tourUI() {
    return {
        sceneListOpen: false,
        hotspotOpen: false,
        hotspot: {},
        autoRotate: false,
        audioPlaying: false,
        hasAudio: false,
        audioUrl: null,
        helpOpen: {{ count($sceneList) === 1 ? 'false' : 'false' }},
        currentSceneId: TOUR_CONFIG.tour.defaultScene,

        loadScene(sceneId) {
            window.tourEngine?.goToScene(sceneId);
        },
        onSceneChanged(detail) {
            this.currentSceneId = detail.sceneId;
        },
        openHotspot(hs) {
            this.hotspot = hs;
            this.hotspotOpen = true;
        },
        closePanels() {
            this.hotspotOpen = false;
            this.sceneListOpen = false;
            this.helpOpen = false;
        }
    };
}

document.addEventListener('DOMContentLoaded', () => {
    window.tourEngine = new VirtualTourEngine(TOUR_CONFIG);
    window.tourEngine.init();
});
</script>
@endpush
@endsection
