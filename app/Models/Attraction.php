<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Attraction extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'category_id', 'state_id', 'name', 'slug', 'short_description',
        'description', 'history', 'interesting_facts', 'address', 'lga',
        'latitude', 'longitude', 'thumbnail', 'hero_image', 'audio',
        'visiting_hours', 'entry_fee', 'is_featured', 'tour_available',
        'status', 'view_count'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'tour_available' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function scenes()
    {
        return $this->hasMany(Scene::class)->orderBy('sort_order');
    }

    public function defaultScene()
    {
        return $this->hasOne(Scene::class)->where('is_default', true);
    }

    public function publishedScenes()
    {
        return $this->hasMany(Scene::class)->where('status', 'published')->orderBy('sort_order');
    }

    public function mediaItems()
    {
        return $this->hasMany(MediaItem::class)->orderBy('sort_order');
    }

    public function tourSessions()
    {
        return $this->hasMany(TourSession::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset('storage/' . $this->thumbnail) : asset('images/placeholder.webp');
    }

    public function getHeroImageUrlAttribute()
    {
        return $this->hero_image ? asset('storage/' . $this->hero_image) : asset('images/hero-placeholder.webp');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeWithTour($query)
    {
        return $query->where('tour_available', true);
    }

    public function incrementViews()
    {
        $this->increment('view_count');
    }
}
