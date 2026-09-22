@extends('layouts.admin')

@section('title', 'New Vehicle')
@section('page_title', 'New Vehicle')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>Add Vehicle</h1>
            <p>Register a new vehicle in your fleet</p>
        </div>
        <a href="{{ route('vehicles.index') }}" class="adm-btn adm-btn-outline">← Back</a>
    </div>

    <form method="POST" action="{{ route('vehicles.store') }}">
        @csrf

        <div class="adm-grid adm-grid-2">

            <div class="adm-card">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">Vehicle Info</h2>

                <div class="adm-form-group">
                    <label class="adm-label">Plate Number *</label>
                    <input type="text" name="plate_number" class="adm-input" value="{{ old('plate_number') }}" required placeholder="e.g. RIV-234-AB">
                    @error('plate_number') <p class="adm-error">{{ $message }}</p> @enderror
                </div>

                <div class="adm-form-group" style="margin-bottom: 0;">
                    <label class="adm-label">Type *</label>
                    <select name="type" class="adm-select" required>
                        <option value="">Select a type...</option>
                        <option value="truck" @selected(old('type') === 'truck')>Truck</option>
                        <option value="van" @selected(old('type') === 'van')>Van</option>
                        <option value="bike" @selected(old('type') === 'bike')>Bike</option>
                        <option value="car" @selected(old('type') === 'car')>Car</option>
                        <option value="trailer" @selected(old('type') === 'trailer')>Trailer</option>
                    </select>
                    @error('type') <p class="adm-error">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="adm-card">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">Capacity & Status</h2>

                <div class="adm-form-group">
                    <label class="adm-label">Capacity (kg)</label>
                    <input type="number" step="0.01" min="0" name="capacity_kg" class="adm-input" value="{{ old('capacity_kg') }}" placeholder="e.g. 5000">
                    @error('capacity_kg') <p class="adm-error">{{ $message }}</p> @enderror
                </div>

                <div class="adm-form-group" style="margin-bottom: 0;">
                    <label class="adm-label">Status *</label>
                    <select name="status" class="adm-select" required>
                        <option value="available" @selected(old('status') === 'available')>Available</option>
                        <option value="in_use" @selected(old('status') === 'in_use')>In Use</option>
                        <option value="maintenance" @selected(old('status') === 'maintenance')>Maintenance</option>
                    </select>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
            <a href="{{ route('vehicles.index') }}" class="adm-btn adm-btn-outline">Cancel</a>
            <button type="submit" class="adm-btn adm-btn-lime">Create Vehicle</button>
        </div>
    </form>

@endsection