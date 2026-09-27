<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaItem extends Model
{
    use HasFactory;

    protected $table = 'media_items';

    protected $fillable = [
        'attraction_id', 'scene_id', 'type', 'path', 'caption', 'sort_order'
    ];

    public function attraction()
    {
        return $this->belongsTo(Attraction::class);
    }

    public function scene()
    {
        return $this->belongsTo(Scene::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}
