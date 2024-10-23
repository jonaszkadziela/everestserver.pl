<?php

namespace Tests\Feature\Admin\User;

use App\Events\UserDeleted;
use App\Models\User;
use Illuminate\Support\Facades\Event;

class UserDestroyTest extends BaseUserTestCase
{
    public function test_guest_user_redirected_to_login_screen(): void
    {
        $response = $this->deleteUsersDestroy($this->user->id);

        $response->assertRedirect('/login');

        Event::assertNotDispatched(UserDeleted::class);
    }

    public function test_user_redirected_back_when_they_are_not_an_admin(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->deleteUsersDestroy($this->user->id);

        $response->assertRedirect('/');

        Event::assertNotDispatched(UserDeleted::class);
    }

    public function test_admin_user_cannot_delete_a_non_existing_user(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->deleteUsersDestroy(-1);

        $response->assertNotFound();

        $this->assertDatabaseHas('users', [
            'username' => $this->user->username,
        ]);

        Event::assertNotDispatched(UserDeleted::class);
    }

    public function test_admin_user_can_delete_an_existing_user(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->deleteUsersDestroy($this->user->id);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();

        $this->assertDatabaseMissing('users', [
            'username' => $this->user->username,
        ]);

        Event::assertDispatched(
            UserDeleted::class,
            fn (UserDeleted $event) => $event->user->username === $this->user->username,
        );
    }
}
