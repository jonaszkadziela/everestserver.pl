<?php

namespace Tests\Feature\Admin\Service;

use App\Models\Service;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class ServiceIndexTest extends BaseServiceTestCase
{
    public function test_admin_user_can_list_services(): void
    {
        Config::set('pagination.admin.services', 10);

        $defaultServices = Service::get();
        $service = Service::factory()->create();
        $privateService = Service::factory()->private()->create();
        $disabledService = Service::factory()->disabled()->create();

        $services = $defaultServices->merge(collect([$service, $privateService, $disabledService]));

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForServices();

        $response->assertOk();

        $services->each(fn (Service $service) => $this->assertSeeService($response, $service));
    }

    public function test_admin_user_can_list_paginated_services(): void
    {
        Config::set('pagination.admin.services', 5);

        $defaultServices = Service::get();
        $newServices = Service::factory(6)->create();

        $services = $defaultServices->merge($newServices);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForServices();

        $response->assertOk();

        $services->take(5)->each(fn (Service $service) => $this->assertSeeService($response, $service));

        $response->assertDontSeeText($services->last()->name);
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

    public function test_admin_user_can_search_services(): void
    {
        $service = Service::factory()->create([
            'name' => 'first',
        ]);

        $otherService = Service::factory()->create([
            'name' => 'second',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForServices(search: 'first');

        $response->assertOk();
        $response->assertDontSeeText($otherService->name);

        $this->assertSeeService($response, $service);
    }

    public function test_admin_user_can_see_no_search_results_when_service_cannot_be_found(): void
    {
        Service::factory()->create([
            'name' => 'first',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForServices(search: 'third');

        $response->assertOk();
        $response->assertSeeText(Lang::get('admin.panel.services.no-results'));
        $response->assertViewHas('data.transformed', []);
    }

    public function test_admin_user_can_sort_services_in_ascending_order(): void
    {
        $service = Service::factory()->create([
            'name' => 'b-name',
        ]);

        $otherService = Service::factory()->create([
            'name' => 'a-name',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForServices('name', 'asc');

        $response->assertOk();
        $response->assertSeeTextInOrder([
            $otherService->name,
            $service->name,
        ]);

        $this->assertSeeService($response, $service);
        $this->assertSeeService($response, $otherService);
    }

    public function test_admin_user_can_sort_services_in_descending_order(): void
    {
        $service = Service::factory()->create([
            'name' => 'b-name',
        ]);

        $otherService = Service::factory()->create([
            'name' => 'a-name',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForServices('name', 'desc');

        $response->assertOk();
        $response->assertSeeTextInOrder([
            $service->name,
            $otherService->name,
        ]);

        $this->assertSeeService($response, $service);
        $this->assertSeeService($response, $otherService);
    }
}
