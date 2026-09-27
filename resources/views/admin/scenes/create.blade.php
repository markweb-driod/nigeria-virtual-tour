@extends('layouts.admin')
@section('title', 'Add Scene')
@section('page-title', 'Add New Scene')
@section('breadcrumb', $attraction->name . ' / Add Scene')

@section('content')
<form method="POST" action="{{ route('admin.scenes.store', $attraction) }}" enctype="multipart/form-data"
      class="max-w-3xl space-y-6">
    @csrf

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Scene Details</h3>
        <div class="space-y-5">
            <div>
                <label class="form-label">Scene Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input" required placeholder="e.g. Main Entrance">
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Description</label>
                <textarea name="description" rows="3" class="form-textarea" placeholder="Brief description of this scene...">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-1">Panorama Image *</h3>
        <p class="text-sm text-gray-400 mb-5">Upload a 2:1 equirectangular 360° image (e.g. 8192×4096px). JPG or PNG, max 50MB.</p>
        <input type="file" name="panorama" accept="image/*" class="form-input">
        @error('panorama')<p class="form-error">{{ $message }}</p>@enderror
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Initial View</h3>
        <p class="text-sm text-gray-400 mb-4">Set where the viewer looks when this scene loads.</p>
        <div class="grid grid-cols-3 gap-5">
            <div>
                <label class="form-label">Initial Yaw (-180 to 180)</label>
                <input type="number" step="0.1" name="initial_yaw" value="{{ old('initial_yaw', 0) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Initial Pitch (-90 to 90)</label>
                <input type="number" step="0.1" name="initial_pitch" value="{{ old('initial_pitch', 0) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Field of View (30–150)</label>
                <input type="number" step="1" name="initial_hfov" value="{{ old('initial_hfov', 100) }}" class="form-input">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Additional Files</h3>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="form-label">Scene Thumbnail</label>
                <input type="file" name="thumbnail" accept="image/*" class="form-input">
                <p class="text-xs text-gray-400 mt-1">Used in scene list sidebar</p>
            </div>
            <div>
                <label class="form-label">Audio Narration</label>
                <input type="file" name="audio_narration" accept=".mp3,.wav,.ogg" class="form-input">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Settings</h3>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
            </div>
            <div class="flex items-center gap-3 pt-6">
                <input type="checkbox" name="is_default" id="is_default" value="1"
                       class="w-4 h-4 rounded border-gray-300 text-forest">
                <label for="is_default" class="text-sm font-medium text-dark">Set as default start scene</label>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Upload Scene</button>
        <a href="{{ route('admin.scenes.index', $attraction) }}" class="btn-ghost">Cancel</a>
    </div>
</form>
@endsection
