<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Driver;
use App\Models\Shipment;
use App\Models\ShipmentEvent;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LogiflowSeeder extends Seeder
{
    public function run(): void
    {
        // -------------------------
        // Users
        // -------------------------
        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@logiflow.test',
            'phone'    => '+2348000000001',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $staff = User::create([
            'name'     => 'Staff User',
            'email'    => 'staff@logiflow.test',
            'phone'    => '+2348000000002',
            'password' => Hash::make('password'),
            'role'     => 'staff',
        ]);

        $driverUser = User::create([
            'name'     => 'John Driver',
            'email'    => 'driver@logiflow.test',
            'phone'    => '+2348000000003',
            'password' => Hash::make('password'),
            'role'     => 'driver',
        ]);

        $customerUser = User::create([
            'name'     => 'Jane Customer',
            'email'    => 'customer@logiflow.test',
            'phone'    => '+2348000000004',
            'password' => Hash::make('password'),
            'role'     => 'customer',
        ]);

        // -------------------------
        // Customers
        // -------------------------
        $customer = Customer::create([
            'user_id'      => $customerUser->id,
            'company_name' => 'Acme Shipping Ltd',
            'contact_name' => 'Jane Customer',
            'email'        => 'jane@acme.test',
            'phone'        => '+2348000000004',
            'address'      => '12 Marina Road',
            'city'         => 'Port Harcourt',
            'state'        => 'Rivers',
            'country'      => 'Nigeria',
        ]);

        $customer2 = Customer::create([
            'company_name' => 'Blue-Con Subsea Ltd',
            'contact_name' => 'Operations Manager',
            'email'        => 'ops@bluecon.test',
            'phone'        => '+2348000000005',
            'address'      => '45 Trans-Amadi',
            'city'         => 'Port Harcourt',
            'state'        => 'Rivers',
            'country'      => 'Nigeria',
        ]);

        // -------------------------
        // Drivers
        // -------------------------
        Driver::create([
            'user_id'        => $driverUser->id,
            'license_number' => 'DRV-2026-0001',
            'status'         => 'available',
        ]);

        // -------------------------
        // Vehicles
        // -------------------------
        Vehicle::create([
            'plate_number' => 'RIV-234-AB',
            'type'         => 'truck',
            'capacity_kg'  => 5000,
            'status'       => 'available',
        ]);

        Vehicle::create([
            'plate_number' => 'RIV-567-CD',
            'type'         => 'van',
            'capacity_kg'  => 1200,
            'status'       => 'available',
        ]);

        // -------------------------
        // Sample Shipments
        // -------------------------
        foreach ([$customer, $customer2] as $c) {
            $shipment = Shipment::create([
                'tracking_number'     => 'LGF-' . date('Y') . '-' . strtoupper(Str::random(6)),
                'customer_id'         => $c->id,
                'created_by'          => $staff->id,
                'origin_address'      => '12 Marina Road, Port Harcourt',
                'origin_city'         => 'Port Harcourt',
                'destination_address' => '1 Broad Street, Lagos',
                'destination_city'    => 'Lagos',
                'receiver_name'       => 'Receiver ' . $c->id,
                'receiver_phone'      => '+2348000000000',
                'weight_kg'           => 25.5,
                'description'         => 'Sample shipment for testing',
                'cost'                => 15000,
                'status'              => 'pending',
                'expected_delivery'   => now()->addDays(3)->toDateString(),
            ]);

            ShipmentEvent::create([
                'shipment_id' => $shipment->id,
                'status'      => 'pending',
                'location'    => 'Port Harcourt Hub',
                'note'        => 'Shipment created.',
                'created_by'  => $staff->id,
            ]);
        }

        $this->command->info('✅ LogiFlow seeded.');
        $this->command->info('   Admin:    admin@logiflow.test / password');
        $this->command->info('   Staff:    staff@logiflow.test / password');
        $this->command->info('   Driver:   driver@logiflow.test / password');
        $this->command->info('   Customer: customer@logiflow.test / password');
    }
}