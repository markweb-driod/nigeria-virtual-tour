@extends('layouts.admin')
@section('title', 'Edit Scene: ' . $scene->name)
@section('page-title', 'Scene Editor: ' . $scene->name)
@section('breadcrumb', $attraction->name . ' / Scenes / ' . $scene->name)

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.css">
<script src="https://cdn.jsdelivr.net/npm/pannellum@2.5.6/build/pannellum.js"></script>
@endpush

@section('header-actions')
<a href="{{ route('admin.scenes.index', $attraction) }}" class="btn-ghost btn-sm">All Scenes</a>
@endsection

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6" x-data="sceneEditor()">

    {{-- Panorama editor (2/3 width) --}}
    <div class="xl:col-span-2">
        @if($scene->panorama_path)
        <div class="bg-dark rounded-2xl overflow-hidden mb-4" style="height:450px">
            <div id="editor-panorama" style="width:100%;height:100%;"></div>
        </div>
        <p class="text-xs text-gray-400 text-center mb-4">
            <span class="font-semibold text-forest">Click anywhere</span> on the panorama to place a hotspot at that position.
        </p>
        @else
        <div class="bg-gray-100 rounded-2xl flex items-center justify-center h-64 mb-4">
            <div class="text-center">
                <p class="text-gray-400 mb-3">No panorama uploaded yet.</p>
                <p class="text-sm text-gray-500">Upload a panorama image below to use the visual hotspot editor.</p>
            </div>
        </div>
        @endif

        {{-- Hotspot creation form (appears on click) --}}
        <div x-show="newHotspot.visible" x-transition class="bg-white rounded-2xl shadow-sm border border-forest-200 p-5 mb-4">
            <h4 class="font-heading font-bold text-sm text-forest mb-4">Create Hotspot at Yaw: <span x-text="newHotspot.yaw"></span>°, Pitch: <span x-text="newHotspot.pitch"></span>°</h4>
            <form @submit.prevent="createHotspot">
                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-2">
                        <label class="form-label">Label *</label>
                        <input type="text" x-model="newHotspot.label" class="form-input" required placeholder="e.g. Continue to Cave Entrance">
                    </div>
                    <div>
                        <label class="form-label">Type *</label>
                        <select x-model="newHotspot.type" class="form-select">
                            <option value="scene">Scene Navigation</option>
                            <option value="info">Information</option>
                            <option value="media">Media</option>
                        </select>
                    </div>
                    <div x-show="newHotspot.type === 'scene'">
                        <label class="form-label">Target Scene *</label>
                        <select x-model="newHotspot.target_scene_id" class="form-select">
                            <option value="">Select scene...</option>
                            @foreach($scenes as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2" x-show="newHotspot.type === 'info' || newHotspot.type === 'media'">
                        <label class="form-label">Description</label>
                        <textarea x-model="newHotspot.description" rows="2" class="form-textarea"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-4">
                    <button type="submit" class="btn-primary btn-sm">Create Hotspot</button>
                    <button type="button" @click="newHotspot.visible = false" class="btn-ghost btn-sm">Cancel</button>
                </div>
            </form>
        </div>

        {{-- Existing hotspots --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <h4 class="font-heading font-bold text-sm text-dark mb-4">Hotspots ({{ $hotspots->count() }})</h4>
            @if($hotspots->count())
            <div class="space-y-2">
                @foreach($hotspots as $hs)
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl">
                    <span class="badge {{ $hs->type === 'scene' ? 'bg-forest-50 text-forest-600' : ($hs->type === 'info' ? 'bg-blue-50 text-blue-600' : 'bg-gold-50 text-gold-600') }} text-xs">
                        {{ ucfirst($hs->type) }}
                    </span>
                    <span class="flex-1 text-sm font-medium text-dark">{{ $hs->label }}</span>
                    <span class="text-xs text-gray-400 font-mono">{{ $hs->yaw }}° / {{ $hs->pitch }}°</span>
                    <form method="POST" action="{{ route('admin.hotspots.destroy', $hs) }}"
                          onsubmit="return confirm('Delete this hotspot?')">
                        @csrf @method('DELETE')
                        <button class="text-red-400 hover:text-red-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-gray-400 text-sm">
                @if($scene->panorama_path)
                Click the panorama above to place your first hotspot.
                @else
                Upload a panorama first, then add hotspots.
                @endif
            </p>
            @endif
        </div>
    </div>

    {{-- Right panel: Scene settings --}}
    <div class="space-y-5">
        <form method="POST" action="{{ route('admin.scenes.update', $scene) }}" enctype="multipart/form-data"
              class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-4">
            @csrf @method('PUT')

            <h4 class="font-heading font-bold text-sm text-dark">Scene Settings</h4>

            <div>
                <label class="form-label">Scene Name</label>
                <input type="text" name="name" value="{{ old('name', $scene->name) }}" class="form-input" required>
            </div>

            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ $scene->status === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ $scene->status === 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>

            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="form-label text-xs">Yaw</label>
                    <input type="number" step="0.1" name="initial_yaw" value="{{ old('initial_yaw', $scene->initial_yaw) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label text-xs">Pitch</label>
                    <input type="number" step="0.1" name="initial_pitch" value="{{ old('initial_pitch', $scene->initial_pitch) }}" class="form-input">
                </div>
                <div>
                    <label class="form-label text-xs">FOV</label>
                    <input type="number" step="1" name="initial_hfov" value="{{ old('initial_hfov', $scene->initial_hfov) }}" class="form-input">
                </div>
            </div>

            <div>
                <label class="form-label">Replace Panorama</label>
                <input type="file" name="panorama" accept="image/*" class="form-input">
            </div>

            <div>
                <label class="form-label">Scene Thumbnail</label>
                <input type="file" name="thumbnail" accept="image/*" class="form-input">
            </div>

            <div class="flex items-center gap-3">
                <input type="checkbox" name="is_default" id="is_default_edit" value="1"
                       {{ $scene->is_default ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-forest">
                <label for="is_default_edit" class="text-sm font-medium text-dark">Default start scene</label>
            </div>

            <button type="submit" class="btn-primary w-full justify-center">Save Scene</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function sceneEditor() {
    return {
        viewer: null,
        newHotspot: { visible: false, yaw: 0, pitch: 0, label: '', type: 'scene', description: '', target_scene_id: '' },

        init() {
            @if($scene->panorama_path)
            this.viewer = pannellum.viewer('editor-panorama', {
                type: 'equirectangular',
                panorama: '{{ asset("storage/" . $scene->panorama_path) }}',
                autoLoad: true,
                showControls: true,
                yaw: {{ $scene->initial_yaw }},
                pitch: {{ $scene->initial_pitch }},
                hfov: {{ $scene->initial_hfov }},
                hotSpots: [
                    @foreach($hotspots as $hs)
                    {
                        pitch: {{ $hs->pitch }},
                        yaw: {{ $hs->yaw }},
                        type: 'info',
                        text: '{{ addslashes($hs->label) }}',
                        cssClass: 'pnlm-hotspot-base hs-{{ $hs->type }}',
                    },
                    @endforeach
                ]
            });

            // Capture click position for hotspot creation
            document.getElementById('editor-panorama').addEventListener('click', (e) => {
                if (!this.viewer) return;
                const coords = this.viewer.mouseEventToCoords(e);
                if (coords) {
                    this.newHotspot.pitch = Math.round(coords[0] * 10) / 10;
                    this.newHotspot.yaw = Math.round(coords[1] * 10) / 10;
                    this.newHotspot.visible = true;
                    this.newHotspot.label = '';
                    this.newHotspot.type = 'scene';
                }
            });
            @endif
        },

        async createHotspot() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
            const res = await fetch('/admin/scenes/{{ $scene->id }}/hotspots', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify(this.newHotspot),
            });
            const data = await res.json();
            if (data.success) {
                window.location.reload();
            } else {
                alert('Failed to create hotspot.');
            }
        },
    };
}
</script>
@endpush
