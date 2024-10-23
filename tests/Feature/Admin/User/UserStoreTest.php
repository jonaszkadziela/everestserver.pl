<?php

namespace Tests\Feature\Admin\User;

use App\Events\UserCreated;
use App\Models\User;
use App\Notifications\AccountCreatedByAdmin;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;

class UserStoreTest extends BaseUserTestCase
{
    private array $data = [
        'username' => 'test',
        'email' => 'test@test.com',
        'language' => 'en',
    ];

    public function test_guest_user_redirected_to_login_screen(): void
    {
        $response = $this->postUsersStore($this->data);

        $response->assertRedirect('/login');

        Event::assertNotDispatched(NotificationSent::class);
        Event::assertNotDispatched(UserCreated::class);
    }

    public function test_user_redirected_back_when_they_are_not_an_admin(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->postUsersStore($this->data);

        $response->assertRedirect('/');

        Event::assertNotDispatched(NotificationSent::class);
        Event::assertNotDispatched(UserCreated::class);
    }

    public function test_admin_user_cannot_create_a_new_user_when_some_required_parameters_are_missing(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->postUsersStore([
                'username' => 'test',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrorsIn('createUser', ['email', 'language']);

        $this->assertDatabaseMissing('users', [
            'username' => 'test',
        ]);

        Event::assertNotDispatched(NotificationSent::class);
        Event::assertNotDispatched(UserCreated::class);
    }

    public function test_admin_user_can_create_a_new_user_with_only_required_parameters(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->postUsersStore($this->data);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'username' => 'test',
            'email' => 'test@test.com',
            'email_verified_at' => null,
            'is_admin' => false,
            'is_enabled' => false,
        ]);

        Event::assertDispatched(
            NotificationSent::class,
            fn (NotificationSent $event) => $event->notification instanceof AccountCreatedByAdmin,
        );

        Event::assertDispatched(
            UserCreated::class,
            fn (UserCreated $event) => $event->user->username === 'test',
        );
    }

    public function test_admin_user_can_create_a_new_user_with_all_parameters(): void
    {
        Carbon::setTestNow(Carbon::now());

        $response = $this
            ->actingAs($this->user)
            ->postUsersStore([
                ...$this->data,
                'password' => 'zaq1@WSX',
                'is_admin' => 'on',
                'is_enabled' => 'on',
                'is_verified' => 'on',
            ]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users', [
            'username' => 'test',
            'email' => 'test@test.com',
            'email_verified_at' => Carbon::now(),
            'is_admin' => true,
            'is_enabled' => true,
        ]);

        Event::assertDispatched(
            NotificationSent::class,
            fn (NotificationSent $event) => $event->notification instanceof AccountCreatedByAdmin,
        );

        Event::assertDispatched(
            UserCreated::class,
            fn (UserCreated $event) => $event->user->username === 'test',
        );

        Carbon::setTestNow();
    }
}
