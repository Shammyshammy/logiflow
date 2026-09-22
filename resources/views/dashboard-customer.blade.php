@extends('layouts.admin')

@section('title', 'My Shipments')
@section('page_title', 'My Shipments')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>My Shipments</h1>
            <p>All shipments on your account</p>
        </div>
    </div>

    @if(!$customer)
        <div class="adm-card" style="text-align: center; padding: 60px 24px;">
            <p style="font-size: 18px; font-weight: 600; margin: 0 0 8px;">No customer profile linked</p>
            <p style="color: #6B7273;">Your account isn't linked to a customer record. Contact support.</p>
        </div>
    @else
        @if($shipments->isEmpty())
            <div class="adm-card" style="text-align: center; padding: 60px 24px;">
                <p style="font-size: 18px; font-weight: 600; margin: 0 0 8px;">No shipments yet</p>
                <p style="color: #6B7273;">You haven't booked any shipments.</p>
            </div>
        @else
            <div class="adm-grid adm-grid-4" style="margin-bottom: 24px;">
                <div class="adm-stat">
                    <p class="adm-stat-label">Total</p>
                    <p class="adm-stat-value">{{ $shipments->count() }}</p>
                </div>
                <div class="adm-stat">
                    <p class="adm-stat-label">In Transit</p>
                    <p class="adm-stat-value lime">{{ $shipments->where('status', 'in_transit')->count() }}</p>
                </div>
                <div class="adm-stat">
                    <p class="adm-stat-label">Pending</p>
                    <p class="adm-stat-value">{{ $shipments->where('status', 'pending')->count() }}</p>
                </div>
                <div class="adm-stat">
                    <p class="adm-stat-label">Delivered</p>
                    <p class="adm-stat-value">{{ $shipments->where('status', 'delivered')->count() }}</p>
                </div>
            </div>

            <div class="adm-card" style="padding: 0; overflow: hidden;">
                <div style="overflow-x: auto;">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Tracking</th>
                                <th>Route</th>
                                <th>Status</th>
                                <th>Expected</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($shipments as $shipment)
                                <tr>
                                    <td><a href="{{ route('tracking.show', $shipment->tracking_number) }}" target="_blank">{{ $shipment->tracking_number }}</a></td>
                                    <td style="font-size: 13px;">
                                        {{ $shipment->origin_city ?? 'Origin' }}
                                        <span style="color: #CED1D1;">→</span>
                                        {{ $shipment->destination_city ?? 'Destination' }}
                                    </td>
                                    <td>
                                        <span class="adm-badge adm-badge-{{ $shipment->status }}">{{ $shipment->statusLabel() }}</span>
                                    </td>
                                    <td style="font-size: 13px;">{{ $shipment->expected_delivery?->format('M d, Y') ?? '—' }}</td>
                                    <td style="text-align: right;">
                                        <a href="{{ route('tracking.show', $shipment->tracking_number) }}" target="_blank" class="adm-btn adm-btn-outline adm-btn-sm">Track</a>
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