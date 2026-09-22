@extends('layouts.admin')

@section('title', 'My Deliveries')
@section('page_title', 'My Deliveries')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>My Deliveries</h1>
            <p>Your currently assigned shipments</p>
        </div>
    </div>

    @if(!$driver)
        <div class="adm-card" style="text-align: center; padding: 60px 24px;">
            <p style="font-size: 18px; font-weight: 600; margin: 0 0 8px;">No driver profile linked</p>
            <p style="color: #6B7273;">Your account isn't linked to a driver record. Ask an admin to set it up.</p>
        </div>
    @else
        <div class="adm-card" style="margin-bottom: 20px;">
            <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                <div>
                    <p style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 4px;">License</p>
                    <p style="font-size: 16px; font-weight: 600; font-family: monospace; margin: 0;">{{ $driver->license_number }}</p>
                </div>
                <div>
                    <p style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 4px;">Status</p>
                    <span class="adm-badge {{ $driver->status === 'available' ? 'adm-badge-delivered' : 'adm-badge-in_transit' }}">
                        {{ ucfirst(str_replace('_', ' ', $driver->status)) }}
                    </span>
                </div>
                <div>
                    <p style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 4px;">Active</p>
                    <p style="font-size: 16px; font-weight: 600; margin: 0;">{{ $shipments->count() }}</p>
                </div>
            </div>
        </div>

        @if($shipments->isEmpty())
            <div class="adm-card" style="text-align: center; padding: 60px 24px;">
                <p style="font-size: 18px; font-weight: 600; margin: 0 0 8px;">No active deliveries</p>
                <p style="color: #6B7273;">You have no shipments assigned right now.</p>
            </div>
        @else
            <div class="adm-card" style="padding: 0; overflow: hidden;">
                <div style="overflow-x: auto;">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Tracking</th>
                                <th>Customer</th>
                                <th>Destination</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shipments as $shipment)
                                <tr>
                                    <td><a href="{{ route('driver.shipments.show', $shipment) }}">{{ $shipment->tracking_number }}</a></td>
                                    <td>{{ $shipment->customer->contact_name ?? '—' }}</td>
                                    <td style="font-size: 13px;">{{ $shipment->destination_city ?? $shipment->destination_address }}</td>
                                    <td>
                                        <span class="adm-badge adm-badge-{{ $shipment->status }}">{{ $shipment->statusLabel() }}</span>
                                    </td>
                                    <td style="text-align: right;">
                                        <a href="{{ route('driver.shipments.show', $shipment) }}" class="adm-btn adm-btn-outline adm-btn-sm">View</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    @endif

@endsection