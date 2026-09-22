<?php

namespace Tests\Feature;

use Tests\TestCase;

class NotificationAndExportTest extends TestCase
{
    public function test_admin_can_view_notifications_page(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->get('/notifications')
            ->assertOk();
    }

    public function test_admin_can_mark_all_notifications_read(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->post('/notifications/read-all')
            ->assertRedirect();
    }

    public function test_admin_can_export_csv(): void
    {
        $admin = $this->createAdmin();
        $customer = $this->createCustomer();
        $this->createShipment($customer);

        $response = $this->actingAs($admin)->get('/shipments/export/csv');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    public function test_admin_can_view_activity_log(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin)
            ->get('/activity')
            ->assertOk();
    }
}