@extends('layouts.public')

@section('title', 'Booking Confirmed — LogiFlow')

@section('content')

    <section class="lf-section" style="padding-top: 160px; padding-bottom: 100px;">
        <div class="lf-container" style="max-width: 640px; text-align: center;">

            <div style="width: 80px; height: 80px; border-radius: 50%; background: #CBCD30; color: #073B3A; display: inline-flex; align-items: center; justify-content: center; font-size: 40px; margin-bottom: 24px;">
                ✓
            </div>

            <h1 style="font-size: 40px; font-weight: 800; letter-spacing: -1px; margin: 0 0 12px;">Booking received!</h1>
            <p style="font-size: 17px; color: #6B7273; margin: 0 0 40px;">
                We've created your shipment. Save your tracking number to stay updated.
            </p>

            <div style="background: #073B3A; color: white; padding: 32px; border-radius: 16px; margin-bottom: 32px;">
                <p style="font-size: 13px; text-transform: uppercase; letter-spacing: 1px; opacity: 0.6; margin: 0 0 8px;">Tracking Number</p>
                <p style="font-size: 32px; font-weight: 800; font-family: monospace; letter-spacing: -1px; margin: 0;">{{ $shipment->tracking_number }}</p>
            </div>

            <p style="font-size: 15px; color: #6B7273; margin-bottom: 32px;">
                We'll notify you at <strong>{{ $shipment->customer->email ?? '—' }}</strong> when your shipment is picked up.
            </p>

            <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('tracking.show', $shipment->tracking_number) }}" class="lf-btn lf-btn-primary">Track Shipment</a>
                <a href="{{ route('home') }}" class="lf-btn lf-btn-outline">Back Home</a>
            </div>
        </div>
    </section>

@endsection