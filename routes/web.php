<?php

use App\Http\Controllers\Admin\BuildingController;
use App\Http\Controllers\Admin\HotspotController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\TourController;
use App\Models\Building;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $firstBuilding = Building::where('is_active', true)
        ->orderBy('sort_order')
        ->first();

    $buildings = Building::where('is_active', true)
        ->orderBy('sort_order')
        ->take(4)
        ->get();

    return view('welcome', [
        'firstTourUrl' => $firstBuilding ? route('tour.show', $firstBuilding) : null,
        'buildings' => $buildings,
    ]);
})->name('home');

Route::get('/explore', [TourController::class, 'index'])->name('tour.index');
Route::get('/tour/{building}', [TourController::class, 'show'])->name('tour.show');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/admin', function () {
        return view('admin.index');
    })->name('admin');

    Route::prefix('admin/virtual-tour')->name('admin.')->group(function () {
        Route::resource('buildings', BuildingController::class);
        Route::resource('buildings.locations', LocationController::class)->shallow(false);
        Route::resource('buildings.locations.hotspots', HotspotController::class)->shallow(false);
    });
// Rute Preview untuk Gedung
Route::get('admin/virtual-tour/buildings/{building}/preview', [App\Http\Controllers\Admin\BuildingPreviewController::class, 'previewBuilding'])
    ->name('admin.buildings.preview');
Route::get('admin/virtual-tour/buildings/{building}/locations/{location}/preview', [TourController::class, 'previewLocation'])
    ->name('admin.locations.preview');
});
