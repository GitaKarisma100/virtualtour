<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
