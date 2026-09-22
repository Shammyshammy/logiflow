@extends('layouts.admin')

@section('title', $vehicle->plate_number)
@section('page_title', 'Vehicle Details')

@section('content')

    @php
        $statusColors = [
            'available' => 'adm-badge-delivered',
            'in_use' => 'adm-badge-in_transit',
            'maintenance' => 'adm-badge-failed',
        ];
        $statusLabels = [
            'available' => 'Available',
            'in_use' => 'In Use',
            'maintenance' => 'Maintenance',
        ];
    @endphp

    <div class="adm-page-header">
        <div>
            <h1 style="font-family: monospace;">{{ $vehicle->plate_number }}</h1>
            <p>{{ ucfirst($vehicle->type) }} · Added {{ $vehicle->created_at->format('M d, Y') }}</p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('vehicles.index') }}" class="adm-btn adm-btn-outline">← Back</a>
            <a href="{{ route('vehicles.edit', $vehicle) }}" class="adm-btn adm-btn-outline">Edit</a>
        </div>
    </div>

    <div class="adm-grid" style="grid-template-columns: 1fr 2fr; gap: 20px;">

        <div>
            <div class="adm-card" style="margin-bottom: 20px;">
                <h3 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 12px;">Status</h3>
                <span class="adm-badge {{ $statusColors[$vehicle->status] ?? '' }}" style="font-size: 13px; padding: 8px 16px;">
                    {{ $statusLabels[$vehicle->status] ?? $vehicle->status }}
                </span>
            </div>

            <div class="adm-card">
                <h3 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 12px;">Details</h3>
                <div style="display: grid; gap: 10px; font-size: 14px;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #6B7273;">Type</span>
                        <span style="font-weight: 500; text-transform: capitalize;">{{ $vehicle->type }}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #6B7273;">Capacity</span>
                        <span style="font-weight: 500;">{{ $vehicle->capacity_kg ? number_format($vehicle->capacity_kg) . ' kg' : '—' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="adm-card" style="padding: 0; overflow: hidden;">
            <div style="padding: 20px 24px; border-bottom: 1px solid #F0F1F1;">
                <h3 style="font-size: 16px; font-weight: 700; margin: 0;">Assignments ({{ $vehicle->assignments->count() }})</h3>
            </div>

            @if($vehicle->assignments->isEmpty())
                <p style="padding: 40px 24px; text-align: center; color: #6B7273;">No assignments yet.</p>
            @else
                <div style="overflow-x: auto;">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Tracking</th>
                                <th>Driver</th>
                                <th>Assigned</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vehicle->assignments as $assignment)
                                @if($assignment->shipment)
                                    <tr>
                                        <td><a href="{{ route('shipments.show', $assignment->shipment) }}">{{ $assignment->shipment->tracking_number }}</a></td>
                                        <td>{{ $assignment->driver->user->name ?? '—' }}</td>
                                        <td style="font-size: 13px;">{{ $assignment->assigned_at->format('M d, Y') }}</td>
                                        <td>
                                            @if($assignment->isActive())
                                                <span class="adm-badge adm-badge-in_transit">Active</span>
                                            @else
                                                <span class="adm-badge adm-badge-cancelled">Closed</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <a href="{{ route('shipments.show', $assignment->shipment) }}" class="adm-btn adm-btn-outline adm-btn-sm">View</a>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

@endsection