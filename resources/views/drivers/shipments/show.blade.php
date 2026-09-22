@extends('layouts.admin')

@section('title', $shipment->tracking_number)
@section('page_title', 'Delivery Details')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1 style="font-family: monospace;">{{ $shipment->tracking_number }}</h1>
            <p>{{ $shipment->customer->contact_name ?? '—' }}</p>
        </div>
        <a href="{{ route('dashboard') }}" class="adm-btn adm-btn-outline">← Back</a>
    </div>

    <div class="adm-grid" style="grid-template-columns: 2fr 1fr; gap: 20px;">

        <div>
            <div class="adm-card" style="margin-bottom: 20px;">
                <span class="adm-badge adm-badge-{{ $shipment->status }}" style="font-size: 13px; padding: 8px 16px; margin-bottom: 16px; display: inline-block;">
                    {{ $shipment->statusLabel() }}
                </span>

                <div class="adm-grid adm-grid-2" style="margin-top: 20px;">
                    <div>
                        <p class="adm-stat-label">Origin</p>
                        <p style="font-weight: 600; margin: 0 0 4px;">{{ $shipment->origin_city ?? '—' }}</p>
                        <p style="color: #6B7273; font-size: 13px; margin: 0;">{{ $shipment->origin_address }}</p>
                    </div>
                    <div>
                        <p class="adm-stat-label">Destination</p>
                        <p style="font-weight: 600; margin: 0 0 4px;">{{ $shipment->destination_city ?? '—' }}</p>
                        <p style="color: #6B7273; font-size: 13px; margin: 0;">{{ $shipment->destination_address }}</p>
                    </div>
                </div>
            </div>

            @if(!$shipment->isDelivered())
                <div class="adm-card">
                    <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 16px;">Update Status</h2>

                    <form method="POST" action="{{ route('driver.shipments.status', $shipment) }}">
                        @csrf

                        <div class="adm-form-group">
                            <label class="adm-label">New Status *</label>
                            <select name="status" class="adm-select" required>
                                <option value="">Select...</option>
                                <option value="picked_up">Picked Up</option>
                                <option value="in_transit">In Transit</option>
                                <option value="out_for_delivery">Out for Delivery</option>
                                <option value="delivered">Delivered</option>
                                <option value="failed">Failed / Not Delivered</option>
                            </select>
                        </div>

                        <div class="adm-form-group">
                            <label class="adm-label">Current Location</label>
                            <input type="text" name="location" class="adm-input" placeholder="e.g. On A1 Highway near Abuja">
                        </div>

                        <div class="adm-form-group">
                            <label class="adm-label">Note</label>
                            <input type="text" name="note" class="adm-input" placeholder="Optional">
                        </div>

                        <button type="submit" class="adm-btn adm-btn-lime" style="width: 100%;">
                            Update Status
                        </button>
                    </form>
                </div>
            @else
                <div class="adm-card" style="text-align: center;">
                    <p style="font-size: 16px; font-weight: 700; color: #065F46;">✅ Delivered</p>
                    <p style="color: #6B7273;">{{ $shipment->delivered_at?->format('M d, Y — H:i') }}</p>
                </div>
            @endif
        </div>

        <div class="adm-card">
            <h3 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 12px;">Receiver</h3>

            @if($shipment->receiver_name)
                <p style="font-weight: 600; margin: 0 0 4px;">{{ $shipment->receiver_name }}</p>
            @endif
            @if($shipment->receiver_phone)
                <p style="font-size: 13px; margin: 4px 0;">📞 {{ $shipment->receiver_phone }}</p>
            @endif

            @if(!$shipment->receiver_name && !$shipment->receiver_phone)
                <p style="color: #6B7273; font-size: 13px;">No receiver information.</p>
            @endif

            @if($shipment->description)
                <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #F0F1F1;">
                    <p style="font-size: 12px; color: #6B7273; margin: 0 0 4px;">Package</p>
                    <p style="font-size: 13px; margin: 0;">{{ $shipment->description }}</p>
                </div>
            @endif
        </div>
    </div>

@endsection