<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TourSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'attraction_id', 'session_uuid', 'device_type', 'browser', 'os',
        'avg_fps', 'load_time_ms', 'transition_time_ms', 'scenes_visited',
        'completed', 'started_at', 'ended_at'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
    ];

    public function attraction()
    {
        return $this->belongsTo(Attraction::class);
    }

    public function sceneViews()
    {
        return $this->hasMany(SceneView::class);
    }
}
