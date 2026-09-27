@extends('layouts.admin')
@section('title', 'Create Attraction')
@section('page-title', 'Create New Attraction')
@section('breadcrumb', 'Admin / Attractions / Create')

@section('content')
<form method="POST" action="{{ route('admin.attractions.store') }}" enctype="multipart/form-data"
      class="max-w-4xl space-y-6">
    @csrf

    {{-- Basic Info --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Basic Information</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="md:col-span-2">
                <label class="form-label">Attraction Name *</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-input" required>
                @error('name')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Category *</label>
                <select name="category_id" class="form-select" required>
                    <option value="">Select Category</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">State *</label>
                <select name="state_id" class="form-select" required>
                    <option value="">Select State</option>
                    @foreach($states as $state)
                    <option value="{{ $state->id }}" {{ old('state_id') == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                    @endforeach
                </select>
                @error('state_id')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">LGA</label>
                <input type="text" name="lga" value="{{ old('lga') }}" class="form-input" placeholder="e.g. Abeokuta North">
            </div>
            <div>
                <label class="form-label">Address</label>
                <input type="text" name="address" value="{{ old('address') }}" class="form-input">
            </div>
        </div>
    </div>

    {{-- Descriptions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Descriptions</h3>
        <div class="space-y-5">
            <div>
                <label class="form-label">Short Description * <span class="text-gray-400 font-normal">(max 500 chars)</span></label>
                <textarea name="short_description" rows="2" class="form-textarea" maxlength="500" required>{{ old('short_description') }}</textarea>
                @error('short_description')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Full Description *</label>
                <textarea name="description" rows="6" class="form-textarea" required>{{ old('description') }}</textarea>
            </div>
            <div>
                <label class="form-label">History</label>
                <textarea name="history" rows="6" class="form-textarea">{{ old('history') }}</textarea>
            </div>
            <div>
                <label class="form-label">Interesting Facts</label>
                <textarea name="interesting_facts" rows="4" class="form-textarea">{{ old('interesting_facts') }}</textarea>
            </div>
        </div>
    </div>

    {{-- GPS --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">GPS Coordinates</h3>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="form-label">Latitude</label>
                <input type="number" step="any" name="latitude" value="{{ old('latitude') }}" class="form-input" placeholder="e.g. 7.1557">
            </div>
            <div>
                <label class="form-label">Longitude</label>
                <input type="number" step="any" name="longitude" value="{{ old('longitude') }}" class="form-input" placeholder="e.g. 3.3453">
            </div>
        </div>
    </div>

    {{-- Visit Info --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Visit Information</h3>
        <div class="grid grid-cols-2 gap-5">
            <div>
                <label class="form-label">Visiting Hours</label>
                <input type="text" name="visiting_hours" value="{{ old('visiting_hours') }}" class="form-input" placeholder="e.g. 8am - 6pm daily">
            </div>
            <div>
                <label class="form-label">Entry Fee</label>
                <input type="text" name="entry_fee" value="{{ old('entry_fee') }}" class="form-input" placeholder="e.g. Free / &#8358;500">
            </div>
        </div>
    </div>

    {{-- Media --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Media</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="form-label">Thumbnail Image</label>
                <input type="file" name="thumbnail" accept="image/*" class="form-input">
                <p class="text-xs text-gray-400 mt-1">Max 5MB. Recommended: 400x400px</p>
            </div>
            <div>
                <label class="form-label">Hero Image</label>
                <input type="file" name="hero_image" accept="image/*" class="form-input">
                <p class="text-xs text-gray-400 mt-1">Max 10MB. Recommended: 1920x1080px</p>
            </div>
            <div>
                <label class="form-label">Audio Introduction</label>
                <input type="file" name="audio" accept=".mp3,.wav,.ogg" class="form-input">
                <p class="text-xs text-gray-400 mt-1">Max 20MB. MP3 recommended.</p>
            </div>
        </div>
    </div>

    {{-- Settings --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="font-heading font-bold text-base text-dark mb-5">Settings</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            <div>
                <label class="form-label">Visibility</label>
                <select name="status" class="form-select">
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : 'selected' }}>Draft</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                </select>
            </div>
            <div class="flex items-center gap-3 pt-6">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}
                       class="w-4 h-4 rounded border-gray-300 text-forest focus:ring-forest">
                <label for="is_featured" class="text-sm font-medium text-dark">Featured Attraction</label>
            </div>
        </div>
    </div>

    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Create Attraction</button>
        <a href="{{ route('admin.attractions.index') }}" class="btn-ghost">Cancel</a>
    </div>
</form>
@endsection
