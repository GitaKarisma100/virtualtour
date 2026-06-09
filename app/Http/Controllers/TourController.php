<?php

namespace App\Http\Controllers;

use App\Models\Building;
use App\Models\Location;

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

        $locationsJson = $this->generateLocationsJson($building);

        return view('tour.show', compact('building', 'locationsJson'));
    }

    /**
     * Fitur Preview untuk Halaman Edit Gedung
     */
    public function previewBuilding(Building $building)
    {
        // Load semua lokasi termasuk yang is_active = false agar admin bisa preview
        $building->load(['locations' => function ($query) {
            $query->orderBy('sort_order');
        }, 'locations.hotspots', 'locations.hotspots.targetLocation']);

        $locationsJson = $this->generateLocationsJson($building);

        return view('tour.show', compact('building', 'locationsJson'));
    }

    /**
     * Fitur Preview untuk Halaman Edit Lokasi (Langsung mengarah ke lokasi spesifik)
     */
    public function previewLocation(Building $building, Location $location)
    {
        if ($location->building_id !== $building->id) {
            abort(404);
        }

        $building->load(['locations' => function ($query) {
            $query->orderBy('sort_order');
        }, 'locations.hotspots', 'locations.hotspots.targetLocation']);

        $locationsJson = $this->generateLocationsJson($building);

        // Mengirimkan data tambahan $location agar view tahu lokasi mana yang mau dibuka duluan
        return view('tour.show', compact('building', 'locationsJson', 'location'));
    }

    /**
     * Helper fungsi agar tidak menulis ulang kode JSON yang sama
     */
    private function generateLocationsJson(Building $building)
    {
        return json_encode($building->locations->map(function ($location) {
            return [
                'id' => $location->id,
                'name' => $location->name,
                'description' => $location->description,
                'image' => asset('storage/'.$location->image_path),
                'hfov' => (int) ($location->hfov ?? 100),
                'yaw' => (float) ($location->yaw ?? 0),
                'pitch' => (float) ($location->pitch ?? 0),
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
    }
}