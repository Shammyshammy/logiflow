@extends('layouts.admin')

@section('title', 'New Driver')
@section('page_title', 'New Driver')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>Add Driver</h1>
            <p>Register a new driver with login credentials</p>
        </div>
        <a href="{{ route('drivers.index') }}" class="adm-btn adm-btn-outline">← Back</a>
    </div>

    <form method="POST" action="{{ route('drivers.store') }}">
        @csrf

        <div class="adm-grid adm-grid-2">

            <div class="adm-card">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">Personal Info</h2>

                <div class="adm-form-group">
                    <label class="adm-label">Full Name *</label>
                    <input type="text" name="name" class="adm-input" value="{{ old('name') }}" required>
                    @error('name') <p class="adm-error">{{ $message }}</p> @enderror
                </div>

                <div class="adm-form-group">
                    <label class="adm-label">Email *</label>
                    <input type="email" name="email" class="adm-input" value="{{ old('email') }}" required>
                    @error('email') <p class="adm-error">{{ $message }}</p> @enderror
                </div>

                <div class="adm-form-group" style="margin-bottom: 0;">
                    <label class="adm-label">Phone</label>
                    <input type="text" name="phone" class="adm-input" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="adm-card">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">Login & License</h2>

                <div class="adm-form-group">
                    <label class="adm-label">Password *</label>
                    <input type="password" name="password" class="adm-input" required minlength="6">
                    @error('password') <p class="adm-error">{{ $message }}</p> @enderror
                </div>

                <div class="adm-form-group" style="margin-bottom: 0;">
                    <label class="adm-label">License Number *</label>
                    <input type="text" name="license_number" class="adm-input" value="{{ old('license_number') }}" required placeholder="e.g. DRV-2026-0002">
                    @error('license_number') <p class="adm-error">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
            <a href="{{ route('drivers.index') }}" class="adm-btn adm-btn-outline">Cancel</a>
            <button type="submit" class="adm-btn adm-btn-lime">Create Driver</button>
        </div>
    </form>

@endsection