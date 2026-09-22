@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')

    {{-- Stats Row 1 --}}
    <div class="adm-grid adm-grid-4" style="margin-bottom: 24px;">
        <div class="adm-stat">
            <p class="adm-stat-label">Total Shipments</p>
            <p class="adm-stat-value">{{ $stats['total_shipments'] }}</p>
        </div>
        <div class="adm-stat">
            <p class="adm-stat-label">In Transit</p>
            <p class="adm-stat-value lime">{{ $stats['in_transit'] }}</p>
        </div>
        <div class="adm-stat">
            <p class="adm-stat-label">Pending</p>
            <p class="adm-stat-value">{{ $stats['pending'] }}</p>
        </div>
        <div class="adm-stat">
            <p class="adm-stat-label">Delivered</p>
            <p class="adm-stat-value">{{ $stats['delivered'] }}</p>
        </div>
    </div>

    {{-- Stats Row 2 --}}
    <div class="adm-grid adm-grid-4" style="margin-bottom: 24px;">
        <div class="adm-stat">
            <p class="adm-stat-label">Customers</p>
            <p class="adm-stat-value">{{ $stats['total_customers'] }}</p>
        </div>
        <div class="adm-stat">
            <p class="adm-stat-label">Drivers</p>
            <p class="adm-stat-value">{{ $stats['total_drivers'] }}</p>
        </div>
        <div class="adm-stat">
            <p class="adm-stat-label">Available Vehicles</p>
            <p class="adm-stat-value">{{ $stats['available_vehicles'] }}</p>
        </div>
        <div class="adm-stat">
            <p class="adm-stat-label">Overdue</p>
            <p class="adm-stat-value" style="color: #991B1B;">
                {{ \App\Models\Shipment::whereNotNull('expected_delivery')
                    ->where('expected_delivery', '<', now()->startOfDay())
                    ->whereNotIn('status', ['delivered', 'cancelled'])
                    ->count() }}
            </p>
        </div>
    </div>

    {{-- Charts --}}
    <div class="adm-grid adm-grid-2" style="margin-bottom: 24px;">
        <div class="adm-card">
            <h3 style="font-size: 16px; font-weight: 700; margin: 0 0 4px;">Shipments (last 7 days)</h3>
            <p style="font-size: 13px; color: #6B7273; margin: 0 0 20px;">Daily new shipment count</p>
            <canvas id="trendChart" height="120"></canvas>
        </div>

        <div class="adm-card">
            <h3 style="font-size: 16px; font-weight: 700; margin: 0 0 4px;">Status Breakdown</h3>
            <p style="font-size: 13px; color: #6B7273; margin: 0 0 20px;">Current shipment distribution</p>
            <canvas id="statusChart" height="120"></canvas>
        </div>
    </div>

    {{-- Recent Shipments --}}
    <div class="adm-card">
        <div class="adm-page-header" style="margin-bottom: 16px;">
            <div>
                <h2 style="font-size: 18px; font-weight: 700; margin: 0;">Recent Shipments</h2>
                <p style="margin: 4px 0 0; color: #6B7273; font-size: 13px;">Last 10 shipments created</p>
            </div>
            <a href="{{ route('shipments.create') }}" class="adm-btn adm-btn-lime">+ New Shipment</a>
        </div>

        @if($recentShipments->isEmpty())
            <p style="color: #6B7273; padding: 24px 0; text-align: center;">No shipments yet. Create your first one.</p>
        @else
            <div style="overflow-x: auto;">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Tracking</th>
                            <th>Customer</th>
                            <th>Destination</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentShipments as $shipment)
                            <tr>
                                <td><a href="{{ route('shipments.show', $shipment) }}">{{ $shipment->tracking_number }}</a></td>
                                <td>{{ $shipment->customer->contact_name ?? '—' }}</td>
                                <td>{{ $shipment->destination_city ?? $shipment->destination_address }}</td>
                                <td>
                                    <span class="adm-badge adm-badge-{{ $shipment->status }}">
                                        {{ $shipment->statusLabel() }}
                                    </span>
                                </td>
                                <td>{{ $shipment->created_at->diffForHumans() }}</td>
                                <td style="text-align: right;">
                                    <a href="{{ route('shipments.show', $shipment) }}" class="adm-btn adm-btn-outline adm-btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @push('scripts')
        <script>
            const trendCtx = document.getElementById('trendChart');
            if (trendCtx) {
                new Chart(trendCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($labels) !!},
                        datasets: [{
                            label: 'Shipments',
                            data: {!! json_encode($counts) !!},
                            borderColor: '#073B3A',
                            backgroundColor: 'rgba(7, 59, 58, 0.08)',
                            borderWidth: 2.5,
                            tension: 0.35,
                            fill: true,
                            pointBackgroundColor: '#CBCD30',
                            pointBorderColor: '#073B3A',
                            pointRadius: 5,
                            pointHoverRadius: 7,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#F0F1F1' } },
                            x: { grid: { display: false } }
                        }
                    }
                });
            }

            const statusCtx = document.getElementById('statusChart');
            if (statusCtx) {
                new Chart(statusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pending', 'In Transit', 'Delivered', 'Other'],
                        datasets: [{
                            data: {!! json_encode(array_values($statusBreakdown)) !!},
                            backgroundColor: ['#FCC608', '#128985', '#CBCD30', '#CED1D1'],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: true,
                        cutout: '65%',
                        plugins: {
                            legend: { position: 'bottom', labels: { padding: 16, font: { family: 'Inter', size: 13 } } }
                        }
                    }
                });
            }
        </script>
    @endpush

@endsection