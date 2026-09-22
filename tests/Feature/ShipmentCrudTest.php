<?php

namespace Tests\Feature;

use App\Models\Shipment;
use Tests\TestCase;

class ShipmentCrudTest extends TestCase
{
    public function test_admin_can_create_shipment(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();

        $response = $this->actingAs($admin)->post('/shipments', [
            'customer_id'         => $customer->id,
            'origin_address'      => 'Origin St',
            'origin_city'         => 'Port Harcourt',
            'destination_address' => 'Dest St',
            'destination_city'    => 'Lagos',
            'receiver_name'       => 'Sam',
            'receiver_phone'      => '+2348000000003',
            'weight_kg'           => 12,
            'description'         => 'Books',
            'cost'                => 7500,
            'expected_delivery'   => now()->addDays(3)->toDateString(),
        ]);

        $response->assertRedirect('/shipments');

        $shipment = Shipment::latest()->first();
        $this->assertNotNull($shipment);
        $this->assertEquals($customer->id, $shipment->customer_id);
        $this->assertEquals('pending', $shipment->status);
        $this->assertStringStartsWith('LGF-', $shipment->tracking_number);
    }

    public function test_creating_shipment_creates_initial_event(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();

        $this->actingAs($admin)->post('/shipments', [
            'customer_id'         => $customer->id,
            'origin_address'      => 'Origin St',
            'destination_address' => 'Dest St',
        ]);

        $shipment = Shipment::latest()->first();

        $this->assertDatabaseHas('shipment_events', [
            'shipment_id' => $shipment->id,
            'status'      => 'pending',
        ]);
    }

    public function test_create_shipment_requires_customer(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->post('/shipments', [
            'origin_address'      => 'Origin St',
            'destination_address' => 'Dest St',
        ])->assertSessionHasErrors('customer_id');
    }

    public function test_admin_can_view_shipment_detail(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $shipment = $this->createShipment($customer);

        $this->actingAs($admin)
            ->get('/shipments/' . $shipment->id)
            ->assertOk()
            ->assertSee($shipment->tracking_number);
    }

    public function test_admin_can_update_shipment(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $shipment = $this->createShipment($customer);

        $this->actingAs($admin)->put('/shipments/' . $shipment->id, [
            'customer_id'         => $customer->id,
            'origin_address'      => 'Updated Origin',
            'destination_address' => 'Updated Dest',
        ])->assertRedirect();

        $this->assertDatabaseHas('shipments', [
            'id'             => $shipment->id,
            'origin_address' => 'Updated Origin',
        ]);
    }

    public function test_admin_can_delete_shipment(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $shipment = $this->createShipment($customer);

        $this->actingAs($admin)
            ->delete('/shipments/' . $shipment->id)
            ->assertRedirect('/shipments');

        $this->assertDatabaseMissing('shipments', ['id' => $shipment->id]);
    }
}