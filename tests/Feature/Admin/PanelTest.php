<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Support\Facades\Lang;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class PanelTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->admin()->create();
    }

    private function getAdminPanel(?string $tab = null): TestResponse
    {
        return $this->get(route('admin.panel', [
            'tab' => $tab,
        ]));
    }

    public function test_guest_user_redirected_to_login_screen(): void
    {
        $response = $this->getAdminPanel();

        $response->assertRedirect('/login');
    }

    public function test_user_redirected_back_when_they_are_not_an_admin(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->getAdminPanel();

        $response->assertRedirect('/');
    }

    public function test_admin_user_redirected_back_when_they_are_trying_to_access_invalid_tab(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->getAdminPanel('wrong-tab');

        $response->assertRedirect('/');
    }

    public function test_admin_user_can_access_admin_panel(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->getAdminPanel();

        $response->assertOk();
    }

    public function test_admin_user_can_access_linked_services_tab(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->getAdminPanel('linked-services');

        $response->assertOk();
        $response->assertSeeText(Lang::get('admin.panel.linked-services.title'));
        $response->assertSeeText(Lang::get('admin.panel.linked-services.description'));
    }

    public function test_admin_user_can_access_services_tab(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->getAdminPanel('services');

        $response->assertOk();
        $response->assertSeeText(Lang::get('admin.panel.services.title'));
        $response->assertSeeText(Lang::get('admin.panel.services.description'));
    }

    public function test_admin_user_can_access_users_tab(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->getAdminPanel('users');

        $response->assertOk();
        $response->assertSeeText(Lang::get('admin.panel.users.title'));
        $response->assertSeeText(Lang::get('admin.panel.users.description'));
    }

    public function test_users_tab_is_shown_by_default_in_admin_panel(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->getAdminPanel();

        $response->assertOk();
        $response->assertSeeText(Lang::get('admin.panel.users.title'));
        $response->assertSeeText(Lang::get('admin.panel.users.description'));
    }
}
