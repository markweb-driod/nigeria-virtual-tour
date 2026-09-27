@extends('layouts.admin')
@section('title', 'Attractions')
@section('page-title', 'Attractions Manager')

@section('header-actions')
<a href="{{ route('admin.attractions.create') }}" class="btn-primary btn-sm">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
    </svg>
    New Attraction
</a>
@endsection

@section('content')
{{-- Search bar --}}
<form method="GET" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search attractions..."
           class="form-input max-w-xs">
    <select name="status" class="form-select w-auto">
        <option value="">All Status</option>
        <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
    </select>
    <button class="btn-primary btn-sm">Filter</button>
    @if(request()->hasAny(['search','status']))
    <a href="{{ route('admin.attractions.index') }}" class="btn-ghost btn-sm">Clear</a>
    @endif
</form>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-5 py-3">Attraction</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden md:table-cell">Category</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden lg:table-cell">State</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3">Status</th>
                <th class="text-left text-xs font-semibold text-gray-500 uppercase tracking-wide px-4 py-3 hidden md:table-cell">Tour</th>
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($attractions as $attraction)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <img src="{{ $attraction->thumbnail_url }}" alt="{{ $attraction->name }}"
                             class="w-10 h-10 object-cover rounded-xl flex-shrink-0"
                             onerror="this.src='/images/placeholder.webp'">
                        <div>
                            <p class="font-semibold text-sm text-dark">{{ $attraction->name }}</p>
                            <p class="text-xs text-gray-400">/{{ $attraction->slug }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-4 hidden md:table-cell">
                    <span class="text-sm text-gray-600">{{ $attraction->category->name }}</span>
                </td>
                <td class="px-4 py-4 hidden lg:table-cell">
                    <span class="text-sm text-gray-600">{{ $attraction->state->name }}</span>
                </td>
                <td class="px-4 py-4">
                    <span class="badge {{ $attraction->status === 'published' ? 'badge-green' : 'bg-gray-100 text-gray-500' }}">
                        {{ ucfirst($attraction->status) }}
                    </span>
                </td>
                <td class="px-4 py-4 hidden md:table-cell">
                    <span class="badge {{ $attraction->tour_available ? 'bg-forest-50 text-forest-600' : 'bg-gray-100 text-gray-400' }}">
                        {{ $attraction->tour_available ? '360° Ready' : 'No Tour' }}
                    </span>
                </td>
                <td class="px-4 py-4">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('admin.scenes.index', $attraction) }}"
                           class="text-xs text-blue-600 hover:text-blue-800 font-medium">Scenes</a>
                        <a href="{{ route('admin.attractions.edit', $attraction) }}"
                           class="text-xs text-forest hover:text-forest-700 font-medium">Edit</a>
                        <form method="POST" action="{{ route('admin.attractions.publish', $attraction) }}">
                            @csrf
                            <button class="text-xs text-gray-400 hover:text-dark font-medium">
                                {{ $attraction->status === 'published' ? 'Unpublish' : 'Publish' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.attractions.destroy', $attraction) }}"
                              onsubmit="return confirm('Delete this attraction?')">
                            @csrf @method('DELETE')
                            <button class="text-xs text-red-400 hover:text-red-600 font-medium">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-16">
                    <p class="text-gray-400 mb-3">No attractions found.</p>
                    <a href="{{ route('admin.attractions.create') }}" class="btn-primary btn-sm">Create First Attraction</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($attractions->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">{{ $attractions->links() }}</div>
    @endif
</div>
@endsection
