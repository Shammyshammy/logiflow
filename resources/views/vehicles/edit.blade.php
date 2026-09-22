@extends('layouts.admin')

@section('title', 'Edit Vehicle')
@section('page_title', 'Edit Vehicle')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1 style="font-family: monospace;">{{ $vehicle->plate_number }}</h1>
            <p>Edit vehicle details</p>
        </div>
        <a href="{{ route('vehicles.show', $vehicle) }}" class="adm-btn adm-btn-outline">← Back</a>
    </div>

    <form method="POST" action="{{ route('vehicles.update', $vehicle) }}">
        @csrf
        @method('PUT')

        <div class="adm-grid adm-grid-2">

            <div class="adm-card">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">Vehicle Info</h2>

                <div class="adm-form-group">
                    <label class="adm-label">Plate Number *</label>
                    <input type="text" name="plate_number" class="adm-input" value="{{ old('plate_number', $vehicle->plate_number) }}" required>
                    @error('plate_number') <p class="adm-error">{{ $message }}</p> @enderror
                </div>

                <div class="adm-form-group" style="margin-bottom: 0;">
                    <label class="adm-label">Type *</label>
                    <select name="type" class="adm-select" required>
                        <option value="truck" @selected($vehicle->type === 'truck')>Truck</option>
                        <option value="van" @selected($vehicle->type === 'van')>Van</option>
                        <option value="bike" @selected($vehicle->type === 'bike')>Bike</option>
                        <option value="car" @selected($vehicle->type === 'car')>Car</option>
                        <option value="trailer" @selected($vehicle->type === 'trailer')>Trailer</option>
                    </select>
                </div>
            </div>

            <div class="adm-card">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">Capacity & Status</h2>

                <div class="adm-form-group">
                    <label class="adm-label">Capacity (kg)</label>
                    <input type="number" step="0.01" min="0" name="capacity_kg" class="adm-input" value="{{ old('capacity_kg', $vehicle->capacity_kg) }}">
                </div>

                <div class="adm-form-group" style="margin-bottom: 0;">
                    <label class="adm-label">Status *</label>
                    <select name="status" class="adm-select" required>
                        <option value="available" @selected($vehicle->status === 'available')>Available</option>
                        <option value="in_use" @selected($vehicle->status === 'in_use')>In Use</option>
                        <option value="maintenance" @selected($vehicle->status === 'maintenance')>Maintenance</option>
                    </select>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
            <a href="{{ route('vehicles.show', $vehicle) }}" class="adm-btn adm-btn-outline">Cancel</a>
            <button type="submit" class="adm-btn adm-btn-lime">Save Changes</button>
        </div>
    </form>

@endsection