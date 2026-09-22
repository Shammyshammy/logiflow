@extends('layouts.admin')

@section('title', $customer->contact_name)
@section('page_title', 'Customer Details')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>{{ $customer->contact_name }}</h1>
            <p>
                {{ $customer->company_name ?? 'Individual customer' }}
                · Added {{ $customer->created_at->format('M d, Y') }}
            </p>
        </div>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('customers.index') }}" class="adm-btn adm-btn-outline">← Back</a>
            <a href="{{ route('customers.edit', $customer) }}" class="adm-btn adm-btn-outline">Edit</a>
            <form method="POST" action="{{ route('customers.destroy', $customer) }}" onsubmit="return confirm('Delete this customer?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="adm-btn adm-btn-danger">Delete</button>
            </form>
        </div>
    </div>

    <div class="adm-grid" style="grid-template-columns: 1fr 2fr; gap: 20px;">

        <div>
            <div class="adm-card" style="margin-bottom: 20px;">
                <h3 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 12px;">Contact</h3>
                <div style="display: grid; gap: 10px; font-size: 14px;">
                    @if($customer->email)
                        <p style="margin: 0;">✉️ {{ $customer->email }}</p>
                    @endif
                    <p style="margin: 0;">📞 {{ $customer->phone }}</p>
                </div>
            </div>

            <div class="adm-card">
                <h3 style="font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #6B7273; margin: 0 0 12px;">Location</h3>
                <div style="display: grid; gap: 6px; font-size: 14px;">
                    @if($customer->address) <p style="margin: 0;">{{ $customer->address }}</p> @endif
                    <p style="margin: 0;">
                        {{ collect([$customer->city, $customer->state, $customer->country])->filter()->join(', ') ?: '—' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="adm-card" style="padding: 0; overflow: hidden;">
            <div style="padding: 20px 24px; border-bottom: 1px solid #F0F1F1; display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 16px; font-weight: 700; margin: 0;">Shipments ({{ $customer->shipments->count() }})</h3>
                <a href="{{ route('shipments.create') }}" class="adm-btn adm-btn-lime adm-btn-sm">+ New</a>
            </div>

            @if($customer->shipments->isEmpty())
                <p style="padding: 40px 24px; text-align: center; color: #6B7273;">No shipments for this customer yet.</p>
            @else
                <div style="overflow-x: auto;">
                    <table class="adm-table">
                        <thead>
                            <tr>
                                <th>Tracking</th>
                                <th>Destination</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->shipments as $shipment)
                                <tr>
                                    <td><a href="{{ route('shipments.show', $shipment) }}">{{ $shipment->tracking_number }}</a></td>
                                    <td style="font-size: 13px;">{{ $shipment->destination_city ?? $shipment->destination_address }}</td>
                                    <td>
                                        <span class="adm-badge adm-badge-{{ $shipment->status }}">{{ $shipment->statusLabel() }}</span>
                                    </td>
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
    </div>

@endsection