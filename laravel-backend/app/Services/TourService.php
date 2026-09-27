<?php

namespace App\Services;

use App\Models\Attraction;
use App\Models\Scene;
use App\Models\Hotspot;

class TourService
{
    /**
     * Build the full Pannellum-compatible tour configuration JSON for an attraction.
     */
    public function buildTourConfig(Attraction $attraction): array
    {
        $scenes = $attraction->publishedScenes()->with(['hotspots.targetScene'])->get();
        $defaultScene = $scenes->where('is_default', true)->first() ?? $scenes->first();

        $config = [
            'tour' => [
                'name' => $attraction->name,
                'description' => $attraction->short_description,
                'defaultScene' => $defaultScene?->slug ?? '',
            ],
            'scenes' => [],
        ];

        foreach ($scenes as $scene) {
            $sceneData = [
                'id' => $scene->id,
                'title' => $scene->name,
                'slug' => $scene->slug,
                'panorama' => $scene->panorama_url,
                'thumbnail' => $scene->thumbnail_url,
                'yaw' => $scene->initial_yaw,
                'pitch' => $scene->initial_pitch,
                'hfov' => $scene->initial_hfov,
                'description' => $scene->description,
                'audio' => $scene->audio_narration ? asset('storage/' . $scene->audio_narration) : null,
                'hotspots' => [],
            ];

            foreach ($scene->hotspots as $hotspot) {
                $hotspotData = [
                    'id' => $hotspot->id,
                    'type' => $hotspot->type,
                    'yaw' => $hotspot->yaw,
                    'pitch' => $hotspot->pitch,
                    'text' => $hotspot->label,
                    'description' => $hotspot->description,
                ];

                if ($hotspot->type === 'scene' && $hotspot->targetScene) {
                    $hotspotData['sceneId'] = $hotspot->targetScene->slug;
                }

                if ($hotspot->type === 'media') {
                    $hotspotData['mediaUrl'] = $hotspot->media_url;
                    $hotspotData['mediaType'] = $hotspot->media_type;
                }

                $sceneData['hotspots'][] = $hotspotData;
            }

            $config['scenes'][$scene->slug] = $sceneData;
        }

        return $config;
    }

    /**
     * Get scene list for navigation sidebar.
     */
    public function getSceneList(Attraction $attraction): array
    {
        return $attraction->publishedScenes()->get()->map(function ($scene) {
            return [
                'id' => $scene->id,
                'slug' => $scene->slug,
                'name' => $scene->name,
                'thumbnail' => $scene->thumbnail_url,
                'is_default' => $scene->is_default,
            ];
        })->toArray();
    }
}
