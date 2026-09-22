<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Shipment;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->isDriver()) {
            return $this->driverDashboard($user);
        }

        if ($user->isCustomer()) {
            return $this->customerDashboard($user);
        }

        return $this->adminDashboard();
    }

    private function adminDashboard()
{
    $stats = [
        'total_shipments'    => Shipment::count(),
        'pending'            => Shipment::where('status', 'pending')->count(),
        'in_transit'         => Shipment::where('status', 'in_transit')->count(),
        'delivered'          => Shipment::where('status', 'delivered')->count(),
        'total_customers'    => Customer::count(),
        'total_drivers'      => Driver::count(),
        'available_vehicles' => Vehicle::where('status', 'available')->count(),
    ];

    // Shipments created in last 7 days (daily counts)
    $trend = Shipment::selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->where('created_at', '>=', now()->subDays(6)->startOfDay())
        ->groupBy('date')
        ->orderBy('date')
        ->pluck('count', 'date')
        ->toArray();

    // Fill missing days with 0
    $labels = [];
    $counts = [];
    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i)->format('Y-m-d');
        $labels[] = now()->subDays($i)->format('M d');
        $counts[] = $trend[$date] ?? 0;
    }

    $statusBreakdown = [
        'pending'          => $stats['pending'],
        'in_transit'       => Shipment::whereIn('status', ['picked_up', 'in_transit', 'out_for_delivery'])->count(),
        'delivered'        => $stats['delivered'],
        'other'            => Shipment::whereIn('status', ['failed', 'cancelled'])->count(),
    ];

    $recentShipments = Shipment::with('customer')->latest()->take(10)->get();

    return view('dashboard', compact('stats', 'recentShipments', 'labels', 'counts', 'statusBreakdown'));
}

    private function driverDashboard($user)
    {
        $driver = $user->driver;

        $shipments = $driver
            ? Shipment::whereHas('activeAssignment', function ($q) use ($driver) {
                $q->where('driver_id', $driver->id);
            })->with('customer')->latest()->get()
            : collect();

        return view('dashboard-driver', compact('shipments', 'driver'));
    }

    private function customerDashboard($user)
    {
        $customer = $user->customer;

        $shipments = $customer
            ? Shipment::where('customer_id', $customer->id)->latest()->get()
            : collect();

        return view('dashboard-customer', compact('shipments', 'customer'));
    }
}