@extends('layouts.admin')
@section('title', 'Edit ' . $attraction->name)
@section('page-title', 'Edit: ' . $attraction->name)
@section('breadcrumb', 'Admin / Attractions / Edit')

@section('header-actions')
<a href="{{ route('admin.scenes.index', $attraction) }}" class="btn-ghost btn-sm">
    Manage Scenes ({{ $attraction->scenes->count() }})
</a>
@if($attraction->tour_available)
<a href="{{ route('tour.show', $attraction->slug) }}" target="_blank" class="btn-secondary btn-sm">View Tour</a>
@endif
@endsection

@section('content')
<form method="POST" action="{{ route('admin.attractions.update', $attraction) }}" enctype="multipart/form-data"
      class="max-w-4xl space-y-6">
    @csrf @method('PUT')

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="form-label">Attraction Name *</label>
                <input type="text" name="name" value="{{ old('name', $attraction->name) }}" class="form-input" required>
            </div>
            <div>
                <label class="form-label">Category *</label>
                <select name="category_id" class="form-select" required>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id', $attraction->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">State *</label>
                <select name="state_id" class="form-select" required>
                    @foreach($states as $state)
                    <option value="{{ $state->id }}" {{ old('state_id', $attraction->state_id) == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">LGA</label>
                <input type="text" name="lga" value="{{ old('lga', $attraction->lga) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Address</label>
                <input type="text" name="address" value="{{ old('address', $attraction->address) }}" class="form-input">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Descriptions</h3>
        <div class="space-y-5">
            <div>
                <label class="form-label">Short Description *</label>
                <textarea name="short_description" rows="2" class="form-textarea" required>{{ old('short_description', $attraction->short_description) }}</textarea>
            </div>
            <div>
                <label class="form-label">Full Description *</label>
                <textarea name="description" rows="6" class="form-textarea" required>{{ old('description', $attraction->description) }}</textarea>
            </div>
            <div>
                <label class="form-label">History</label>
                <textarea name="history" rows="6" class="form-textarea">{{ old('history', $attraction->history) }}</textarea>
            </div>
            <div>
                <label class="form-label">Interesting Facts</label>
                <textarea name="interesting_facts" rows="4" class="form-textarea">{{ old('interesting_facts', $attraction->interesting_facts) }}</textarea>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">GPS Coordinates</h3>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="form-label">Latitude</label>
                <input type="number" step="any" name="latitude" value="{{ old('latitude', $attraction->latitude) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Longitude</label>
                <input type="number" step="any" name="longitude" value="{{ old('longitude', $attraction->longitude) }}" class="form-input">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Visit Information</h3>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="form-label">Visiting Hours</label>
                <input type="text" name="visiting_hours" value="{{ old('visiting_hours', $attraction->visiting_hours) }}" class="form-input">
            </div>
            <div>
                <label class="form-label">Entry Fee</label>
                <input type="text" name="entry_fee" value="{{ old('entry_fee', $attraction->entry_fee) }}" class="form-input">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Media</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="form-label">Thumbnail Image</label>
                @if($attraction->thumbnail)
                <img src="{{ $attraction->thumbnail_url }}" class="w-24 h-24 object-cover rounded-xl mb-2">
                @endif
                <input type="file" name="thumbnail" accept="image/*" class="form-input">
            </div>
            <div>
                <label class="form-label">Hero Image</label>
                @if($attraction->hero_image)
                <img src="{{ $attraction->hero_image_url }}" class="w-full h-24 object-cover rounded-xl mb-2">
                @endif
                <input type="file" name="hero_image" accept="image/*" class="form-input">
            </div>
            <div>
                <label class="form-label">Audio Introduction</label>
                @if($attraction->audio)
                <audio controls class="w-full mb-2" preload="none">
                    <source src="{{ asset('storage/' . $attraction->audio) }}">
                </audio>
                @endif
                <input type="file" name="audio" accept=".mp3,.wav,.ogg" class="form-input">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Settings</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="form-label">Visibility</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ old('status', $attraction->status) === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ old('status', $attraction->status) === 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ old('status', $attraction->status) === 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <div class="flex items-center gap-3 pt-6">
                <input type="checkbox" name="is_featured" id="is_featured" value="1"
                       {{ old('is_featured', $attraction->is_featured) ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-forest focus:ring-forest">
                <label for="is_featured" class="text-sm font-medium text-dark">Featured Attraction</label>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Save Changes</button>
        <a href="{{ route('admin.scenes.index', $attraction) }}" class="btn-secondary">Manage Scenes</a>
        <a href="{{ route('admin.attractions.index') }}" class="btn-ghost">Cancel</a>
    </div>
</form>
@endsection
