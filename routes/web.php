<?php

use App\Http\Controllers\TourController;
use App\Models\Building;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $firstBuilding = Building::where('is_active', true)
        ->orderBy('sort_order')
        ->first();

    return view('welcome', [
        'firstTourUrl' => $firstBuilding ? route('tour.show', $firstBuilding) : null,
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
        Route::resource('buildings', \App\Http\Controllers\Admin\BuildingController::class);
        Route::resource('buildings.locations', \App\Http\Controllers\Admin\LocationController::class)->shallow(false);
        Route::resource('buildings.locations.hotspots', \App\Http\Controllers\Admin\HotspotController::class)->shallow(false);
    });
});
