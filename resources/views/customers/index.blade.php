@extends('layouts.admin')

@section('title', 'Customers')
@section('page_title', 'Customers')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>Customers</h1>
            <p>All clients and their shipment history</p>
        </div>
        <a href="{{ route('customers.create') }}" class="adm-btn adm-btn-lime">+ New Customer</a>
    </div>

    <div class="adm-card" style="padding: 0; overflow: hidden;">
        @if($customers->isEmpty())
            <div style="padding: 60px 24px; text-align: center;">
                <p style="font-size: 18px; font-weight: 600; margin: 0 0 8px;">No customers yet</p>
                <p style="color: #6B7273; margin: 0 0 24px;">Add your first customer to get started.</p>
                <a href="{{ route('customers.create') }}" class="adm-btn adm-btn-lime">+ New Customer</a>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table class="adm-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Company</th>
                            <th>Contact</th>
                            <th>Location</th>
                            <th>Shipments</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                            <tr>
                                <td><a href="{{ route('customers.show', $customer) }}">{{ $customer->contact_name }}</a></td>
                                <td>{{ $customer->company_name ?? '—' }}</td>
                                <td>
                                    <div>{{ $customer->phone }}</div>
                                    @if($customer->email)
                                        <div style="font-size: 12px; color: #6B7273;">{{ $customer->email }}</div>
                                    @endif
                                </td>
                                <td style="font-size: 13px;">
                                    {{ $customer->city ?? '—' }}
                                    @if($customer->state) <span style="color:#6B7273;">, {{ $customer->state }}</span> @endif
                                </td>
                                <td><strong>{{ $customer->shipments_count }}</strong></td>
                                <td style="text-align: right;">
                                    <a href="{{ route('customers.show', $customer) }}" class="adm-btn adm-btn-outline adm-btn-sm">View</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 20px 24px; border-top: 1px solid #F0F1F1;">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

@endsection