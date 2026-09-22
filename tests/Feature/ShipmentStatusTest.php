<?php

namespace Tests\Feature;

use App\Mail\ShipmentStatusChanged;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ShipmentStatusTest extends TestCase
{
    public function test_admin_can_update_shipment_status(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $shipment = $this->createShipment($customer);

        $this->actingAs($admin)->post('/shipments/' . $shipment->id . '/events', [
            'status'   => 'in_transit',
            'location' => 'On the road',
            'note'     => 'Moving',
        ])->assertRedirect();

        $this->assertDatabaseHas('shipments', [
            'id'     => $shipment->id,
            'status' => 'in_transit',
        ]);

        $this->assertDatabaseHas('shipment_events', [
            'shipment_id' => $shipment->id,
            'status'      => 'in_transit',
            'location'    => 'On the road',
        ]);
    }

    public function test_status_update_sends_email_when_customer_has_email(): void
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $customer = $this->createCustomer(['email' => 'real@example.com']);
        $shipment = $this->createShipment($customer);

        $this->actingAs($admin)->post('/shipments/' . $shipment->id . '/events', [
            'status' => 'in_transit',
        ]);

        Mail::assertSent(ShipmentStatusChanged::class);
    }

    public function test_status_update_does_not_send_email_when_customer_has_no_email(): void
    {
        Mail::fake();

        $admin = $this->createAdmin();
        $customer = $this->createCustomer(['email' => null]);
        $shipment = $this->createShipment($customer);

        $this->actingAs($admin)->post('/shipments/' . $shipment->id . '/events', [
            'status' => 'in_transit',
        ]);

        Mail::assertNothingSent();
    }

    public function test_delivered_status_sets_delivered_at(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $shipment = $this->createShipment($customer);

        $this->actingAs($admin)->post('/shipments/' . $shipment->id . '/events', [
            'status' => 'delivered',
        ]);

        $shipment->refresh();
        $this->assertNotNull($shipment->delivered_at);
    }

    public function test_delivered_status_frees_up_assigned_driver(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $shipment = $this->createShipment($customer);

        $driverUser = $this->createDriverUser();
        $driver = $this->createDriverRecord($driverUser);

        $this->actingAs($admin)->post('/shipments/' . $shipment->id . '/assign', [
            'driver_id' => $driver->id,
        ]);

        $driver->refresh();
        $this->assertEquals('on_delivery', $driver->status);

        $this->actingAs($admin)->post('/shipments/' . $shipment->id . '/events', [
            'status' => 'delivered',
        ]);

        $driver->refresh();
        $this->assertEquals('available', $driver->status);
    }

    public function test_driver_can_update_own_assigned_shipment_status(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $shipment = $this->createShipment($customer);

        $driverUser = $this->createDriverUser();
        $driver = $this->createDriverRecord($driverUser);

        $this->actingAs($admin)->post('/shipments/' . $shipment->id . '/assign', [
            'driver_id' => $driver->id,
        ]);

        $this->actingAs($driverUser)->post('/driver/shipments/' . $shipment->id . '/status', [
            'status' => 'picked_up',
        ])->assertRedirect();

        $this->assertDatabaseHas('shipment_events', [
            'shipment_id' => $shipment->id,
            'status'      => 'picked_up',
        ]);
    }

    public function test_driver_cannot_update_unassigned_shipment_status(): void
    {
        $customer = $this->createCustomer();
        $shipment = $this->createShipment($customer);

        $driverUser = $this->createDriverUser();
        $this->createDriverRecord($driverUser);

        $this->actingAs($driverUser)
            ->post('/driver/shipments/' . $shipment->id . '/status', [
                'status' => 'picked_up',
            ])
            ->assertForbidden();
    }
}