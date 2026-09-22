<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    public function index()
    {
        return view('tracking.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'tracking_number' => ['required', 'string'],
        ]);

        return redirect()->route('tracking.show', $request->tracking_number);
    }

    public function show(string $trackingNumber)
    {
        $shipment = Shipment::with(['customer', 'events' => function ($q) {
            $q->orderBy('created_at', 'asc');
        }])->where('tracking_number', $trackingNumber)->firstOrFail();

        return view('tracking.show', compact('shipment'));
    }
}