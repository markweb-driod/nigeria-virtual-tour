<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PanoramaService
{
    /**
     * Store and optimize an uploaded panorama image.
     */
    public function storePanorama(UploadedFile $file, string $attractionSlug, string $sceneName): string
    {
        $directory = 'panoramas/' . $attractionSlug;
        $filename = Str::slug($sceneName) . '-' . time() . '.jpg';

        $path = $file->storeAs($directory, $filename, 'public');

        return $path;
    }

    /**
     * Store attraction thumbnail.
     */
    public function storeThumbnail(UploadedFile $file, string $attractionSlug): string
    {
        $directory = 'attractions/' . $attractionSlug . '/thumbs';
        $filename = 'thumb-' . time() . '.' . $file->extension();
        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Store hero image.
     */
    public function storeHeroImage(UploadedFile $file, string $attractionSlug): string
    {
        $directory = 'attractions/' . $attractionSlug;
        $filename = 'hero-' . time() . '.' . $file->extension();
        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Store audio file.
     */
    public function storeAudio(UploadedFile $file, string $attractionSlug): string
    {
        $directory = 'audio/' . $attractionSlug;
        $filename = 'narration-' . time() . '.' . $file->extension();
        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Store scene thumbnail.
     */
    public function storeSceneThumbnail(UploadedFile $file, string $attractionSlug, string $sceneSlug): string
    {
        $directory = 'panoramas/' . $attractionSlug . '/thumbs';
        $filename = $sceneSlug . '-thumb-' . time() . '.' . $file->extension();
        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Validate that a file is a valid equirectangular panorama (2:1 ratio).
     */
    public function validatePanorama(UploadedFile $file): array
    {
        $result = ['valid' => false, 'message' => '', 'ratio' => null];

        try {
            $imageInfo = getimagesize($file->getPathname());
            if (!$imageInfo) {
                $result['message'] = 'Could not read image dimensions.';
                return $result;
            }

            [$width, $height] = $imageInfo;
            $ratio = round($width / $height, 2);
            $result['ratio'] = $ratio;

            if ($ratio < 1.8 || $ratio > 2.2) {
                $result['message'] = "Image ratio is {$ratio}:1. Equirectangular panoramas should be 2:1 (e.g., 8192×4096).";
                return $result;
            }

            $result['valid'] = true;
            $result['message'] = "Valid panorama: {$width}×{$height} ({$ratio}:1)";
        } catch (\Exception $e) {
            $result['message'] = 'Image validation failed: ' . $e->getMessage();
        }

        return $result;
    }
}
