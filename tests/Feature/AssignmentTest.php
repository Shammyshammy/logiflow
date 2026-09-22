<?php

namespace Tests\Feature;

use Tests\TestCase;

class AssignmentTest extends TestCase
{
    public function test_admin_can_assign_driver_and_vehicle(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $shipment = $this->createShipment($customer);
        $driverUser = $this->createDriverUser();
        $driver = $this->createDriverRecord($driverUser);
        $vehicle = $this->createVehicle();

        $this->actingAs($admin)->post('/shipments/' . $shipment->id . '/assign', [
            'driver_id'  => $driver->id,
            'vehicle_id' => $vehicle->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('shipment_assignments', [
            'shipment_id' => $shipment->id,
            'driver_id'   => $driver->id,
            'vehicle_id'  => $vehicle->id,
        ]);

        $driver->refresh();
        $vehicle->refresh();
        $this->assertEquals('on_delivery', $driver->status);
        $this->assertEquals('in_use', $vehicle->status);
    }

    public function test_admin_can_unassign(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $shipment = $this->createShipment($customer);
        $driverUser = $this->createDriverUser();
        $driver = $this->createDriverRecord($driverUser);

        $this->actingAs($admin)->post('/shipments/' . $shipment->id . '/assign', [
            'driver_id' => $driver->id,
        ]);

        $this->actingAs($admin)
            ->post('/shipments/' . $shipment->id . '/unassign')
            ->assertRedirect();

        $driver->refresh();
        $this->assertEquals('available', $driver->status);
    }

    public function test_reassigning_closes_previous_assignment(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $shipment = $this->createShipment($customer);

        $driverUser1 = $this->createDriverUser();
        $driver1 = $this->createDriverRecord($driverUser1);

        $driverUser2 = $this->createDriverUser();
        $driver2 = $this->createDriverRecord($driverUser2);

        $this->actingAs($admin)->post('/shipments/' . $shipment->id . '/assign', [
            'driver_id' => $driver1->id,
        ]);

        $this->actingAs($admin)->post('/shipments/' . $shipment->id . '/assign', [
            'driver_id' => $driver2->id,
        ]);

        $this->assertDatabaseHas('shipment_assignments', [
            'shipment_id'   => $shipment->id,
            'driver_id'     => $driver2->id,
            'unassigned_at' => null,
        ]);

        $driver1->refresh();
        $this->assertEquals('available', $driver1->status);
    }
}