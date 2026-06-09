<?php

namespace App\Http\Controllers;

use App\Models\Building;

class TourController extends Controller
{
    public function index()
    {
        $buildings = Building::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('tour.index', compact('buildings'));
    }

    public function show(Building $building)
    {
        $building->load(['locations' => function ($query) {
            $query->where('is_active', true)->orderBy('sort_order');
        }, 'locations.hotspots', 'locations.hotspots.targetLocation']);

        $locationsJson = json_encode($building->locations->map(function ($location) {
            return [
                'id' => $location->id,
                'name' => $location->name,
                'description' => $location->description,
                'image' => asset('storage/'.$location->image_path),
                'hfov' => (int) ($location->hfov ?? 100),
                'yaw' => (float) ($location->yaw ?? 0),
                'pitch' => (float) ($location->pitch ?? 0),
                'map_x' => $location->map_x !== null ? (float) $location->map_x : null,
                'map_y' => $location->map_y !== null ? (float) $location->map_y : null,
                'hotspots' => $location->hotspots->map(function ($hotspot) {
                    return [
                        'id' => $hotspot->id,
                        'yaw' => (float) $hotspot->yaw,
                        'pitch' => (float) $hotspot->pitch,
                        'targetId' => $hotspot->target_location_id,
                        'label' => $hotspot->label,
                        'description' => $hotspot->description,
                        'thumbnail' => $hotspot->thumbnail_path ? asset('storage/'.$hotspot->thumbnail_path) : null,
                        'type' => $hotspot->type,
                        'icon' => $hotspot->icon,
                        'url' => $hotspot->url,
                    ];
                })->toArray(),
            ];
        })->toArray());

        $buildingLat = $building->latitude ? (float) $building->latitude : null;
        $buildingLng = $building->longitude ? (float) $building->longitude : null;

        return view('tour.show', compact('building', 'locationsJson', 'buildingLat', 'buildingLng'));
    }
}
