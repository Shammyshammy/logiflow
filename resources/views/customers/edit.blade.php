@extends('layouts.admin')

@section('title', 'Edit Customer')
@section('page_title', 'Edit Customer')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1>{{ $customer->contact_name }}</h1>
            <p>Edit customer details</p>
        </div>
        <a href="{{ route('customers.show', $customer) }}" class="adm-btn adm-btn-outline">← Back</a>
    </div>

    <form method="POST" action="{{ route('customers.update', $customer) }}">
        @csrf
        @method('PUT')

        <div class="adm-grid adm-grid-2">

            <div class="adm-card">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">Contact Info</h2>

                <div class="adm-form-group">
                    <label class="adm-label">Contact Name *</label>
                    <input type="text" name="contact_name" class="adm-input" value="{{ old('contact_name', $customer->contact_name) }}" required>
                    @error('contact_name') <p class="adm-error">{{ $message }}</p> @enderror
                </div>

                <div class="adm-form-group">
                    <label class="adm-label">Company Name</label>
                    <input type="text" name="company_name" class="adm-input" value="{{ old('company_name', $customer->company_name) }}">
                </div>

                <div class="adm-form-group">
                    <label class="adm-label">Email</label>
                    <input type="email" name="email" class="adm-input" value="{{ old('email', $customer->email) }}">
                </div>

                <div class="adm-form-group" style="margin-bottom: 0;">
                    <label class="adm-label">Phone *</label>
                    <input type="text" name="phone" class="adm-input" value="{{ old('phone', $customer->phone) }}" required>
                </div>
            </div>

            <div class="adm-card">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">Address</h2>

                <div class="adm-form-group">
                    <label class="adm-label">Street Address</label>
                    <input type="text" name="address" class="adm-input" value="{{ old('address', $customer->address) }}">
                </div>

                <div class="adm-grid adm-grid-2" style="gap: 16px;">
                    <div class="adm-form-group">
                        <label class="adm-label">City</label>
                        <input type="text" name="city" class="adm-input" value="{{ old('city', $customer->city) }}">
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label">State</label>
                        <input type="text" name="state" class="adm-input" value="{{ old('state', $customer->state) }}">
                    </div>
                </div>

                <div class="adm-form-group" style="margin-bottom: 0;">
                    <label class="adm-label">Country</label>
                    <input type="text" name="country" class="adm-input" value="{{ old('country', $customer->country) }}">
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
            <a href="{{ route('customers.show', $customer) }}" class="adm-btn adm-btn-outline">Cancel</a>
            <button type="submit" class="adm-btn adm-btn-lime">Save Changes</button>
        </div>
    </form>

@endsection