<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use App\Models\MediaItem;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    public function store(Request $request, Attraction $attraction)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file|max:20480|mimes:jpg,jpeg,png,webp,mp4,mp3,wav',
            'type' => 'required|in:photo,video,audio',
        ]);

        $mediaItems = [];
        foreach ($request->file('files') as $file) {
            $path = $file->store('gallery/' . $attraction->slug, 'public');
            $media = $attraction->mediaItems()->create([
                'type' => $request->type,
                'path' => $path,
                'caption' => $request->caption,
                'sort_order' => $attraction->mediaItems()->max('sort_order') + 1,
            ]);
            $mediaItems[] = $media;
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'media' => $mediaItems]);
        }

        return back()->with('success', count($mediaItems) . ' media item(s) uploaded.');
    }

    public function destroy(MediaItem $media)
    {
        $media->delete();
        return back()->with('success', 'Media deleted.');
    }
}
