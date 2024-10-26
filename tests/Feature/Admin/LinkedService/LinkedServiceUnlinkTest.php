<?php

namespace Tests\Feature\Admin\LinkedService;

use App\Models\Service;
use App\Models\User;

class LinkedServiceUnlinkTest extends BaseLinkedServiceTestCase
{
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = $this->createLinkedService(pivotAttributes: ['identifier' => 'testing']);
    }

    public function test_guest_user_redirected_to_login_screen(): void
    {
        $response = $this->postLinkedServicesUnlink([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseHas('services_users', [
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'identifier' => 'testing',
        ]);
    }

    public function test_user_redirected_back_when_they_are_not_an_admin(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->postLinkedServicesUnlink([
                'user_id' => $this->user->id,
                'service_id' => $this->service->id,
            ]);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('services_users', [
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'identifier' => 'testing',
        ]);
    }

    public function test_admin_user_cannot_unlink_a_non_existing_service_from_a_user(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->postLinkedServicesUnlink([
                'user_id' => $this->user->id,
                'service_id' => -1,
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrorsIn('unlinkService', 'service_id');
    }

    public function test_admin_user_can_unlink_a_service_from_a_user(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->postLinkedServicesUnlink([
                'user_id' => $this->user->id,
                'service_id' => $this->service->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('services_users', [
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'identifier' => 'testing',
        ]);
    }
}
