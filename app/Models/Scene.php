<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Scene extends Model
{
    use HasFactory;

    protected $fillable = [
        'attraction_id', 'name', 'slug', 'panorama_path', 'thumbnail',
        'initial_yaw', 'initial_pitch', 'initial_hfov', 'sort_order',
        'latitude', 'longitude', 'description', 'audio_narration',
        'is_default', 'status'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'initial_yaw' => 'float',
        'initial_pitch' => 'float',
        'initial_hfov' => 'float',
    ];

    public function attraction()
    {
        return $this->belongsTo(Attraction::class);
    }

    public function hotspots()
    {
        return $this->hasMany(Hotspot::class)->orderBy('sort_order');
    }

    public function mediaItems()
    {
        return $this->hasMany(MediaItem::class);
    }

    public function sceneViews()
    {
        return $this->hasMany(SceneView::class);
    }

    public function getPanoramaUrlAttribute()
    {
        return $this->panorama_path ? asset('storage/' . $this->panorama_path) : null;
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : asset('images/scene-placeholder.webp');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }
}
