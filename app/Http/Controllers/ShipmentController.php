<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Shipment::with('customer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('contact_name', 'like', "%{$search}%")
                         ->orWhere('company_name', 'like', "%{$search}%");
                  });
            });
        }

        $shipments = $query->latest()->paginate(15)->withQueryString();

        return view('shipments.index', compact('shipments'));
    }

    public function create()
    {
        $customers = Customer::orderBy('contact_name')->get();
        return view('shipments.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id'         => ['required', 'exists:customers,id'],
            'origin_address'      => ['required', 'string', 'max:255'],
            'origin_city'         => ['nullable', 'string', 'max:100'],
            'destination_address' => ['required', 'string', 'max:255'],
            'destination_city'    => ['nullable', 'string', 'max:100'],
            'receiver_name'       => ['nullable', 'string', 'max:255'],
            'receiver_phone'      => ['nullable', 'string', 'max:30'],
            'weight_kg'           => ['nullable', 'numeric', 'min:0'],
            'description'         => ['nullable', 'string'],
            'cost'                => ['nullable', 'numeric', 'min:0'],
            'expected_delivery'   => ['nullable', 'date'],
            'origin_lat'          => ['nullable', 'numeric', 'between:-90,90'],
            'origin_lng'          => ['nullable', 'numeric', 'between:-180,180'],
            'destination_lat'     => ['nullable', 'numeric', 'between:-90,90'],
            'destination_lng'     => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $shipment = null;

        DB::transaction(function () use ($data, $request, &$shipment) {
            $data['tracking_number'] = $this->generateTrackingNumber();
            $data['created_by']      = $request->user()->id;
            $data['status']          = 'pending';

            $shipment = Shipment::create($data);

            ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status'      => 'pending',
                'location'    => $shipment->origin_city,
                'note'        => 'Shipment created.',
                'created_by'  => $request->user()->id,
            ]);
        });

        ActivityLog::record('shipment.created', 'Created shipment ' . $shipment->tracking_number, $shipment);

        return redirect()->route('shipments.index')
                         ->with('success', 'Shipment created successfully.');
    }

    public function show(Shipment $shipment)
    {
        $shipment->load([
            'customer',
            'events.creator',
            'assignments.driver.user',
            'assignments.vehicle',
        ]);

        $drivers  = Driver::with('user')->where('status', 'available')->get();
        $vehicles = Vehicle::where('status', 'available')->get();

        return view('shipments.show', compact('shipment', 'drivers', 'vehicles'));
    }

    public function edit(Shipment $shipment)
    {
        $customers = Customer::orderBy('contact_name')->get();
        return view('shipments.edit', compact('shipment', 'customers'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $data = $request->validate([
            'customer_id'         => ['required', 'exists:customers,id'],
            'origin_address'      => ['required', 'string', 'max:255'],
            'origin_city'         => ['nullable', 'string', 'max:100'],
            'destination_address' => ['required', 'string', 'max:255'],
            'destination_city'    => ['nullable', 'string', 'max:100'],
            'receiver_name'       => ['nullable', 'string', 'max:255'],
            'receiver_phone'      => ['nullable', 'string', 'max:30'],
            'weight_kg'           => ['nullable', 'numeric', 'min:0'],
            'description'         => ['nullable', 'string'],
            'cost'                => ['nullable', 'numeric', 'min:0'],
            'expected_delivery'   => ['nullable', 'date'],
            'origin_lat'          => ['nullable', 'numeric', 'between:-90,90'],
            'origin_lng'          => ['nullable', 'numeric', 'between:-180,180'],
            'destination_lat'     => ['nullable', 'numeric', 'between:-90,90'],
            'destination_lng'     => ['nullable', 'numeric', 'between:-180,180'],
        ]);

        $shipment->update($data);

        ActivityLog::record('shipment.updated', 'Updated shipment ' . $shipment->tracking_number, $shipment);

        return redirect()->route('shipments.show', $shipment)
                         ->with('success', 'Shipment updated.');
    }

    public function destroy(Shipment $shipment)
    {
        $tracking = $shipment->tracking_number;
        $shipment->delete();

        ActivityLog::record('shipment.deleted', 'Deleted shipment ' . $tracking);

        return redirect()->route('shipments.index')
                         ->with('success', 'Shipment deleted.');
    }

    public function exportPdf(Shipment $shipment)
    {
        $shipment->load(['customer', 'events.creator', 'activeAssignment.driver.user', 'activeAssignment.vehicle']);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('shipments.pdf', compact('shipment'));
        $pdf->setPaper('A4');

        return $pdf->download("shipment-{$shipment->tracking_number}.pdf");
    }

    public function exportAllPdf(Request $request)
    {
        $query = Shipment::with('customer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $shipments = $query->latest()->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('shipments.pdf-all', compact('shipments'));
        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('shipments-' . now()->format('Y-m-d-His') . '.pdf');
    }

    public function exportCsv(Request $request)
    {
        $query = Shipment::with('customer');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $shipments = $query->latest()->get();

        $filename = 'shipments-' . now()->format('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($shipments) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Tracking Number',
                'Customer',
                'Company',
                'Origin',
                'Destination',
                'Status',
                'Weight (kg)',
                'Cost',
                'Expected Delivery',
                'Delivered At',
                'Created At',
            ]);

            foreach ($shipments as $shipment) {
                fputcsv($handle, [
                    $shipment->tracking_number,
                    $shipment->customer->contact_name ?? '',
                    $shipment->customer->company_name ?? '',
                    ($shipment->origin_city ?? '') . ' / ' . $shipment->origin_address,
                    ($shipment->destination_city ?? '') . ' / ' . $shipment->destination_address,
                    $shipment->statusLabel(),
                    $shipment->weight_kg,
                    $shipment->cost,
                    $shipment->expected_delivery?->format('Y-m-d'),
                    $shipment->delivered_at?->format('Y-m-d H:i'),
                    $shipment->created_at->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function assign(Request $request, Shipment $shipment)
    {
        $data = $request->validate([
            'driver_id'  => ['required', 'exists:drivers,id'],
            'vehicle_id' => ['nullable', 'exists:vehicles,id'],
        ]);

        $driver = Driver::findOrFail($data['driver_id']);

        if ($driver->status !== 'available' && ! $shipment->activeAssignment) {
            return back()->with('error', 'Driver is not available.');
        }

        DB::transaction(function () use ($shipment, $data, $request) {
            $active = $shipment->activeAssignment;
            if ($active) {
                $active->update(['unassigned_at' => now()]);
                $active->driver->update(['status' => 'available']);
                if ($active->vehicle) {
                    $active->vehicle->update(['status' => 'available']);
                }
            }

            \App\Models\ShipmentAssignment::create([
                'shipment_id'   => $shipment->id,
                'driver_id'     => $data['driver_id'],
                'vehicle_id'    => $data['vehicle_id'] ?? null,
                'assigned_at'   => now(),
            ]);

            $driver = Driver::find($data['driver_id']);
            $driver->update(['status' => 'on_delivery']);

            if (! empty($data['vehicle_id'])) {
                $vehicle = Vehicle::find($data['vehicle_id']);
                $vehicle?->update(['status' => 'in_use']);
            }

            ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status'      => $shipment->status,
                'location'    => null,
                'note'        => 'Assigned to ' . $driver->user->name,
                'created_by'  => $request->user()->id,
            ]);
        });

        ActivityLog::record('shipment.assigned', 'Assigned shipment ' . $shipment->tracking_number, $shipment);

        return back()->with('success', 'Shipment assigned successfully.');
    }

    public function unassign(Request $request, Shipment $shipment)
    {
        $active = $shipment->activeAssignment;

        if (! $active) {
            return back()->with('error', 'No active assignment to remove.');
        }

        DB::transaction(function () use ($active, $shipment, $request) {
            $driverName = $active->driver->user->name ?? 'driver';

            $active->update(['unassigned_at' => now()]);
            $active->driver->update(['status' => 'available']);
            if ($active->vehicle) {
                $active->vehicle->update(['status' => 'available']);
            }

            ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status'      => $shipment->status,
                'note'        => 'Unassigned from ' . $driverName,
                'created_by'  => $request->user()->id,
            ]);
        });

        ActivityLog::record('shipment.unassigned', 'Unassigned shipment ' . $shipment->tracking_number, $shipment);

        return back()->with('success', 'Assignment removed.');
    }

    public function driverShow(Request $request, Shipment $shipment)
    {
        $driver = $request->user()->driver;

        if (! $driver) {
            abort(403, 'No driver profile.');
        }

        $isAssigned = $shipment->activeAssignment
            && $shipment->activeAssignment->driver_id === $driver->id;

        if (! $isAssigned) {
            abort(403, 'This shipment is not assigned to you.');
        }

        $shipment->load(['customer', 'events.creator', 'activeAssignment.vehicle']);

        return view('driver.shipments.show', compact('shipment', 'driver'));
    }

    private function generateTrackingNumber(): string
    {
        do {
            $number = 'LGF-' . date('Y') . '-' . strtoupper(Str::random(6));
        } while (Shipment::where('tracking_number', $number)->exists());

        return $number;
    }
}