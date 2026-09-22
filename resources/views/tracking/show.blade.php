@extends('layouts.public')

@section('title', 'Tracking ' . $shipment->tracking_number . ' — LogiFlow')

@section('content')

    <section class="lf-section" style="padding-top: 140px;">
        <div class="lf-container">

            {{-- Shipment Header --}}
            <div style="background: var(--lf-teal-800); color: white; padding: 40px; border-radius: var(--lf-radius-lg); margin-bottom: 32px;">
                <p style="font-size: 14px; opacity: 0.7; margin: 0 0 8px; text-transform: uppercase; letter-spacing: 1px;">Tracking Number</p>
                <h1 style="font-size: 40px; font-weight: 800; margin: 0 0 24px; letter-spacing: -1px;">{{ $shipment->tracking_number }}</h1>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 24px;">
                    <div>
                        <p style="font-size: 12px; opacity: 0.6; margin: 0 0 4px; text-transform: uppercase;">Status</p>
                        <p style="font-size: 18px; font-weight: 600; margin: 0;">{{ $shipment->statusLabel() }}</p>
                    </div>
                    <div>
                        <p style="font-size: 12px; opacity: 0.6; margin: 0 0 4px; text-transform: uppercase;">From</p>
                        <p style="font-size: 16px; font-weight: 500; margin: 0;">{{ $shipment->origin_city ?? $shipment->origin_address }}</p>
                    </div>
                    <div>
                        <p style="font-size: 12px; opacity: 0.6; margin: 0 0 4px; text-transform: uppercase;">To</p>
                        <p style="font-size: 16px; font-weight: 500; margin: 0;">{{ $shipment->destination_city ?? $shipment->destination_address }}</p>
                    </div>
                    <div>
                        <p style="font-size: 12px; opacity: 0.6; margin: 0 0 4px; text-transform: uppercase;">Expected</p>
                        <p style="font-size: 16px; font-weight: 500; margin: 0;">
                            {{ $shipment->expected_delivery?->format('M d, Y') ?? 'TBD' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Timeline --}}
            <div style="background: white; padding: 40px; border-radius: var(--lf-radius-lg); border: 1px solid var(--lf-gray-200);">
                <h2 style="font-size: 24px; font-weight: 700; margin: 0 0 32px;">Tracking History</h2>

                @if($shipment->events->isEmpty())
                    <p style="color: var(--lf-ink-500);">No events recorded yet.</p>
                @else
                    @php
                        $statuses = array_keys(\App\Models\Shipment::statuses());
                        $currentIndex = array_search($shipment->status, $statuses);
                    @endphp

                    <div class="lf-timeline">
                        @foreach($shipment->events as $event)
                            @php
                                $eventIndex = array_search($event->status, $statuses);
                                $isDone = $eventIndex !== false && $currentIndex !== false && $eventIndex < $currentIndex;
                                $isActive = $event->status === $shipment->status;
                            @endphp
                            <div class="lf-timeline-item {{ $isDone ? 'done' : '' }} {{ $isActive ? 'active' : '' }}">
                                <div class="lf-timeline-dot">
                                    @if($isDone) ✓
                                    @elseif($isActive) ●
                                    @else ○
                                    @endif
                                </div>
                                <div class="lf-timeline-content">
                                    <h4>{{ $event->statusLabel() }}</h4>
                                    @if($event->location)
                                        <p>📍 {{ $event->location }}</p>
                                    @endif
                                    @if($event->note)
                                        <p>{{ $event->note }}</p>
                                    @endif
                                    <time>{{ $event->created_at->format('M d, Y — H:i') }}</time>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Back --}}
            <div class="lf-text-center lf-mt-24">
                <a href="{{ route('tracking.index') }}" class="lf-btn lf-btn-outline">← Track another shipment</a>
            </div>

        </div>
    </section>

@endsection