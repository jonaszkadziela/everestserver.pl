<?php

namespace Tests\Feature\Admin\LinkedService;

use App\Models\Service;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class LinkedServiceIndexTest extends BaseLinkedServiceTestCase
{
    public function test_admin_user_can_list_linked_services(): void
    {
        $service = $this->createLinkedService();
        $privateService = $this->createLinkedService(state: 'private');
        $disabledService = $this->createLinkedService(state: 'disabled');

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForLinkedServices();

        $response->assertOk();

        $this->assertSeeLinkedService($response, $service, $this->user);
        $this->assertSeeLinkedService($response, $privateService, $this->user);
        $this->assertSeeLinkedService($response, $disabledService, $this->user);
    }

    public function test_admin_user_can_list_paginated_linked_services(): void
    {
        Config::set('pagination.admin.linked_services', 5);

        $services = collect()->times(6)->map(fn () => $this->createLinkedService());

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForLinkedServices();

        $response->assertOk();

        $services->take(5)->each(fn (Service $service) => $this->assertSeeLinkedService($response, $service, $this->user));

        $response->assertDontSeeText($services->last()->name . ' (#' . $services->last()->id . ')');
        $response->assertSeeTextInOrder([
            Lang::get('pagination.showing'),
            1,
            Lang::get('pagination.to'),
            5,
            Lang::get('pagination.of'),
            $services->count(),
            Lang::get('pagination.results'),
        ]);
    }

    public function test_admin_user_can_search_linked_services(): void
    {
        $service = $this->createLinkedService([
            'name' => 'first',
        ]);

        $otherService = $this->createLinkedService([
            'name' => 'second',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForLinkedServices(search: 'first');

        $response->assertOk();
        $response->assertDontSeeText($otherService->name . ' (#' . $otherService->id . ')');

        $this->assertSeeLinkedService($response, $service, $this->user);
    }

    public function test_admin_user_can_see_no_search_results_when_linked_service_cannot_be_found(): void
    {
        $this->createLinkedService([
            'name' => 'first',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForLinkedServices(search: 'third');

        $response->assertOk();
        $response->assertSeeText(Lang::get('admin.panel.linked-services.no-results'));
        $response->assertViewHas('data.transformed', []);
    }

    public function test_admin_user_can_sort_linked_services_in_ascending_order(): void
    {
        $service = $this->createLinkedService([
            'name' => 'b-name',
        ]);

        $otherService = $this->createLinkedService([
            'name' => 'a-name',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForLinkedServices('name', 'asc');

        $response->assertOk();
        $response->assertSeeTextInOrder([
            $otherService->name,
            $service->name,
        ]);

        $this->assertSeeLinkedService($response, $service, $this->user);
        $this->assertSeeLinkedService($response, $otherService, $this->user);
    }

    public function test_admin_user_can_sort_linked_services_in_descending_order(): void
    {
        $service = $this->createLinkedService([
            'name' => 'b-name',
        ]);

        $otherService = $this->createLinkedService([
            'name' => 'a-name',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForLinkedServices('name', 'desc');

        $response->assertOk();
        $response->assertSeeTextInOrder([
            $service->name,
            $otherService->name,
        ]);

        $this->assertSeeLinkedService($response, $service, $this->user);
        $this->assertSeeLinkedService($response, $otherService, $this->user);
    }
}
