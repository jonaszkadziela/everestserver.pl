<?php

namespace Tests\Feature\Admin\Service;

use App\Models\User;

class ServiceStoreTest extends BaseServiceTestCase
{
    private array $data = [
        'name' => 'service-name',
        'description' => '{"en":"service-description"}',
        'icon' => 'fa-solid fa-globe',
        'link' => 'http://everestserver.test',
    ];

    public function test_guest_user_redirected_to_login_screen(): void
    {
        $response = $this->postServicesStore($this->data);

        $response->assertRedirect('/login');
    }

    public function test_user_redirected_back_when_they_are_not_an_admin(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->postServicesStore($this->data);

        $response->assertRedirect('/');
    }

    public function test_admin_user_cannot_create_a_new_service_when_some_required_parameters_are_missing(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->postServicesStore([
                'name' => 'service-name',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrorsIn('createService', ['description', 'icon', 'link']);

        $this->assertDatabaseMissing('services', [
            'name' => 'service-name',
        ]);
    }

    public function test_admin_user_can_create_a_new_service_with_only_required_parameters(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->postServicesStore($this->data);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('services', [
            'name' => 'service-name',
            'description' => '{"en":"service-description"}',
            'icon' => 'fa-solid fa-globe',
            'link' => 'http://everestserver.test',
            'is_public' => false,
            'is_enabled' => false,
        ]);
    }

    public function test_admin_user_can_create_a_new_service_with_all_parameters(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->postServicesStore([
                ...$this->data,
                'is_public' => 'on',
                'is_enabled' => 'on',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('services', [
            'name' => 'service-name',
            'description' => '{"en":"service-description"}',
            'icon' => 'fa-solid fa-globe',
            'link' => 'http://everestserver.test',
            'is_public' => true,
            'is_enabled' => true,
        ]);
    }
}
