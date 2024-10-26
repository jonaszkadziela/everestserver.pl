<?php

namespace Tests\Feature\Admin\User;

use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class BaseUserTestCase extends TestCase
{
    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->admin()->create();

        Event::fake();
    }

    protected function getAdminPanelForUsers(?string $column = null, ?string $direction = null, ?string $search = null): TestResponse
    {
        return $this->get(route('admin.panel', [
            'tab' => 'users',
            'column' => $column,
            'direction' => $direction,
            'search' => $search,
        ]));
    }

    protected function postUsersStore(array $data): TestResponse
    {
        return $this->post(route('users.store'), $data);
    }

    protected function patchUsersUpdate(int $userId, array $data): TestResponse
    {
        return $this->patch(route('users.update', [
            'user' => $userId,
        ]), $data);
    }

    protected function deleteUsersDestroy(int $userId): TestResponse
    {
        return $this->delete(route('users.destroy', [
            'user' => $userId,
        ]));
    }

    protected function assertSeeUser(TestResponse $response, User $user): TestResponse
    {
        return $response->assertSeeTextInOrder([
            $user->id,
            $user->username,
            $user->email,
            $user->email_verified_at?->toDateTimeString() ?? '-',
            $user->is_admin ? Lang::get('main.yes') : Lang::get('main.no'),
            $user->is_enabled ? Lang::get('main.yes') : Lang::get('main.no'),
        ]);
    }
}
