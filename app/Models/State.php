<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class State extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = ['name', 'slug', 'region'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function attractions()
    {
        return $this->hasMany(Attraction::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
