<?php

namespace Tests\Feature\Admin\Service;

use App\Models\Service;
use App\Models\User;

class ServiceDestroyTest extends BaseServiceTestCase
{
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = Service::factory()->create();
    }

    public function test_guest_user_redirected_to_login_screen(): void
    {
        $response = $this->deleteServicesDestroy($this->service->id);

        $response->assertRedirect('/login');
    }

    public function test_user_redirected_back_when_they_are_not_an_admin(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->deleteServicesDestroy($this->service->id);

        $response->assertRedirect('/');
    }

    public function test_admin_user_cannot_delete_a_non_existing_service(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->deleteServicesDestroy(-1);

        $response->assertNotFound();

        $this->assertDatabaseHas('services', [
            'name' => $this->service->name,
        ]);
    }

    public function test_admin_user_can_delete_an_existing_service(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->deleteServicesDestroy($this->service->id);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('services', [
            'name' => $this->service->name,
        ]);
    }
}
