<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Shipment {{ $shipment->tracking_number }}</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #0F1B1C; font-size: 12px; margin: 0; padding: 24px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; border-bottom: 3px solid #073B3A; padding-bottom: 16px; margin-bottom: 24px; }
        .logo { font-size: 24px; font-weight: 800; color: #073B3A; }
        .logo span { color: #A9AB1F; }
        .meta { text-align: right; font-size: 11px; color: #6B7273; }
        h1 { font-size: 22px; font-weight: 800; margin: 0 0 4px; font-family: monospace; color: #073B3A; }
        h2 { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #6B7273; margin: 20px 0 8px; border-bottom: 1px solid #E5E7E7; padding-bottom: 4px; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 999px; background: #CBCD30; color: #073B3A; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 8px 0; vertical-align: top; font-size: 12px; }
        td:first-child { color: #6B7273; width: 35%; }
        .timeline { margin-top: 8px; }
        .timeline-item { border-left: 2px solid #CBCD30; padding: 0 0 12px 16px; position: relative; }
        .timeline-item::before { content: "●"; position: absolute; left: -7px; top: -2px; color: #073B3A; font-size: 14px; }
        .timeline-status { font-weight: 700; font-size: 12px; }
        .timeline-meta { font-size: 10px; color: #6B7273; }
        .footer { margin-top: 32px; padding-top: 16px; border-top: 1px solid #E5E7E7; font-size: 10px; color: #9CA3A4; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <div>
            <div class="logo">Logi<span>Flow</span></div>
            <div style="font-size: 11px; color: #6B7273; margin-top: 4px;">Shipment Summary</div>
        </div>
        <div class="meta">
            Generated: {{ now()->format('M d, Y — H:i') }}<br>
            Ref: {{ $shipment->tracking_number }}
        </div>
    </div>

    <h1>{{ $shipment->tracking_number }}</h1>
    <div class="badge">{{ $shipment->statusLabel() }}</div>

    <table style="margin-top: 20px;">
        <tr>
            <td style="width: 50%; padding-right: 24px;">
                <h2 style="margin-top: 0;">Origin</h2>
                <strong>{{ $shipment->origin_city ?? '—' }}</strong><br>
                <span style="color: #6B7273;">{{ $shipment->origin_address }}</span>
            </td>
            <td style="width: 50%;">
                <h2 style="margin-top: 0;">Destination</h2>
                <strong>{{ $shipment->destination_city ?? '—' }}</strong><br>
                <span style="color: #6B7273;">{{ $shipment->destination_address }}</span>
            </td>
        </tr>
    </table>

    <h2>Customer & Receiver</h2>
    <table>
        <tr>
            <td>Customer</td>
            <td>
                <strong>{{ $shipment->customer->contact_name ?? '—' }}</strong>
                @if($shipment->customer?->company_name) — {{ $shipment->customer->company_name }} @endif
            </td>
        </tr>
        <tr><td>Customer Phone</td><td>{{ $shipment->customer->phone ?? '—' }}</td></tr>
        <tr><td>Receiver</td><td>{{ $shipment->receiver_name ?? '—' }}</td></tr>
        <tr><td>Receiver Phone</td><td>{{ $shipment->receiver_phone ?? '—' }}</td></tr>
    </table>

    <h2>Package</h2>
    <table>
        <tr><td>Weight</td><td>{{ $shipment->weight_kg ? $shipment->weight_kg . ' kg' : '—' }}</td></tr>
        <tr><td>Cost</td><td>{{ $shipment->cost ? '₦' . number_format($shipment->cost, 2) : '—' }}</td></tr>
        <tr><td>Description</td><td>{{ $shipment->description ?? '—' }}</td></tr>
        <tr><td>Expected Delivery</td><td>{{ $shipment->expected_delivery?->format('M d, Y') ?? '—' }}</td></tr>
        @if($shipment->delivered_at)
            <tr><td>Delivered At</td><td>{{ $shipment->delivered_at->format('M d, Y — H:i') }}</td></tr>
        @endif
    </table>

    @if($shipment->activeAssignment)
        <h2>Assignment</h2>
        <table>
            <tr><td>Driver</td><td>{{ $shipment->activeAssignment->driver->user->name ?? '—' }}</td></tr>
            @if($shipment->activeAssignment->vehicle)
                <tr><td>Vehicle</td><td>{{ $shipment->activeAssignment->vehicle->plate_number }} ({{ $shipment->activeAssignment->vehicle->type }})</td></tr>
            @endif
            <tr><td>Assigned At</td><td>{{ $shipment->activeAssignment->assigned_at->format('M d, Y — H:i') }}</td></tr>
        </table>
    @endif

    <h2>Tracking History</h2>
    @if($shipment->events->isEmpty())
        <p style="color: #6B7273;">No events recorded.</p>
    @else
        <div class="timeline">
            @foreach($shipment->events as $event)
                <div class="timeline-item">
                    <div class="timeline-status">{{ $event->statusLabel() }}</div>
                    @if($event->location) <div class="timeline-meta">📍 {{ $event->location }}</div> @endif
                    @if($event->note) <div class="timeline-meta">{{ $event->note }}</div> @endif
                    <div class="timeline-meta">{{ $event->created_at->format('M d, Y — H:i') }}</div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="footer">
        LogiFlow — Modern Logistics Management · This document was generated automatically.
    </div>

</body>
</html>