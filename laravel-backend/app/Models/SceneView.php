<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SceneView extends Model
{
    use HasFactory;

    protected $fillable = ['tour_session_id', 'scene_id', 'viewed_at'];

    protected $casts = [
        'viewed_at' => 'datetime',
    ];

    public function tourSession()
    {
        return $this->belongsTo(TourSession::class);
    }

    public function scene()
    {
        return $this->belongsTo(Scene::class);
    }
}
