<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hotspot extends Model
{
    use HasFactory;

    protected $fillable = [
        'scene_id', 'type', 'yaw', 'pitch', 'label', 'description',
        'target_scene_id', 'media_path', 'media_type', 'icon', 'sort_order'
    ];

    protected $casts = [
        'yaw' => 'float',
        'pitch' => 'float',
    ];

    public function scene()
    {
        return $this->belongsTo(Scene::class);
    }

    public function targetScene()
    {
        return $this->belongsTo(Scene::class, 'target_scene_id');
    }

    public function getMediaUrlAttribute()
    {
        return $this->media_path ? asset('storage/' . $this->media_path) : null;
    }
}
