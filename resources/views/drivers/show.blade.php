@extends('layouts.admin')

@section('title', $driver->user->name ?? 'Driver')
@section('page_title', 'Driver Details')

@section('content')

    @php
        $statusColors = [
            'available' => 'adm-badge-delivered',
            'on_delivery' => 'adm-badge-in_transit',
            'off_duty' => 'adm-badge-cancelled',
        ];
        $statusLabels = [
            'available' => 'Available',
            'on_delivery' => 'On Delivery',
            'off_duty' => 'Off Duty',
        ];
    @endphp

    <div class="adm-page-header">
        <div>
            <h1>{{ $driver->user->name ?? '—' }}</h1>
            <p>
                License <span style="font-family: monospace;">{{ $driver->license_number }}</span>
                · Joined {{ $driver->created_at->format('M d, Y') }}
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('drivers.index') }}" class="adm-btn adm-btn-outline">← Back</a>
            <a href="{{ route('drivers.edit', $driver) }}" class="adm-btn adm-btn-outline">Edit</a>
        </div>
    </div>

    <div class="adm-grid" style="grid-template-columns: 1fr 2fr; gap: 20px;">

        <div>
            <div class="adm-card" style="margin-bottom: 20px;">
                <h3 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 12px;">Status</h3>
                <span class="adm-badge {{ $statusColors[$driver->status] ?? '' }}" style="font-size: 13px; padding: 8px 16px;">
                    {{ $statusLabels[$driver->status] ?? $driver->status }}
                </span>
            </div>

            <div class="adm-card">
                <h3 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 12px;">Contact</h3>
                <div style="display: grid; gap: 10px; font-size: 14px;">
                    @if($driver->user?->email)
                        <p style="margin: 0;">✉️ {{ $driver->user->email }}</p>
                    @endif
                    @if($driver->user?->phone)
                        <p style="margin: 0;">📞 {{ $driver->user->phone }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="adm-card" style="padding: 0; overflow: hidden;">
            <div style="padding: 20px 24px; border-bottom: 1px solid #F0F1F1;">
                <h3 style="font-size: 16px; font-weight: 700; margin: 0;">Assignments ({{ $driver->assignments->count() }})</h3>
            </div>

            @if($driver->assignments->isEmpty())
                <p style="padding: 40px 24px; text-align: center; color: #6B7273;">No assignments yet.</p>
            @else
                <div style="overflow-x: auto;">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Tracking</th>
                                <th>Destination</th>
                                <th>Assigned</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($driver->assignments as $assignment)
                                @if($assignment->shipment)
                                    <tr>
                                        <td><a href="{{ route('shipments.show', $assignment->shipment) }}">{{ $assignment->shipment->tracking_number }}</a></td>
                                        <td style="font-size: 13px;">{{ $assignment->shipment->destination_city ?? $assignment->shipment->destination_address }}</td>
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