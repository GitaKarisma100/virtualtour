<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Building;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BuildingController extends Controller
{
    public function index()
    {
        $buildings = Building::orderBy('sort_order')->paginate(10);

        return view('admin.buildings.index', compact('buildings'));
    }

    public function create()
    {
         // Hitung nomor berikutnya
    $lastId = Building::max('id') ?? 0;
    $nextNumber = $lastId + 1;

    // Default name
    $defaultName = 'Building ' . $nextNumber;

    return view('admin.buildings.form', compact('defaultName'));
}

        
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:10240',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean', 
        ]);

        $building = new Building();
        $building->name = $validated['name'];
        $building->description = $validated['description'] ?? null;
        $building->sort_order = $validated['sort_order'] ?? 0;
        $building->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('thumbnail')) {
            $building->thumbnail_path = $request->file('thumbnail')->store('buildings', 'public');
        }

        $building->save();

        return redirect()->route('admin.buildings.index')->with('success', 'Building created successfully.');
    }

    public function edit(Building $building)
    {
        return view('admin.buildings.form', compact('building'));
    }

    public function update(Request $request, Building $building)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'thumbnail' => 'nullable|image|max:10240',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $building->name = $validated['name'];
        $building->description = $validated['description'] ?? null;
        $building->sort_order = $validated['sort_order'] ?? 0;
        $building->is_active = $request->boolean('is_active', true);

        if ($request->hasFile('thumbnail')) {
            if ($building->thumbnail_path) {
                Storage::disk('public')->delete($building->thumbnail_path);
            }
            $building->thumbnail_path = $request->file('thumbnail')->store('buildings', 'public');
        }

        $building->save();

        return redirect()->route('admin.buildings.index')->with('success', 'Building updated successfully.');
    }

    public function destroy(Building $building)
    {
        if ($building->thumbnail_path) {
            Storage::disk('public')->delete($building->thumbnail_path);
        }
        $building->delete();

        return redirect()->route('admin.buildings.index')->with('success', 'Building deleted successfully.');
    }
}
