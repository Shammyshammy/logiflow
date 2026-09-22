@extends('layouts.public')

@section('title', 'Track Your Shipment — LogiFlow')

@section('content')

    {{-- Hero --}}
    <section class="lf-hero">
        <div class="lf-container">
            <div class="lf-hero-inner">
                <h1 class="lf-animate lf-delay-1">
                    Track every shipment,<br>
                    from pickup to <span class="accent">delivery</span>
                </h1>
                <p class="lf-animate lf-delay-2">
                    Enter your tracking number below to see where your package is right now.
                </p>

                <form action="{{ route('tracking.search') }}" method="POST" class="lf-track-form lf-animate lf-delay-3">
                    @csrf
                    <input
                        type="text"
                        name="tracking_number"
                        placeholder="e.g. LGF-2026-ABC123"
                        value="{{ old('tracking_number') }}"
                        required
                        autofocus
                    >
                    <button type="submit" class="lf-btn lf-btn-primary">
                        Track Now
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                </form>

                @error('tracking_number')
                    <p style="color: #FF6B00; margin-top: 16px;">{{ $message }}</p>
                @enderror
            </div>
        </div>
    </section>

    {{-- Features --}}
    <section class="lf-section" id="features">
        <div class="lf-container">
            <div class="lf-section-title">
                <span class="eyebrow">Why LogiFlow</span>
                <h2>Everything you need to move things</h2>
                <p>From a single package to a fleet of vehicles, LogiFlow handles the complexity so you don't have to.</p>
            </div>

            <div class="lf-grid lf-grid-3">
                <div class="lf-card reveal-up">
    <div class="lf-card-icon" style="transition-delay: 0.1s;">🚚</div>
    <h3>Real-Time Tracking</h3>
    <p>Every status change is captured with location and timestamp, visible to your customers instantly.</p>
</div>
<div class="lf-card reveal-up" style="transition-delay: 0.1s;">
    <div class="lf-card-icon">👥</div>
    <h3>Driver Management</h3>
    <p>Assign drivers and vehicles to shipments, track availability, and keep operations moving.</p>
</div>
<div class="lf-card reveal-up" style="transition-delay: 0.2s;">
    <div class="lf-card-icon">📊</div>
    <h3>Operations Dashboard</h3>
    <p>See all your shipments at a glance — pending, in transit, delivered, and everything in between.</p>
</div>
            </div>
        </div>
    </section>

    {{-- About --}}
    <section class="lf-section lf-section-sm" id="about" style="background: var(--lf-gray-100);">
        <div class="lf-container lf-text-center">
            <div class="lf-section-title" style="margin-bottom: 0;">
                <span class="eyebrow">About LogiFlow</span>
                <h2>Built for the way logistics actually works</h2>
                <p>Whether you run a courier business, a haulage company, or an in-house delivery team, LogiFlow adapts to your workflow.</p>
            </div>
        </div>
    </section>

@endsection