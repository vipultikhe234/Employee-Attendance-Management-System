<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::all();
        return view('admin.locations.index', compact('locations'));
    }

    public function create()
    {
        return view('admin.locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'location_name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:10',
            'in_time' => 'required',
            'out_time' => 'required',
            'early_buffer' => 'required|integer|min:0',
            'late_buffer' => 'required|integer|min:0',
        ]);

        Location::create($validated);
        return redirect()->route('admin.locations.index')->with('success', 'Location created successfully.');
    }

    public function edit(Location $location)
    {
        return view('admin.locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'location_name' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:10',
            'in_time' => 'required',
            'out_time' => 'required',
            'early_buffer' => 'required|integer|min:0',
            'late_buffer' => 'required|integer|min:0',
        ]);

        $location->update($validated);
        return redirect()->route('admin.locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->route('admin.locations.index')->with('success', 'Location deleted successfully.');
    }
}
