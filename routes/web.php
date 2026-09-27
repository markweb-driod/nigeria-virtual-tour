<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\AttractionController;
use App\Http\Controllers\Public\TourController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AttractionAdminController;
use App\Http\Controllers\Admin\SceneController;
use App\Http\Controllers\Admin\HotspotController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Auth\LoginController;

// ──────────────────────────────────────────────
// PUBLIC ROUTES
// ──────────────────────────────────────────────

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/explore', [AttractionController::class, 'index'])->name('explore');
Route::get('/attractions/{slug}', [AttractionController::class, 'show'])->name('attractions.show');
Route::get('/tour/{slug}', [TourController::class, 'show'])->name('tour.show');

Route::get('/search', [AttractionController::class, 'search'])->name('search');
Route::get('/category/{slug}', [AttractionController::class, 'byCategory'])->name('attractions.category');
Route::get('/state/{slug}', [AttractionController::class, 'byState'])->name('attractions.state');

// ──────────────────────────────────────────────
// AUTH ROUTES
// ──────────────────────────────────────────────

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('logout');

// ──────────────────────────────────────────────
// ADMIN ROUTES (protected)
// ──────────────────────────────────────────────

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AnalyticsController::class, 'index'])->name('analytics');

    // Attractions
    Route::resource('attractions', AttractionAdminController::class);
    Route::post('/attractions/{attraction}/publish', [AttractionAdminController::class, 'publish'])->name('attractions.publish');
    Route::post('/attractions/{attraction}/feature', [AttractionAdminController::class, 'toggleFeatured'])->name('attractions.feature');

    // Scenes
    Route::get('/attractions/{attraction}/scenes', [SceneController::class, 'index'])->name('scenes.index');
    Route::get('/attractions/{attraction}/scenes/create', [SceneController::class, 'create'])->name('scenes.create');
    Route::post('/attractions/{attraction}/scenes', [SceneController::class, 'store'])->name('scenes.store');
    Route::get('/scenes/{scene}/edit', [SceneController::class, 'edit'])->name('scenes.edit');
    Route::put('/scenes/{scene}', [SceneController::class, 'update'])->name('scenes.update');
    Route::delete('/scenes/{scene}', [SceneController::class, 'destroy'])->name('scenes.destroy');
    Route::post('/scenes/{scene}/set-default', [SceneController::class, 'setDefault'])->name('scenes.set-default');
    Route::post('/scenes/reorder', [SceneController::class, 'reorder'])->name('scenes.reorder');

    // Hotspots
    Route::post('/scenes/{scene}/hotspots', [HotspotController::class, 'store'])->name('hotspots.store');
    Route::put('/hotspots/{hotspot}', [HotspotController::class, 'update'])->name('hotspots.update');
    Route::delete('/hotspots/{hotspot}', [HotspotController::class, 'destroy'])->name('hotspots.destroy');

    // Media
    Route::post('/attractions/{attraction}/media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');
});
