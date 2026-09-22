<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::with('user')->latest()->paginate(15);
        return view('drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('drivers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'email'          => ['required', 'email', 'unique:users,email'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'password'       => ['required', 'string', 'min:6'],
            'license_number' => ['required', 'string', 'unique:drivers,license_number'],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => 'driver',
        ]);

        Driver::create([
            'user_id'        => $user->id,
            'license_number' => $data['license_number'],
            'status'         => 'available',
        ]);

        return redirect()->route('drivers.index')
                         ->with('success', 'Driver added.');
    }

    public function show(Driver $driver)
    {
        $driver->load('user', 'assignments.shipment');
        return view('drivers.show', compact('driver'));
    }

    public function edit(Driver $driver)
    {
        $driver->load('user');
        return view('drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'phone'          => ['nullable', 'string', 'max:30'],
            'license_number' => ['required', 'string', 'unique:drivers,license_number,' . $driver->id],
            'status'         => ['required', 'in:available,on_delivery,off_duty'],
        ]);

        $driver->user->update([
            'name'  => $data['name'],
            'phone' => $data['phone'] ?? null,
        ]);

        $driver->update([
            'license_number' => $data['license_number'],
            'status'         => $data['status'],
        ]);

        return redirect()->route('drivers.index')
                         ->with('success', 'Driver updated.');
    }

    public function destroy(Driver $driver)
    {
        $driver->user->delete();

        return redirect()->route('drivers.index')
                         ->with('success', 'Driver removed.');
    }
}