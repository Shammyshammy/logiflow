<?php

namespace App\Listeners;

use App\Events\ShipmentStatusUpdated;
use App\Mail\ShipmentStatusChanged;
use App\Notifications\ShipmentStatusNotification;
use Illuminate\Support\Facades\Mail;

class SendShipmentStatusEmail
{
    public function handle(ShipmentStatusUpdated $event): void
    {
        $shipment = $event->shipment;

        // Email to customer
        $email = $shipment->customer?->email;
        if ($email) {
            Mail::to($email)->send(
                new ShipmentStatusChanged($shipment, $event->event)
            );
        }

        // In-app notification to customer's user account
        $user = $shipment->customer?->user;
        if ($user) {
            $user->notify(new ShipmentStatusNotification($shipment, $event->event));
        }
    }
}