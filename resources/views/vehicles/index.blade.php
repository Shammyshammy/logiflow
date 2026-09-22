@extends('layouts.admin')

@section('title', 'Vehicles')
@section('page_title', 'Vehicles')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>Vehicles</h1>
            <p>Manage your vehicle fleet</p>
        </div>
        <a href="{{ route('vehicles.create') }}" class="adm-btn adm-btn-lime">+ New Vehicle</a>
    </div>

    <div class="adm-card" style="padding: 0; overflow: hidden;">
        @if($vehicles->isEmpty())
            <div style="padding: 60px 24px; text-align: center;">
                <p style="font-size: 18px; font-weight: 600; margin: 0 0 8px;">No vehicles yet</p>
                <p style="color: #6B7273; margin: 0 0 24px;">Add your first vehicle to get started.</p>
                <a href="{{ route('vehicles.create') }}" class="adm-btn adm-btn-lime">+ New Vehicle</a>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Plate</th>
                            <th>Type</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th>Assignments</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vehicles as $vehicle)
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
                            <tr>
                                <td>
                                    <a href="{{ route('vehicles.show', $vehicle) }}" style="font-family: monospace;">
                                        {{ $vehicle->plate_number }}
                                    </a>
                                </td>
                                <td style="text-transform: capitalize;">{{ $vehicle->type }}</td>
                                <td>{{ $vehicle->capacity_kg ? number_format($vehicle->capacity_kg) . ' kg' : '—' }}</td>
                                <td>
                                    <span class="adm-badge {{ $statusColors[$vehicle->status] ?? '' }}">
                                        {{ $statusLabels[$vehicle->status] ?? $vehicle->status }}
                                    </span>
                                </td>
                                <td>{{ $vehicle->assignments->count() }}</td>
                                <td style="text-align: right;">
                                    <a href="{{ route('vehicles.show', $vehicle) }}" class="adm-btn adm-btn-outline adm-btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 20px 24px; border-top: 1px solid #F0F1F1;">
                {{ $vehicles->links() }}
            </div>
        @endif
    </div>

@endsection