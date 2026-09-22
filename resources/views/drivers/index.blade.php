@extends('layouts.admin')

@section('title', 'Drivers')
@section('page_title', 'Drivers')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>Drivers</h1>
            <p>Manage your driver fleet</p>
        </div>
        <a href="{{ route('drivers.create') }}" class="adm-btn adm-btn-lime">+ New Driver</a>
    </div>

    <div class="adm-card" style="padding: 0; overflow: hidden;">
        @if($drivers->isEmpty())
            <div style="padding: 60px 24px; text-align: center;">
                <p style="font-size: 18px; font-weight: 600; margin: 0 0 8px;">No drivers yet</p>
                <p style="color: #6B7273; margin: 0 0 24px;">Add your first driver to get started.</p>
                <a href="{{ route('drivers.create') }}" class="adm-btn adm-btn-lime">+ New Driver</a>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>License</th>
                            <th>Contact</th>
                            <th>Status</th>
                            <th>Assignments</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($drivers as $driver)
                            <tr>
                                <td><a href="{{ route('drivers.show', $driver) }}">{{ $driver->user->name ?? '—' }}</a></td>
                                <td style="font-family: monospace; font-size: 13px;">{{ $driver->license_number }}</td>
                                <td>
                                    <div>{{ $driver->user->phone ?? '—' }}</div>
                                    @if($driver->user?->email)
                                        <div style="font-size: 12px; color: #6B7273;">{{ $driver->user->email }}</div>
                                    @endif
                                </td>
                                <td>
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
                                    <span class="adm-badge {{ $statusColors[$driver->status] ?? '' }}">
                                        {{ $statusLabels[$driver->status] ?? $driver->status }}
                                    </span>
                                </td>
                                <td>{{ $driver->assignments->count() }}</td>
                                <td style="text-align: right;">
                                    <a href="{{ route('drivers.show', $driver) }}" class="adm-btn adm-btn-outline adm-btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 20px 24px; border-top: 1px solid #F0F1F1;">
                {{ $drivers->links() }}
            </div>
        @endif
    </div>

@endsection