<?php

namespace App\Notifications;

use App\Models\Shipment;
use App\Models\ShipmentEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ShipmentStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Shipment $shipment,
        public ShipmentEvent $event,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'shipment_id'     => $this->shipment->id,
            'tracking_number' => $this->shipment->tracking_number,
            'status'          => $this->event->status,
            'status_label'    => $this->event->statusLabel(),
            'location'        => $this->event->location,
            'note'            => $this->event->note,
            'url'             => route('tracking.show', $this->shipment->tracking_number),
            'created_at'      => $this->event->created_at->toIso8601String(),
        ];
    }
}