<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AttractionApiController;
use App\Http\Controllers\Api\TourApiController;
use App\Http\Controllers\Api\AnalyticsApiController;

// Public API
Route::prefix('v1')->group(function () {

    Route::get('/attractions', [AttractionApiController::class, 'index']);
    Route::get('/attractions/{slug}', [AttractionApiController::class, 'show']);
    Route::get('/attractions/{slug}/tour', [TourApiController::class, 'config']);
    Route::get('/scenes/{scene}', [TourApiController::class, 'scene']);
    Route::get('/scenes/{scene}/hotspots', [TourApiController::class, 'hotspots']);

    // Analytics (write-only, no auth needed)
    Route::post('/analytics/session/start', [AnalyticsApiController::class, 'startSession']);
    Route::post('/analytics/session/end', [AnalyticsApiController::class, 'endSession']);
    Route::post('/analytics/scene-view', [AnalyticsApiController::class, 'recordSceneView']);
});
