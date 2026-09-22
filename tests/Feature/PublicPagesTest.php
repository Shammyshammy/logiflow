<?php

namespace Tests\Feature;

use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    public function test_landing_page_loads(): void
    {
        $this->get('/')->assertOk()->assertSee('LogiFlow');
    }

    public function test_tracking_page_loads(): void
    {
        $this->get('/track')->assertOk();
    }

    public function test_tracking_show_returns_shipment(): void
    {
        $customer = $this->createCustomer();
        $shipment = $this->createShipment($customer);

        $this->get('/track/' . $shipment->tracking_number)
            ->assertOk()
            ->assertSee($shipment->tracking_number);
    }

    public function test_tracking_show_404s_on_unknown_number(): void
    {
        $this->get('/track/LGF-DOES-NOT-EXIST')->assertNotFound();
    }

    public function test_booking_form_loads(): void
    {
        $this->get('/book')->assertOk();
    }

    public function test_booking_creates_customer_shipment_and_event(): void
    {
        $response = $this->post('/book', [
            'contact_name'        => 'New Customer',
            'email'               => 'new@example.com',
            'phone'               => '+2348000000010',
            'company_name'        => 'New Co',
            'origin_address'      => '1 Origin St',
            'origin_city'         => 'Yenagoa',
            'destination_address' => '2 Dest St',
            'destination_city'    => 'Lagos',
            'receiver_name'       => 'Receiver Name',
            'receiver_phone'      => '+2348000000011',
            'weight_kg'           => 5,
            'description'         => 'Books',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('customers', ['email' => 'new@example.com']);
        $this->assertDatabaseHas('shipments', ['destination_city' => 'Lagos']);
        $this->assertDatabaseHas('shipment_events', ['note' => 'Booking submitted online.']);
    }

    public function test_booking_validates_required_fields(): void
    {
        $this->post('/book', [])->assertSessionHasErrors([
            'contact_name',
            'email',
            'phone',
            'origin_address',
            'destination_address',
            'receiver_name',
            'receiver_phone',
            'description',
        ]);
    }
}