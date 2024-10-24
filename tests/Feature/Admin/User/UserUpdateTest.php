<?php

namespace Tests\Feature\Admin\User;

use App\Events\UserUpdated;
use App\Models\User;
use App\Notifications\AccountUpdatedByAdmin;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Event;

class UserUpdateTest extends BaseUserTestCase
{
    private array $data = [
        'new_username' => 'new-username',
        'new_email' => 'new-email@test.com',
        'new_language' => 'en',
    ];

    public function test_guest_user_redirected_to_login_screen(): void
    {
        $response = $this->patchUsersUpdate($this->user, $this->data);

        $response->assertRedirect('/login');

        Event::assertNotDispatched(NotificationSent::class);
        Event::assertNotDispatched(UserUpdated::class);
    }

    public function test_user_redirected_back_when_they_are_not_an_admin(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->patchUsersUpdate($this->user, $this->data);

        $response->assertRedirect('/');

        Event::assertNotDispatched(NotificationSent::class);
        Event::assertNotDispatched(UserUpdated::class);
    }

    public function test_admin_user_cannot_update_an_existing_user_when_some_required_parameters_are_missing(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->patchUsersUpdate($this->user, [
                'new_username' => 'new-username',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrorsIn('updateUser', ['new_email', 'new_language']);

        $this->assertDatabaseMissing('users', [
            'username' => 'new-username',
        ]);

        Event::assertNotDispatched(NotificationSent::class);
        Event::assertNotDispatched(UserUpdated::class);
    }

    public function test_admin_user_can_update_an_existing_user_with_only_required_parameters(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->patchUsersUpdate($this->user, $this->data);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'username' => 'new-username',
            'email' => 'new-email@test.com',
            'email_verified_at' => null,
            'is_admin' => false,
            'is_enabled' => false,
        ]);

        Event::assertDispatched(
            NotificationSent::class,
            fn (NotificationSent $event) => $event->notification instanceof AccountUpdatedByAdmin,
        );

        Event::assertDispatched(
            UserUpdated::class,
            fn (UserUpdated $event) => $event->user->username === 'new-username',
        );
    }

    public function test_admin_user_can_update_an_existing_user_with_all_parameters(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->patchUsersUpdate($this->user, [
                ...$this->data,
                'new_password' => 'zaq1@WSX',
                'new_is_admin' => 'on',
                'new_is_enabled' => 'on',
                'new_is_verified' => 'on',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'username' => 'new-username',
            'email' => 'new-email@test.com',
            'email_verified_at' => $this->user->email_verified_at,
            'is_admin' => true,
            'is_enabled' => true,
        ]);

        Event::assertDispatched(
            NotificationSent::class,
            fn (NotificationSent $event) => $event->notification instanceof AccountUpdatedByAdmin,
        );

        Event::assertDispatched(
            UserUpdated::class,
            fn (UserUpdated $event) => $event->user->username === 'new-username',
        );
    }
}
