@extends('layouts.admin')

@section('title', 'Edit Driver')
@section('page_title', 'Edit Driver')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>{{ $driver->user->name ?? '—' }}</h1>
            <p>Edit driver details</p>
        </div>
        <a href="{{ route('drivers.show', $driver) }}" class="adm-btn adm-btn-outline">← Back</a>
    </div>

    <form method="POST" action="{{ route('drivers.update', $driver) }}">
        @csrf
        @method('PUT')

        <div class="adm-grid adm-grid-2">

            <div class="adm-card">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">Personal Info</h2>

                <div class="adm-form-group">
                    <label class="adm-label">Full Name *</label>
                    <input type="text" name="name" class="adm-input" value="{{ old('name', $driver->user->name) }}" required>
                    @error('name') <p class="adm-error">{{ $message }}</p> @enderror
                </div>

                <div class="adm-form-group" style="margin-bottom: 0;">
                    <label class="adm-label">Phone</label>
                    <input type="text" name="phone" class="adm-input" value="{{ old('phone', $driver->user->phone) }}">
                </div>

                <div class="adm-form-group" style="margin-top: 20px;">
                    <label class="adm-label">Email (read-only)</label>
                    <input type="email" class="adm-input" value="{{ $driver->user->email }}" disabled style="background: #F5F6F7; color: #6B7273;">
                </div>
            </div>

            <div class="adm-card">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">License & Status</h2>

                <div class="adm-form-group">
                    <label class="adm-label">License Number *</label>
                    <input type="text" name="license_number" class="adm-input" value="{{ old('license_number', $driver->license_number) }}" required>
                    @error('license_number') <p class="adm-error">{{ $message }}</p> @enderror
                </div>

                <div class="adm-form-group" style="margin-bottom: 0;">
                    <label class="adm-label">Status *</label>
                    <select name="status" class="adm-select" required>
                        <option value="available" @selected($driver->status === 'available')>Available</option>
                        <option value="on_delivery" @selected($driver->status === 'on_delivery')>On Delivery</option>
                        <option value="off_duty" @selected($driver->status === 'off_duty')>Off Duty</option>
                    </select>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
            <a href="{{ route('drivers.show', $driver) }}" class="adm-btn adm-btn-outline">Cancel</a>
            <button type="submit" class="adm-btn adm-btn-lime">Save Changes</button>
        </div>
    </form>

@endsection