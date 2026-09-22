@extends('layouts.admin')

@section('title', 'Edit Shipment')
@section('page_title', 'Edit Shipment')

@section('content')

    <div class="adm-page-header">
        <div>
            <h1 style="font-family: monospace;">{{ $shipment->tracking_number }}</h1>
            <p>Edit shipment details</p>
        </div>
        <a href="{{ route('shipments.show', $shipment) }}" class="adm-btn adm-btn-outline">← Back</a>
    </div>

    <form method="POST" action="{{ route('shipments.update', $shipment) }}">
        @csrf
        @method('PUT')

        <div class="adm-grid adm-grid-2">

            <div class="adm-card">
                <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">Shipment Details</h2>

                <div class="adm-form-group">
                    <label class="adm-label">Customer *</label>
                    <select name="customer_id" class="adm-select" required>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" @selected($shipment->customer_id == $customer->id)>
                                {{ $customer->contact_name }}
                                @if($customer->company_name) — {{ $customer->company_name }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id') <p class="adm-error">{{ $message }}</p> @enderror
                </div>

                <div class="adm-form-group">
                    <label class="adm-label">Description</label>
                    <textarea name="description" class="adm-textarea">{{ old('description', $shipment->description) }}</textarea>
                </div>

                <div class="adm-grid adm-grid-2" style="gap: 16px;">
                    <div class="adm-form-group">
                        <label class="adm-label">Weight (kg)</label>
                        <input type="number" step="0.01" min="0" name="weight_kg" class="adm-input" value="{{ old('weight_kg', $shipment->weight_kg) }}">
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label">Cost (₦)</label>
                        <input type="number" step="0.01" min="0" name="cost" class="adm-input" value="{{ old('cost', $shipment->cost) }}">
                    </div>
                </div>

                <div class="adm-form-group" style="margin-bottom: 0;">
                    <label class="adm-label">Expected Delivery</label>
                    <input type="date" name="expected_delivery" class="adm-input" value="{{ old('expected_delivery', $shipment->expected_delivery?->format('Y-m-d')) }}">
                </div>
            </div>

            <div>
                <div class="adm-card" style="margin-bottom: 20px;">
                    <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">Route</h2>

                    <div class="adm-form-group">
                        <label class="adm-label">Origin Address *</label>
                        <input type="text" name="origin_address" class="adm-input" value="{{ old('origin_address', $shipment->origin_address) }}" required>
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label">Origin City</label>
                        <input type="text" name="origin_city" class="adm-input" value="{{ old('origin_city', $shipment->origin_city) }}">
                    </div>
                    <div class="adm-form-group">
                        <label class="adm-label">Destination Address *</label>
                        <input type="text" name="destination_address" class="adm-input" value="{{ old('destination_address', $shipment->destination_address) }}" required>
                    </div>
                    <div class="adm-form-group" style="margin-bottom: 0;">
                        <label class="adm-label">Destination City</label>
                        <input type="text" name="destination_city" class="adm-input" value="{{ old('destination_city', $shipment->destination_city) }}">
                    </div>
                </div>

                <div class="adm-card">
                    <h2 style="font-size: 16px; font-weight: 700; margin: 0 0 20px;">Receiver</h2>
                    <div class="adm-form-group">
                        <label class="adm-label">Receiver Name</label>
                        <input type="text" name="receiver_name" class="adm-input" value="{{ old('receiver_name', $shipment->receiver_name) }}">
                    </div>
                    <div class="adm-form-group" style="margin-bottom: 0;">
                        <label class="adm-label">Receiver Phone</label>
                        <input type="text" name="receiver_phone" class="adm-input" value="{{ old('receiver_phone', $shipment->receiver_phone) }}">
                    </div>
                </div>
            </div>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end; margin-top: 24px;">
            <a href="{{ route('shipments.show', $shipment) }}" class="adm-btn adm-btn-outline">Cancel</a>
            <button type="submit" class="adm-btn adm-btn-lime">Save Changes</button>
        </div>
    </form>

@endsection