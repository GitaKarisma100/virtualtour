<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Hotspot;
use App\Models\Location;
use Illuminate\Http\Request;

class HotspotController extends Controller
{
    public function index(Building $building, Location $location)
    {
        $hotspots = $location->hotspots()->with('targetLocation')->get();
        return view('admin.hotspots.index', compact('building', 'location', 'hotspots'));
    }

    public function create(Building $building, Location $location)
    {
        $otherLocations = Location::where('building_id', $building->id)
            ->where('id', '!=', $location->id)
            ->orderBy('sort_order')
            ->get();
        return view('admin.hotspots.form', compact('building', 'location', 'otherLocations'));
    }

    public function store(Request $request, Building $building, Location $location)
    {
        $validated = $request->validate([
            'target_location_id' => 'nullable|exists:locations,id',
            'yaw' => 'required|numeric|min:-180|max:180',
            'pitch' => 'required|numeric|min:-90|max:90',
            'label' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:navigation,info,external_link',
            'icon' => 'nullable|string|max:50',
            'url' => 'nullable|url|max:255',
            
        ]);

        $hotspot = new Hotspot();
        $hotspot->location_id = $location->id;
        $hotspot->target_location_id = $validated['type'] === 'navigation' ? $validated['target_location_id'] : null;
        $hotspot->yaw = $validated['yaw'];
        $hotspot->pitch = $validated['pitch'];
        $hotspot->label = $validated['label'] ?? null;
        $hotspot->description = $validated['description'] ?? null;
        $hotspot->type = $validated['type'];
        $hotspot->icon = $validated['icon'] ?? null;
        $hotspot->url = $validated['type'] === 'external_link' ? $validated['url'] : null;
        $hotspot->save();

        return redirect()->route('admin.buildings.locations.hotspots.index', [$building, $location])->with('success', 'Hotspot created successfully.');
    }

    public function edit(Building $building, Location $location, Hotspot $hotspot)
    {
        $otherLocations = Location::where('building_id', $building->id)
            ->where('id', '!=', $location->id)
            ->orderBy('sort_order')
            ->get();
        return view('admin.hotspots.form', compact('building', 'location', 'hotspot', 'otherLocations'));
    }

    public function update(Request $request, Building $building, Location $location, Hotspot $hotspot)
    {
        $validated = $request->validate([
            'target_location_id' => 'nullable|exists:locations,id',
            'yaw' => 'required|numeric|min:-180|max:180',
            'pitch' => 'required|numeric|min:-90|max:90',
            'label' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:navigation,info,external_link',
            'icon' => 'nullable|string|max:50',
            'url' => 'nullable|url|max:255',
        ]);

        $hotspot->target_location_id = $validated['type'] === 'navigation' ? $validated['target_location_id'] : null;
        $hotspot->yaw = $validated['yaw'];
        $hotspot->pitch = $validated['pitch'];
        $hotspot->label = $validated['label'] ?? null;
        $hotspot->description = $validated['description'] ?? null;
        $hotspot->type = $validated['type'];
        $hotspot->icon = $validated['icon'] ?? null;
        $hotspot->url = $validated['type'] === 'external_link' ? $validated['url'] : null;
        $hotspot->save();

        return redirect()->route('admin.buildings.locations.hotspots.index', [$building, $location])->with('success', 'Hotspot updated successfully.');
    }

    public function destroy(Building $building, Location $location, Hotspot $hotspot)
    {
        $hotspot->delete();

        return redirect()->route('admin.buildings.locations.hotspots.index', [$building, $location])->with('success', 'Hotspot deleted successfully.');
    }
}
