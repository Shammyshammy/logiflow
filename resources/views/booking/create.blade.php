@extends('layouts.public')

@section('title', 'Book a Shipment — LogiFlow')

@section('content')

    <section class="lf-section" style="padding-top: 140px;">
        <div class="lf-container">

            <div class="lf-section-title" style="margin-bottom: 40px;">
                <span class="eyebrow">Book a shipment</span>
                <h2>Send something today</h2>
                <p>Fill in the details below. We'll get back to you with a tracking number and pickup time.</p>
            </div>

            <form method="POST" action="{{ route('booking.store') }}" style="max-width: 900px; margin: 0 auto;">
                @csrf

                <div class="adm-grid adm-grid-2" style="gap: 20px;">

                    <div class="adm-card">
                        <h3 style="font-size: 15px; font-weight: 700; margin: 0 0 20px;">Your Details</h3>

                        <div class="adm-form-group">
                            <label class="adm-label">Full Name *</label>
                            <input type="text" name="contact_name" class="adm-input" value="{{ old('contact_name') }}" required>
                            @error('contact_name') <p class="adm-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="adm-form-group">
                            <label class="adm-label">Email *</label>
                            <input type="email" name="email" class="adm-input" value="{{ old('email') }}" required>
                            @error('email') <p class="adm-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="adm-form-group">
                            <label class="adm-label">Phone *</label>
                            <input type="text" name="phone" class="adm-input" value="{{ old('phone') }}" required>
                            @error('phone') <p class="adm-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="adm-form-group" style="margin-bottom: 0;">
                            <label class="adm-label">Company (optional)</label>
                            <input type="text" name="company_name" class="adm-input" value="{{ old('company_name') }}">
                        </div>
                    </div>

                    <div class="adm-card">
                        <h3 style="font-size: 15px; font-weight: 700; margin: 0 0 20px;">Route</h3>

                        <div class="adm-form-group">
                            <label class="adm-label">Origin Address *</label>
                            <input type="text" name="origin_address" class="adm-input" value="{{ old('origin_address') }}" required placeholder="Street, building, landmark">
                            @error('origin_address') <p class="adm-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="adm-form-group">
                            <label class="adm-label">Origin City *</label>
                            <input type="text" name="origin_city" class="adm-input" value="{{ old('origin_city') }}" required>
                            @error('origin_city') <p class="adm-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="adm-form-group">
                            <label class="adm-label">Destination Address *</label>
                            <input type="text" name="destination_address" class="adm-input" value="{{ old('destination_address') }}" required>
                            @error('destination_address') <p class="adm-error">{{ $message }}</p> @enderror
                        </div>
                        <div class="adm-form-group" style="margin-bottom: 0;">
                            <label class="adm-label">Destination City *</label>
                            <input type="text" name="destination_city" class="adm-input" value="{{ old('destination_city') }}" required>
                            @error('destination_city') <p class="adm-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="adm-card">
                        <h3 style="font-size: 15px; font-weight: 700; margin: 0 0 20px;">Receiver</h3>

                        <div class="adm-form-group">
                            <label class="adm-label">Receiver Name *</label>
                            <input type="text" name="receiver_name" class="adm-input" value="{{ old('receiver_name') }}" required>
                            @error('receiver_name') <p class="adm-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="adm-form-group" style="margin-bottom: 0;">
                            <label class="adm-label">Receiver Phone *</label>
                            <input type="text" name="receiver_phone" class="adm-input" value="{{ old('receiver_phone') }}" required>
                            @error('receiver_phone') <p class="adm-error">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="adm-card">
                        <h3 style="font-size: 15px; font-weight: 700; margin: 0 0 20px;">Package</h3>

                        <div class="adm-form-group">
                            <label class="adm-label">Description *</label>
                            <textarea name="description" class="adm-textarea" required placeholder="What are you shipping?">{{ old('description') }}</textarea>
                            @error('description') <p class="adm-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="adm-form-group" style="margin-bottom: 0;">
                            <label class="adm-label">Weight (kg)</label>
                            <input type="number" step="0.01" min="0" name="weight_kg" class="adm-input" value="{{ old('weight_kg') }}" placeholder="Optional">
                            @error('weight_kg') <p class="adm-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div style="text-align: center; margin-top: 32px;">
                    <button type="submit" class="lf-btn lf-btn-primary" style="padding: 16px 40px; font-size: 16px;">
                        Submit Booking →
                    </button>
                </div>
            </form>
        </div>
    </section>

@endsection