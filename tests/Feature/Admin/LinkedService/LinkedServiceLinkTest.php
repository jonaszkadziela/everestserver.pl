<?php

namespace Tests\Feature\Admin\LinkedService;

use App\Models\Service;
use App\Models\User;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

class LinkedServiceLinkTest extends BaseLinkedServiceTestCase
{
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = Service::factory()->create();
    }

    public function test_guest_user_redirected_to_login_screen(): void
    {
        $response = $this->postLinkedServicesLink([
            'user_id' => $this->user->id,
            'service_id' => $this->service->id,
            'identifier' => 'testing',
        ]);

        $response->assertRedirect('/login');

        $this->assertDatabaseMissing('services_users', [
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'identifier' => 'testing',
        ]);
    }

    public function test_user_redirected_back_when_they_are_not_an_admin(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->postLinkedServicesLink([
                'user_id' => $this->user->id,
                'service_id' => $this->service->id,
                'identifier' => 'testing',
            ]);

        $response->assertRedirect('/');

        $this->assertDatabaseMissing('services_users', [
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'identifier' => 'testing',
        ]);
    }

    public function test_admin_user_cannot_link_a_non_existing_service_to_a_user(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->postLinkedServicesLink([
                'user_id' => $this->user->id,
                'service_id' => -1,
                'identifier' => 'testing',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrorsIn('linkService', 'service_id');
    }

    public function test_admin_user_cannot_link_a_service_to_a_user_twice(): void
    {
        $this->service->users()->attach($this->user, ['identifier' => 'testing']);

        $response = $this
            ->actingAs($this->user)
            ->postLinkedServicesLink([
                'user_id' => $this->user->id,
                'service_id' => $this->service->id,
                'identifier' => 'testing',
            ]);

        $response->assertRedirect();

        Event::assertDispatched(
            MessageLogged::class,
            fn (MessageLogged $event) =>
                $event->level === 'info' &&
                Str::endsWith($event->message, $this->service->name . ' service could not be linked to user ' . $this->user->username) &&
                $event->context['message'] === 'This service is already linked to the user',
        );
    }

    public function test_admin_user_can_link_a_service_to_a_user(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->postLinkedServicesLink([
                'user_id' => $this->user->id,
                'service_id' => $this->service->id,
                'identifier' => 'testing',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('services_users', [
            'service_id' => $this->service->id,
            'user_id' => $this->user->id,
            'identifier' => 'testing',
        ]);
    }
}
