<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Lang;

class UserIndexTest extends BaseUserTestCase
{
    public function test_admin_user_can_list_users(): void
    {
        $user = User::factory()->create();
        $unverifiedUser = User::factory()->unverified()->create();
        $disabledUser = User::factory()->disabled()->create();

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForUsers();

        $response->assertOk();

        $this->assertSeeUser($response, $this->user);
        $this->assertSeeUser($response, $user);
        $this->assertSeeUser($response, $unverifiedUser);
        $this->assertSeeUser($response, $disabledUser);
    }

    public function test_admin_user_can_list_paginated_users(): void
    {
        Config::set('pagination.admin.users', 5);

        $users = User::factory(5)->create();
        $users->prepend($this->user);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForUsers();

        $response->assertOk();

        $users->take(5)->each(fn (User $user) => $this->assertSeeUser($response, $user));

        $response->assertDontSeeText($users->last()->username);
        $response->assertSeeTextInOrder([
            Lang::get('pagination.showing'),
            1,
            Lang::get('pagination.to'),
            5,
            Lang::get('pagination.of'),
            $users->count(),
            Lang::get('pagination.results'),
        ]);
    }

    public function test_admin_user_can_search_users(): void
    {
        $this->user->username = 'first';
        $this->user->email = 'first@test.com';
        $this->user->save();

        $user = User::factory()->create([
            'username' => 'second',
            'email' => 'second@test.com',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForUsers(search: 'first');

        $response->assertOk();
        $response->assertDontSeeText($user->username);

        $this->assertSeeUser($response, $this->user);
    }

    public function test_admin_user_can_see_no_search_results_when_user_cannot_be_found(): void
    {
        $this->user->username = 'first';
        $this->user->email = 'first@test.com';
        $this->user->save();

        User::factory()->create([
            'username' => 'second',
            'email' => 'second@test.com',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForUsers(search: 'third');

        $response->assertOk();
        $response->assertSeeText(Lang::get('admin.panel.users.no-results'));
        $response->assertViewHas('data.transformed', []);
    }

    public function test_admin_user_can_sort_users_in_ascending_order(): void
    {
        $this->user->username = 'b-username';
        $this->user->email = 'b-username@test.com';
        $this->user->save();

        $user = User::factory()->create([
            'username' => 'a-username',
            'email' => 'a-username@test.com',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForUsers('username', 'asc');

        $response->assertOk();
        $response->assertSeeTextInOrder([
            $user->username,
            $this->user->username,
        ]);

        $this->assertSeeUser($response, $user);
        $this->assertSeeUser($response, $this->user);
    }

    public function test_admin_user_can_sort_users_in_descending_order(): void
    {
        $this->user->username = 'b-username';
        $this->user->email = 'b-username@test.com';
        $this->user->save();

        $user = User::factory()->create([
            'username' => 'a-username',
            'email' => 'a-username@test.com',
        ]);

        $response = $this
            ->actingAs($this->user)
            ->getAdminPanelForUsers('username', 'desc');

        $response->assertOk();
        $response->assertSeeTextInOrder([
            $this->user->username,
            $user->username,
        ]);

        $this->assertSeeUser($response, $this->user);
        $this->assertSeeUser($response, $user);
    }
}
