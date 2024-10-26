<?php

namespace Tests\Feature\Admin\LinkedService;

use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Event;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class BaseLinkedServiceTestCase extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->admin()->create();

        Event::fake();
    }

    protected function getAdminPanelForLinkedServices(?string $column = null, ?string $direction = null, ?string $search = null): TestResponse
    {
        return $this->get(route('admin.panel', [
            'tab' => 'linked-services',
            'column' => $column,
            'direction' => $direction,
            'search' => $search,
        ]));
    }

    protected function postLinkedServicesLink(array $data): TestResponse
    {
        return $this->post(route('linked-services.link'), $data);
    }

    protected function postLinkedServicesUnlink(array $data): TestResponse
    {
        return $this->post(route('linked-services.unlink'), $data);
    }

    protected function createLinkedService(array $attributes  = [], array $pivotAttributes  = [], ?string $state = null): Service
    {
        $service = Service::factory()
            ->when($state !== null, fn (Factory $factory) => $factory->$state())
            ->create($attributes);

        $service->users()->attach($this->user, $pivotAttributes);

        return $service;
    }

    protected function assertSeeLinkedService(TestResponse $response, Service $service, User $user): TestResponse
    {
        return $response->assertSeeTextInOrder([
            $service->name . ' (#' . $service->id . ')',
            $user->username . ' (#' . $user->id . ')',
            $service->users()->where('user_id', '=', $user->id)->first()?->pivot->identifier ?? '-',
        ]);
    }
}
