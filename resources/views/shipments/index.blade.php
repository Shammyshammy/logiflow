@extends('layouts.admin')

@section('title', 'Shipments')
@section('page_title', 'Shipments')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>Shipments</h1>
            <p>Manage all shipments across your operations</p>
        </div>
        <div style="display: flex; gap: 8px;">
    <a href="{{ route('shipments.export.csv', request()->query()) }}" class="adm-btn adm-btn-outline">
        ↓ Export CSV
    </a>
    <a href="{{ route('shipments.export.all.pdf', request()->query()) }}" class="adm-btn adm-btn-outline">↓ Export PDF</a>
    <a href="{{ route('shipments.create') }}" class="adm-btn adm-btn-lime">
        + New Shipment
    </a>
</div>
    </div>

    {{-- Filters --}}
    <div class="adm-card" style="margin-bottom: 20px;">
        <form method="GET" style="display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 200px;">
                <label class="adm-label">Search</label>
                <input type="text" name="search" class="adm-input" placeholder="Tracking number, customer..." value="{{ request('search') }}">
            </div>
            <div style="min-width: 180px;">
                <label class="adm-label">Status</label>
                <select name="status" class="adm-select">
                    <option value="">All statuses</option>
                    @foreach(\App\Models\Shipment::statuses() as $key => $label)
                        <option value="{{ $key }}" @selected(request('status') === $key)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: flex; gap: 8px;">
                <button type="submit" class="adm-btn adm-btn-primary">Filter</button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('shipments.index') }}" class="adm-btn adm-btn-outline">Clear</a>
                @endif
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="adm-card" style="padding: 0; overflow: hidden;">
        @if($shipments->isEmpty())
            <div style="padding: 60px 24px; text-align: center;">
                <p style="font-size: 18px; font-weight: 600; margin: 0 0 8px;">No shipments found</p>
                <p style="color: #6B7273; margin: 0 0 24px;">
                    {{ request()->hasAny(['search', 'status']) ? 'Try adjusting your filters.' : 'Create your first shipment to get started.' }}
                </p>
                <a href="{{ route('shipments.create') }}" class="adm-btn adm-btn-lime">+ Create Shipment</a>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Tracking</th>
                            <th>Customer</th>
                            <th>Route</th>
                            <th>Weight</th>
                            <th>Status</th>
                            <th>Expected</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipments as $shipment)
                            <tr>
                                <td>
                                    <a href="{{ route('shipments.show', $shipment) }}">
                                        {{ $shipment->tracking_number }}
                                    </a>
                                    <div style="font-size: 12px; color: #6B7273; margin-top: 2px;">
                                        {{ $shipment->created_at->format('M d, Y') }}
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 500;">{{ $shipment->customer->contact_name ?? '—' }}</div>
                                    @if($shipment->customer?->company_name)
                                        <div style="font-size: 12px; color: #6B7273;">{{ $shipment->customer->company_name }}</div>
                                    @endif
                                </td>
                                <td style="font-size: 13px;">
                                    {{ $shipment->origin_city ?? 'Origin' }}
                                    <span style="color: #CED1D1;">→</span>
                                    {{ $shipment->destination_city ?? 'Destination' }}
                                </td>
                                <td>{{ $shipment->weight_kg ? $shipment->weight_kg . ' kg' : '—' }}</td>
                                <td>
                                    <span class="adm-badge adm-badge-{{ $shipment->status }}">
                                        {{ $shipment->statusLabel() }}
                                    </span>
                                </td>
                                <td style="font-size: 13px;">
    {{ $shipment->expected_delivery?->format('M d, Y') ?? '—' }}
    @if($shipment->expected_delivery && !$shipment->isDelivered())
        <div style="font-size: 11px; margin-top: 4px; color: {{ $shipment->isOverdue() ? '#991B1B' : '#6B7273' }}; font-weight: 600;">
            {{ $shipment->etaLabel() }}
        </div>
    @endif
</td>
                                <td style="text-align: right;">
                                    <a href="{{ route('shipments.show', $shipment) }}" class="adm-btn adm-btn-outline adm-btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 20px 24px; border-top: 1px solid #F0F1F1;">
                {{ $shipments->links() }}
            </div>
        @endif
    </div>

@endsection