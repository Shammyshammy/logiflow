<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::latest()->paginate(15);
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plate_number' => ['required', 'string', 'max:50', 'unique:vehicles,plate_number'],
            'type'         => ['required', 'string', 'max:50'],
            'capacity_kg'  => ['nullable', 'numeric', 'min:0'],
            'status'       => ['required', 'in:available,in_use,maintenance'],
        ]);

        Vehicle::create($data);

        return redirect()->route('vehicles.index')
                         ->with('success', 'Vehicle added.');
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load('assignments.shipment', 'assignments.driver.user');
        return view('vehicles.show', compact('vehicle'));
    }

    public function edit(Vehicle $vehicle)
    {
        return view('vehicles.edit', compact('vehicle'));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $data = $request->validate([
            'plate_number' => ['required', 'string', 'max:50', 'unique:vehicles,plate_number,' . $vehicle->id],
            'type'         => ['required', 'string', 'max:50'],
            'capacity_kg'  => ['nullable', 'numeric', 'min:0'],
            'status'       => ['required', 'in:available,in_use,maintenance'],
        ]);

        $vehicle->update($data);

        return redirect()->route('vehicles.index')
                         ->with('success', 'Vehicle updated.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $vehicle->delete();

        return redirect()->route('vehicles.index')
                         ->with('success', 'Vehicle deleted.');
    }
}