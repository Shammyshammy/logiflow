<x-mail::message>
# Shipment Update

Your shipment **{{ $shipment->tracking_number }}** has been updated.

<x-mail::panel>
**{{ $event->statusLabel() }}**

@if($event->location)
📍 {{ $event->location }}<br>
@endif

@if($event->note)
{{ $event->note }}<br>
@endif

_Updated {{ $event->created_at->format('M d, Y — H:i') }}_
</x-mail::panel>

**Shipment details**

- **From:** {{ $shipment->origin_city ?? $shipment->origin_address }}
- **To:** {{ $shipment->destination_city ?? $shipment->destination_address }}
- **Receiver:** {{ $shipment->receiver_name ?? '—' }}
@if($shipment->expected_delivery)
- **Expected delivery:** {{ $shipment->expected_delivery->format('M d, Y') }}
@endif

<x-mail::button :url="$trackUrl">
Track Shipment
</x-mail::button>

Thanks for using LogiFlow.

— The LogiFlow Team
</x-mail::message>