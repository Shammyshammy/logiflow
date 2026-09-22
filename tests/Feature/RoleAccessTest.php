<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    public function test_guest_is_redirected_from_dashboard(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_admin_can_access_dashboard(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->get('/dashboard')->assertOk();
    }

    public function test_admin_can_access_shipments_index(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)->get('/shipments')->assertOk();
    }

    public function test_driver_cannot_access_shipments_index(): void
    {
        $driverUser = $this->createDriverUser();
        $this->createDriverRecord($driverUser);

        $this->actingAs($driverUser)->get('/shipments')->assertForbidden();
    }

    public function test_customer_cannot_access_customers_index(): void
    {
        $customerUser = $this->createCustomerUser();
        $this->createCustomer(['user_id' => $customerUser->id]);

        $this->actingAs($customerUser)->get('/customers')->assertForbidden();
    }

    public function test_inactive_user_is_blocked(): void
    {
        $admin = $this->createAdmin(['is_active' => false]);

        $this->actingAs($admin)->get('/shipments')->assertForbidden();
    }
}