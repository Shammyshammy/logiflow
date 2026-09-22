<?php

namespace App\Mail;

use App\Models\Shipment;
use App\Models\ShipmentEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShipmentStatusChanged extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Shipment $shipment,
        public ShipmentEvent $event,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Shipment Update: ' . $this->event->statusLabel() . ' — ' . $this->shipment->tracking_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.shipment-status',
            with: [
                'shipment' => $this->shipment,
                'event'    => $this->event,
                'trackUrl' => route('tracking.show', $this->shipment->tracking_number),
            ],
        );
    }
}