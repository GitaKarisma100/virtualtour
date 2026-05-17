<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LocationController extends Controller
{
    public function index(Building $building)
    {
        $locations = $building->locations()->paginate(10);
        return view('admin.locations.index', compact('building', 'locations'));
    }

    public function create(Building $building)
    {
        return view('admin.locations.form', compact('building'));
    }

    public function store(Request $request, Building $building)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|max:10240',
            'hfov' => 'nullable|numeric|min:1|max:360',
            'yaw' => 'nullable|numeric|min:-180|max:180',
            'pitch' => 'nullable|numeric|min:-90|max:90',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $location = new Location();
        $location->building_id = $building->id;
        $location->name = $validated['name'];
        $location->description = $validated['description'] ?? null;
        $location->hfov = $validated['hfov'] ?? 90;
        $location->yaw = $validated['yaw'] ?? 0;
        $location->pitch = $validated['pitch'] ?? 0;
        $location->sort_order = $validated['sort_order'] ?? 0;
        $location->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $location->image_path = $request->file('image')->store('locations', 'public');
        }

        $location->save();

        return redirect()->route('admin.buildings.locations.index', $building)->with('success', 'Location created successfully.');
    }

    public function edit(Building $building, Location $location)
    {
        return view('admin.locations.form', compact('building', 'location'));
    }

    public function update(Request $request, Building $building, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240',
            'hfov' => 'nullable|numeric|min:1|max:360',
            'yaw' => 'nullable|numeric|min:-180|max:180',
            'pitch' => 'nullable|numeric|min:-90|max:90',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $location->name = $validated['name'];
        $location->description = $validated['description'] ?? null;
        $location->hfov = $validated['hfov'] ?? 90;
        $location->yaw = $validated['yaw'] ?? 0;
        $location->pitch = $validated['pitch'] ?? 0;
        $location->sort_order = $validated['sort_order'] ?? 0;
        $location->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            if ($location->image_path) {
                Storage::disk('public')->delete($location->image_path);
            }
            $location->image_path = $request->file('image')->store('locations', 'public');
        }

        $location->save();

        return redirect()->route('admin.buildings.locations.index', $building)->with('success', 'Location updated successfully.');
    }

    public function destroy(Building $building, Location $location)
    {
        if ($location->image_path) {
            Storage::disk('public')->delete($location->image_path);
        }
        $location->delete();

        return redirect()->route('admin.buildings.locations.index', $building)->with('success', 'Location deleted successfully.');
    }
}
