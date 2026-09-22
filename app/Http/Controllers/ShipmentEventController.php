<?php

namespace App\Http\Controllers;

use App\Events\ShipmentStatusUpdated;
use App\Models\ActivityLog;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipmentEventController extends Controller
{
    public function store(Request $request, Shipment $shipment)
    {
        $data = $request->validate([
            'status'   => ['required', 'in:pending,picked_up,in_transit,out_for_delivery,delivered,failed,cancelled'],
            'location' => ['nullable', 'string', 'max:255'],
            'note'     => ['nullable', 'string'],
        ]);

        $shipmentEvent = null;

        DB::transaction(function () use ($data, $shipment, $request, &$shipmentEvent) {
            $shipmentEvent = ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status'      => $data['status'],
                'location'    => $data['location'] ?? null,
                'note'        => $data['note'] ?? null,
                'created_by'  => $request->user()->id,
            ]);

            $update = ['status' => $data['status']];
            if ($data['status'] === 'delivered') {
                $update['delivered_at'] = now();
            }

            $shipment->update($update);

            if ($data['status'] === 'delivered') {
                $active = $shipment->activeAssignment;
                if ($active) {
                    $active->update(['unassigned_at' => now()]);
                    $active->driver->update(['status' => 'available']);
                    if ($active->vehicle) {
                        $active->vehicle->update(['status' => 'available']);
                    }
                }
            }
        });

        ActivityLog::record('shipment.status_changed', 'Status of ' . $shipment->tracking_number . ' → ' . $data['status'], $shipment);

        if (in_array($data['status'], ['picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'failed'])) {
            event(new ShipmentStatusUpdated($shipment, $shipmentEvent));
        }

        return back()->with('success', 'Status updated.');
    }

    public function driverUpdate(Request $request, Shipment $shipment)
    {
        $driver = $request->user()->driver;

        if (! $driver) {
            abort(403);
        }

        $isAssigned = $shipment->activeAssignment
            && $shipment->activeAssignment->driver_id === $driver->id;

        if (! $isAssigned) {
            abort(403, 'Not your shipment.');
        }

        $data = $request->validate([
            'status'   => ['required', 'in:picked_up,in_transit,out_for_delivery,delivered,failed'],
            'location' => ['nullable', 'string', 'max:255'],
            'note'     => ['nullable', 'string'],
        ]);

        $shipmentEvent = null;

        DB::transaction(function () use ($data, $shipment, $request, &$shipmentEvent) {
            $shipmentEvent = ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status'      => $data['status'],
                'location'    => $data['location'] ?? null,
                'note'        => $data['note'] ?? null,
                'created_by'  => $request->user()->id,
            ]);

            $update = ['status' => $data['status']];
            if ($data['status'] === 'delivered') {
                $update['delivered_at'] = now();
            }

            $shipment->update($update);

            if ($data['status'] === 'delivered') {
                $active = $shipment->activeAssignment;
                if ($active) {
                    $active->update(['unassigned_at' => now()]);
                    $active->driver->update(['status' => 'available']);
                    if ($active->vehicle) {
                        $active->vehicle->update(['status' => 'available']);
                    }
                }
            }
        });

        ActivityLog::record('shipment.status_changed', 'Status of ' . $shipment->tracking_number . ' → ' . $data['status'], $shipment);

        if (in_array($data['status'], ['picked_up', 'in_transit', 'out_for_delivery', 'delivered', 'failed'])) {
            event(new ShipmentStatusUpdated($shipment, $shipmentEvent));
        }

        return back()->with('success', 'Status updated.');
    }
}