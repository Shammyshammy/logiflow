<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Shipments</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; color: #0F1B1C; font-size: 10px; margin: 0; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #073B3A; padding-bottom: 12px; margin-bottom: 20px; }
        .logo { font-size: 22px; font-weight: 800; color: #073B3A; }
        .logo span { color: #A9AB1F; }
        h1 { font-size: 18px; font-weight: 800; margin: 0; color: #073B3A; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; background: #F5F6F7; padding: 8px 10px; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; border-bottom: 1px solid #E5E7E7; }
        td { padding: 10px; font-size: 10px; border-bottom: 1px solid #F0F1F1; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 999px; background: #CBCD30; color: #073B3A; font-weight: 700; font-size: 9px; text-transform: uppercase; }
        .footer { margin-top: 24px; padding-top: 12px; border-top: 1px solid #E5E7E7; font-size: 9px; color: #9CA3A4; text-align: center; }
    </style>
</head>
<body>

    <div class="header">
        <div class="logo">Logi<span>Flow</span></div>
        <div>
            <h1>All Shipments Report</h1>
            <div style="font-size: 10px; color: #6B7273; margin-top: 4px;">
                {{ $shipments->count() }} shipments · Generated {{ now()->format('M d, Y — H:i') }}
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Tracking</th>
                <th>Customer</th>
                <th>Origin</th>
                <th>Destination</th>
                <th>Status</th>
                <th>Weight</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach($shipments as $shipment)
                <tr>
                    <td><strong>{{ $shipment->tracking_number }}</strong></td>
                    <td>{{ $shipment->customer->contact_name ?? '—' }}</td>
                    <td>{{ $shipment->origin_city ?? '—' }}</td>
                    <td>{{ $shipment->destination_city ?? '—' }}</td>
                    <td><span class="badge">{{ $shipment->statusLabel() }}</span></td>
                    <td>{{ $shipment->weight_kg ? $shipment->weight_kg . ' kg' : '—' }}</td>
                    <td>{{ $shipment->created_at->format('M d, Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        LogiFlow — Modern Logistics Management · Report generated automatically
    </div>

</body>
</html>