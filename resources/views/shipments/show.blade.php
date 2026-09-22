@extends('layouts.admin')

@section('title', $shipment->tracking_number)
@section('page_title', 'Shipment Details')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1 style="font-family: monospace;">{{ $shipment->tracking_number }}</h1>
            <p>
                Created {{ $shipment->created_at->format('M d, Y — H:i') }}
                by {{ $shipment->creator->name ?? 'system' }}
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('shipments.index') }}" class="adm-btn adm-btn-outline">← Back</a>
            <a href="{{ route('shipments.edit', $shipment) }}" class="adm-btn adm-btn-outline">Edit</a>
            <a href="{{ route('shipments.pdf', $shipment) }}" class="adm-btn adm-btn-outline">↓ PDF</a>
            <a href="{{ route('tracking.show', $shipment->tracking_number) }}" target="_blank" class="adm-btn adm-btn-primary">Public View</a>
        </div>
    </div>

    <div class="adm-grid" style="grid-template-columns: 2fr 1fr; gap: 20px;">

        {{-- Left column --}}
        <div>
            {{-- Status + Route --}}
            <div class="adm-card" style="margin-bottom: 20px;">
                <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                    <span class="adm-badge adm-badge-{{ $shipment->status }}" style="font-size: 13px; padding: 8px 16px;">
                        {{ $shipment->statusLabel() }}
                    </span>
                </div>

                <div class="adm-grid adm-grid-2">
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

            {{-- Update Status --}}
            <div class="adm-card" style="margin-bottom: 20px;">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 16px;">Update Status</h2>
                <form method="POST" action="{{ route('shipments.events.store', $shipment) }}">
                    @csrf
                    <div class="adm-grid adm-grid-3" style="gap: 16px;">
                        <div class="adm-form-group" style="margin: 0;">
                            <label class="adm-label">Status *</label>
                            <select name="status" class="adm-select" required>
                                @foreach(\App\Models\Shipment::statuses() as $key => $label)
                                    <option value="{{ $key }}" @selected($shipment->status === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="adm-form-group" style="margin: 0;">
                            <label class="adm-label">Location</label>
                            <input type="text" name="location" class="adm-input" placeholder="e.g. Port Harcourt Hub">
                        </div>
                        <div class="adm-form-group" style="margin: 0;">
                            <label class="adm-label">Note</label>
                            <input type="text" name="note" class="adm-input" placeholder="Optional note">
                        </div>
                    </div>
                    <div style="margin-top: 16px; text-align: right;">
                        <button type="submit" class="adm-btn adm-btn-lime">Add Event</button>
                    </div>
                </form>
            </div>


            {{-- Map --}}
@if($shipment->origin_lat && $shipment->origin_lng && $shipment->destination_lat && $shipment->destination_lng)
    <div class="adm-card" style="margin-bottom: 20px;">
        <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 16px;">Route Map</h2>
        <div id="shipmentMap" style="height: 320px; border-radius: 10px; overflow: hidden;"></div>
    </div>

    @push('scripts')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const originLat = {{ $shipment->origin_lat }};
                const originLng = {{ $shipment->origin_lng }};
                const destLat   = {{ $shipment->destination_lat }};
                const destLng   = {{ $shipment->destination_lng }};

                const map = L.map('shipmentMap');

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap'
                }).addTo(map);

                const originMarker = L.marker([originLat, originLng]).addTo(map).bindPopup('Origin: {{ $shipment->origin_city ?? "Origin" }}');
                const destMarker   = L.marker([destLat, destLng]).addTo(map).bindPopup('Destination: {{ $shipment->destination_city ?? "Destination" }}');

                L.polyline([[originLat, originLng], [destLat, destLng]], {
                    color: '#073B3A', weight: 3, dashArray: '8 6'
                }).addTo(map);

                map.fitBounds([[originLat, originLng], [destLat, destLng]], { padding: [40, 40] });
            });
        </script>
    @endpush
@endif

            {{-- Timeline --}}
            <div class="adm-card">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">Tracking History</h2>

                @if($shipment->events->isEmpty())
                    <p style="color: #6B7273; text-align: center; padding: 24px 0;">No events yet.</p>
                @else
                    <div style="position: relative; padding-left: 32px;">
                        <div style="position: absolute; left: 11px; top: 6px; bottom: 6px; width: 2px; background: #E5E7E7;"></div>

                        @foreach($shipment->events as $event)
                            <div style="position: relative; padding-bottom: 24px;">
                                <div style="position: absolute; left: -32px; top: 0; width: 24px; height: 24px; border-radius: 50%; background: #073B3A; color: #CBCD30; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700;">
                                    ●
                                </div>
                                <div style="display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap; gap: 8px;">
                                    <p style="font-weight: 600; margin: 0;">{{ $event->statusLabel() }}</p>
                                    <span style="color: #6B7273; font-size: 12px;">{{ $event->created_at->format('M d, Y — H:i') }}</span>
                                </div>
                                @if($event->location)
                                    <p style="color: #6B7273; font-size: 13px; margin: 4px 0;">📍 {{ $event->location }}</p>
                                @endif
                                @if($event->note)
                                    <p style="color: #6B7273; font-size: 13px; margin: 4px 0;">{{ $event->note }}</p>
                                @endif
                                <p style="color: #9CA3A4; font-size: 12px; margin: 4px 0 0;">
                                    by {{ $event->creator->name ?? 'system' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Right column --}}
        <div>
            {{-- Customer --}}
            <div class="adm-card" style="margin-bottom: 20px;">
                <h3 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 12px;">Customer</h3>
                @if($shipment->customer)
                    <p style="font-weight: 600; font-size: 16px; margin: 0 0 4px;">{{ $shipment->customer->contact_name }}</p>
                    @if($shipment->customer->company_name)
                        <p style="color: #6B7273; font-size: 13px; margin: 0 0 8px;">{{ $shipment->customer->company_name }}</p>
                    @endif
                    <p style="font-size: 13px; margin: 4px 0;">📞 {{ $shipment->customer->phone }}</p>
                    @if($shipment->customer->email)
                        <p style="font-size: 13px; margin: 4px 0;">✉️ {{ $shipment->customer->email }}</p>
                    @endif
                    <a href="{{ route('customers.show', $shipment->customer) }}" class="adm-btn adm-btn-outline adm-btn-sm" style="margin-top: 12px;">View Customer</a>
                @else
                    <p style="color: #6B7273;">No customer assigned.</p>
                @endif
            </div>

            {{-- Details --}}
            <div class="adm-card" style="margin-bottom: 20px;">
                <h3 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 12px;">Details</h3>
                <div style="display: grid; gap: 10px; font-size: 14px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #6B7273;">Weight</span>
                        <span style="font-weight: 500;">{{ $shipment->weight_kg ? $shipment->weight_kg . ' kg' : '—' }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #6B7273;">Cost</span>
                        <span style="font-weight: 500;">{{ $shipment->cost ? '₦' . number_format($shipment->cost, 2) : '—' }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #6B7273;">Expected</span>
                        <span style="font-weight: 500;">{{ $shipment->expected_delivery?->format('M d, Y') ?? '—' }}</span>
                    </div>
                    @if($shipment->delivered_at)
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6B7273;">Delivered</span>
                            <span style="font-weight: 500;">{{ $shipment->delivered_at->format('M d, Y') }}</span>
                        </div>
                    @endif
                </div>
                @if($shipment->description)
                    <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #F0F1F1;">
                        <p style="font-size: 12px; color: #6B7273; margin: 0 0 4px;">Description</p>
                        <p style="font-size: 13px; margin: 0;">{{ $shipment->description }}</p>
                    </div>
                @endif

                <div style="display: flex; justify-content: space-between;">
    <span style="color: #6B7273;">ETA</span>
    <span style="font-weight: 500; color: {{ $shipment->isOverdue() ? '#991B1B' : '#073B3A' }};">
        {{ $shipment->etaLabel() }}
    </span>
</div>
            </div>

            {{-- Receiver --}}
            @if($shipment->receiver_name || $shipment->receiver_phone)
                <div class="adm-card" style="margin-bottom: 20px;">
                    <h3 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 12px;">Receiver</h3>
                    @if($shipment->receiver_name)
                        <p style="font-weight: 600; margin: 0 0 4px;">{{ $shipment->receiver_name }}</p>
                    @endif
                    @if($shipment->receiver_phone)
                        <p style="font-size: 13px; margin: 4px 0;">📞 {{ $shipment->receiver_phone }}</p>
                    @endif
                </div>
            @endif

            {{-- Assignment --}}
<div class="adm-card">
    <h3 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 12px;">Assignment</h3>

    @php $active = $shipment->activeAssignment; @endphp

    @if($active)
        <div style="background: #F5F6F7; padding: 14px; border-radius: 8px; margin-bottom: 12px;">
            <p style="font-size: 13px; margin: 0 0 6px;">
                <strong>Driver:</strong> {{ $active->driver->user->name ?? '—' }}
            </p>
            @if($active->vehicle)
                <p style="font-size: 13px; margin: 0 0 6px;">
                    <strong>Vehicle:</strong> {{ $active->vehicle->plate_number }}
                </p>
            @endif
            <p style="font-size: 12px; color: #6B7273; margin: 8px 0 0;">
                Assigned {{ $active->assigned_at->diffForHumans() }}
            </p>
        </div>

        <form method="POST" action="{{ route('shipments.unassign', $shipment) }}" onsubmit="return confirm('Remove this assignment?')">
            @csrf
            <button type="submit" class="adm-btn adm-btn-danger adm-btn-sm" style="width: 100%;">
                Remove Assignment
            </button>
        </form>
    @else
        <p style="font-size: 13px; color: #6B7273; margin: 0 0 12px;">Not currently assigned.</p>

        @if($drivers->isEmpty())
            <p style="font-size: 13px; color: #991B1B;">No available drivers.</p>
        @else
            <form method="POST" action="{{ route('shipments.assign', $shipment) }}">
                @csrf

                <div class="adm-form-group" style="margin-bottom: 12px;">
                    <label class="adm-label" style="font-size: 12px;">Driver *</label>
                    <select name="driver_id" class="adm-select" required style="font-size: 13px; padding: 8px 12px;">
                        <option value="">Select driver...</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">
                                {{ $driver->user->name ?? 'Driver #' . $driver->id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="adm-form-group" style="margin-bottom: 12px;">
                    <label class="adm-label" style="font-size: 12px;">Vehicle</label>
                    <select name="vehicle_id" class="adm-select" style="font-size: 13px; padding: 8px 12px;">
                        <option value="">No vehicle</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">
                                {{ $vehicle->plate_number }} ({{ $vehicle->type }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="adm-btn adm-btn-lime adm-btn-sm" style="width: 100%;">
                    Assign
                </button>
            </form>
        @endif
    @endif
</div>
        </div>
    </div>

@endsection