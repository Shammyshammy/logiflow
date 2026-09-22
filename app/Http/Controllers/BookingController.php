<?php

namespace App\Http\Controllers;

use App\Mail\ShipmentStatusChanged;
use App\Models\Customer;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function show()
    {
        return view('booking.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'contact_name'        => ['required', 'string', 'max:255'],
            'email'               => ['required', 'email', 'max:255'],
            'phone'               => ['required', 'string', 'max:30'],
            'company_name'        => ['nullable', 'string', 'max:255'],

            'origin_address'      => ['required', 'string', 'max:255'],
            'origin_city'         => ['required', 'string', 'max:100'],
            'destination_address' => ['required', 'string', 'max:255'],
            'destination_city'    => ['required', 'string', 'max:100'],

            'receiver_name'       => ['required', 'string', 'max:255'],
            'receiver_phone'      => ['required', 'string', 'max:30'],

            'weight_kg'           => ['nullable', 'numeric', 'min:0'],
            'description'         => ['required', 'string'],
        ]);

        $shipment = null;
        $event = null;

        DB::transaction(function () use ($data, &$shipment, &$event) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['contact_name'],
                    'phone'    => $data['phone'],
                    'password' => Hash::make(Str::random(16)),
                    'role'     => 'customer',
                ]
            );

            $customer = Customer::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'contact_name' => $data['contact_name'],
                    'company_name' => $data['company_name'] ?? null,
                    'email'        => $data['email'],
                    'phone'        => $data['phone'],
                ]
            );

            $shipment = Shipment::create([
                'tracking_number'     => $this->generateTrackingNumber(),
                'customer_id'         => $customer->id,
                'created_by'          => null,
                'origin_address'      => $data['origin_address'],
                'origin_city'         => $data['origin_city'],
                'destination_address' => $data['destination_address'],
                'destination_city'    => $data['destination_city'],
                'receiver_name'       => $data['receiver_name'],
                'receiver_phone'      => $data['receiver_phone'],
                'weight_kg'           => $data['weight_kg'] ?? null,
                'description'         => $data['description'],
                'status'              => 'pending',
            ]);

            $event = ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status'      => 'pending',
                'location'    => $shipment->origin_city,
                'note'        => 'Booking submitted online.',
            ]);
        });

        // Send booking confirmation (safe — event is guaranteed non-null here)
        Mail::to($data['email'])->send(
            new ShipmentStatusChanged($shipment, $event)
        );

        return redirect()->route('booking.success', $shipment->tracking_number);
    }

    public function success(string $trackingNumber)
    {
        $shipment = Shipment::where('tracking_number', $trackingNumber)->firstOrFail();

        return view('booking.success', compact('shipment'));
    }

    private function generateTrackingNumber(): string
    {
        do {
            $number = 'LGF-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (Shipment::where('tracking_number', $number)->exists());

        return $number;
    }
}