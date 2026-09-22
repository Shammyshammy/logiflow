<?php

namespace Tests;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Shipment;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Str;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function createAdmin(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role'      => 'admin',
            'is_active' => true,
        ], $attributes));
    }

    protected function createStaff(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role'      => 'staff',
            'is_active' => true,
        ], $attributes));
    }

    protected function createDriverUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role'      => 'driver',
            'is_active' => true,
        ], $attributes));
    }

    protected function createCustomerUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role'      => 'customer',
            'is_active' => true,
        ], $attributes));
    }

    protected function createCustomer(array $attributes = []): Customer
    {
        return Customer::create(array_merge([
            'contact_name' => 'Jane Customer',
            'company_name' => 'Acme Ltd',
            'email'        => 'jane@acme.test',
            'phone'        => '+2348000000001',
            'address'      => '12 Test Street',
            'city'         => 'Port Harcourt',
            'state'        => 'Rivers',
            'country'      => 'Nigeria',
        ], $attributes));
    }

    protected function createShipment(Customer $customer, array $attributes = []): Shipment
    {
        return Shipment::create(array_merge([
            'tracking_number'     => 'LGF-TEST-' . strtoupper(Str::random(6)),
            'customer_id'         => $customer->id,
            'origin_address'      => 'Origin St',
            'origin_city'         => 'Port Harcourt',
            'destination_address' => 'Dest St',
            'destination_city'    => 'Lagos',
            'receiver_name'       => 'Receiver Name',
            'receiver_phone'      => '+2348000000002',
            'weight_kg'           => 10,
            'description'         => 'Test package',
            'cost'                => 5000,
            'status'              => 'pending',
        ], $attributes));
    }

    protected function createDriverRecord(User $user, array $attributes = []): Driver
    {
        return Driver::create(array_merge([
            'user_id'        => $user->id,
            'license_number' => 'DRV-' . strtoupper(Str::random(6)),
            'status'         => 'available',
        ], $attributes));
    }

    protected function createVehicle(array $attributes = []): Vehicle
    {
        return Vehicle::create(array_merge([
            'plate_number' => 'TEST-' . strtoupper(Str::random(4)),
            'type'         => 'truck',
            'capacity_kg'  => 5000,
            'status'       => 'available',
        ], $attributes));
    }
}