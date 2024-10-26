<?php

namespace Tests\Feature\Admin\Service;

use App\Models\Service;
use App\Models\User;

class ServiceUpdateTest extends BaseServiceTestCase
{
    private Service $service;
    private array $data = [
        'new_name' => 'new-name',
        'new_description' => '{"en":"new-description"}',
        'new_icon' => 'fa-solid fa-link',
        'new_link' => 'http://new.everestserver.test',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = Service::factory()->create();
    }

    public function test_guest_user_redirected_to_login_screen(): void
    {
        $response = $this->patchServicesUpdate($this->service->id, $this->data);

        $response->assertRedirect('/login');
    }

    public function test_user_redirected_back_when_they_are_not_an_admin(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->patchServicesUpdate($this->service->id, $this->data);

        $response->assertRedirect('/');
    }

    public function test_admin_user_cannot_update_an_existing_service_when_some_required_parameters_are_missing(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->patchServicesUpdate($this->service->id, [
                'new_name' => 'new-name',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrorsIn('updateService', ['new_description', 'new_icon', 'new_link']);

        $this->assertDatabaseMissing('services', [
            'name' => 'new-name',
        ]);
    }

    public function test_admin_user_can_update_an_existing_service_with_only_required_parameters(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->patchServicesUpdate($this->service->id, $this->data);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('services', [
            'name' => 'new-name',
            'description' => '{"en":"new-description"}',
            'icon' => 'fa-solid fa-link',
            'link' => 'http://new.everestserver.test',
            'is_public' => false,
            'is_enabled' => false,
        ]);
    }

    public function test_admin_user_can_update_an_existing_service_with_all_parameters(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->patchServicesUpdate($this->service->id, [
                ...$this->data,
                'new_is_public' => 'on',
                'new_is_enabled' => 'on',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('services', [
            'name' => 'new-name',
            'description' => '{"en":"new-description"}',
            'icon' => 'fa-solid fa-link',
            'link' => 'http://new.everestserver.test',
            'is_public' => true,
            'is_enabled' => true,
        ]);
    }
}
