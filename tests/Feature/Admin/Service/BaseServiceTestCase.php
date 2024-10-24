<?php

namespace Tests\Feature\Admin\Service;

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class BaseServiceTestCase extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->admin()->create();

        Event::fake();
    }

    protected function getAdminPanelForServices(?string $column = null, ?string $direction = null, ?string $search = null): TestResponse
    {
        return $this->get(route('admin.panel', [
            'tab' => 'services',
            'column' => $column,
            'direction' => $direction,
            'search' => $search,
        ]));
    }

    protected function postServicesStore(array $data): TestResponse
    {
        return $this->post(route('services.store'), $data);
    }

    protected function patchServicesUpdate(Service $service, array $data): TestResponse
    {
        return $this->patch(route('services.update', [
            'service' => $service,
        ]), $data);
    }

    protected function deleteServicesDestroy(int $serviceId): TestResponse
    {
        return $this->delete(route('services.destroy', [
            'service' => $serviceId,
        ]));
    }

    protected function assertSeeService(TestResponse $response, Service $service): TestResponse
    {
        return $response->assertSeeTextInOrder([
            $service->id,
            $service->name,
            $service->description,
            $service->icon,
            $service->link,
            $service->is_public ? Lang::get('main.yes') : Lang::get('main.no'),
            $service->is_enabled ? Lang::get('main.yes') : Lang::get('main.no'),
        ]);
    }
}
