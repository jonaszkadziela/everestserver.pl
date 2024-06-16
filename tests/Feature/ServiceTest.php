<?php

namespace Tests\Feature;

use App\Models\Service;
use App\Models\User;
use App\Services\EverestPass\EverestPassClient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Service $service;

    protected function setUp(): void
    {
        parent::setUp();

        Service::truncate();

        $this->user = User::factory()->create();
        $this->service = Service::factory()->create();
    }

    private function postLinkService(int $serviceId): TestResponse
    {
        return $this->post(route('services.link', [
            'serviceId' => $serviceId,
        ]));
    }

    public function test_guest_user_redirected_to_login_screen(): void
    {
        $response = $this->postLinkService($this->service->id);

        $response->assertRedirect('/login');
    }

    public function test_user_cannot_link_disabled_service(): void
    {
        Event::fake();

        $this->service->is_enabled = false;
        $this->service->save();

        $this->actingAs($this->user);

        $response = $this->postLinkService($this->service->id);

        $response->assertRedirect('/');

        Event::assertDispatched(
            MessageLogged::class,
            fn (MessageLogged $event) =>
                $event->level === 'info' &&
                Str::endsWith($event->message, 'The requested service could not be linked to user ' . $this->user->username),
        );
    }

    public function test_user_cannot_link_private_service_when_they_are_not_an_admin(): void
    {
        Event::fake();

        $this->service->is_public = false;
        $this->service->save();

        $this->actingAs($this->user);

        $response = $this->postLinkService($this->service->id);

        $response->assertRedirect('/');

        Event::assertDispatched(
            MessageLogged::class,
            fn (MessageLogged $event) =>
                $event->level === 'info' &&
                Str::endsWith($event->message, 'The requested service could not be linked to user ' . $this->user->username),
        );
    }

    public function test_user_can_link_basic_service(): void
    {
        $this->actingAs($this->user);

        $response = $this->postLinkService($this->service->id);

        $response->assertRedirect('/');

        $this->assertSame($this->service->id, $this->user->services()->first()->id);
    }

    public function test_user_can_link_private_service_when_they_are_an_admin(): void
    {
        $this->user->is_admin = true;
        $this->user->save();

        $this->service->is_public = false;
        $this->service->save();

        $this->actingAs($this->user);

        $response = $this->postLinkService($this->service->id);

        $response->assertRedirect('/');

        $this->assertSame($this->service->id, $this->user->services()->first()->id);
    }

    public function test_user_can_link_everest_cloud_service(): void
    {
        $this->service->name = 'EverestCloud';
        $this->service->save();

        $this->actingAs($this->user);

        $response = $this->postLinkService($this->service->id);

        $response->assertRedirect($this->service->link . '/apps/sociallogin/custom_oauth2/everestserver');
    }

    public function test_user_can_link_everest_git_service(): void
    {
        $this->service->name = 'EverestGit';
        $this->service->save();

        $this->actingAs($this->user);

        $response = $this->postLinkService($this->service->id);

        $response->assertRedirect($this->service->link . '/-/profile/account');
    }

    public function test_user_can_link_everest_pass_service_when_they_already_have_an_account(): void
    {
        Event::fake();

        Http::preventStrayRequests();
        Http::fake([
            'https://pass.everestserver.test/users.json*' => Http::response([
                'header' => [
                    'action' => 'd7bc9044-a64e-5421-a4d7-7a94eaa39d37',
                    'code' => Response::HTTP_OK,
                    'id' => 'e0d97447-5c53-4b32-a1b2-fc845b297233',
                    'message' => 'The operation was successful.',
                    'pagination' => [
                        'count' => 1,
                        'limit' => null,
                        'page' => 1
                    ],
                    'servertime' => 1718455537,
                    'status' => 'success',
                    'url' => '/users.json?filter%5Bsearch%5D=test%40test.com',
                ],
                'body' => [
                    'id' => 'ea1b1541-cce2-4b51-8df6-ea38b86845be',
                    'username' => 'test@test.com',
                ],
            ], Response::HTTP_OK),
        ]);

        Config::set('services.everestpass.private_key.path', storage_path('everestpass-private.key.test'));
        Config::set('services.everestpass.private_key.passphrase', 'zaq1@WSX');

        Cache::set(EverestPassClient::SESSION_CACHE_KEY, Str::random(26), 600);
        Cache::set(EverestPassClient::CSRF_TOKEN_CACHE_KEY, Str::random(128), 600);

        $this->service->name = 'EverestPass';
        $this->service->link = 'https://pass.everestserver.test';
        $this->service->save();

        $this->actingAs($this->user);

        $response = $this->postLinkService($this->service->id);

        $response->assertRedirect('/');

        $this->assertSame($this->service->id, $this->user->services()->first()->id);
        $this->assertSame($this->user->email, $this->user->services()->first()->pivot->identifier);

        Event::assertDispatched(
            MessageLogged::class,
            fn (MessageLogged $event) => $event->level === 'info' && Str::endsWith($event->message, 'EverestPass account found'),
        );
    }

    public function test_user_can_link_everest_pass_service_when_they_do_not_have_an_account_yet(): void
    {
        Event::fake();

        Http::preventStrayRequests();
        Http::fake([
            'https://pass.everestserver.test/users.json*' => Http::sequence()
                ->push([
                    'header' => [
                        'action' => 'd7bc9044-a64e-5421-a4d7-7a94eaa39d37',
                        'code' => Response::HTTP_OK,
                        'id' => 'e0d97447-5c53-4b32-a1b2-fc845b297233',
                        'message' => 'The operation was successful.',
                        'pagination' => [
                            'count' => 0,
                            'limit' => null,
                            'page' => 1,
                        ],
                        'servertime' => 1718455537,
                        'status' => 'success',
                        'url' => '/users.json?filter%5Bsearch%5D=test%40test.com',
                    ],
                    'body' => [],
                ], Response::HTTP_OK)
                ->push([
                    'header' => [
                        'action' => 'a1a15b91-72f6-5708-8d7f-6940e51d8595',
                        'code' => Response::HTTP_OK,
                        'id' => 'ba3771c4-b343-4c27-8e62-6a606d2af9ff',
                        'message' => 'The user was successfully added. This user now need to complete the setup.',
                        'servertime' => 1718456839,
                        'status' => 'success',
                        'url' => '/users.json',
                    ],
                    'body' => [
                        'id' => '4e1a73f8-9299-4f01-be5c-a9d608b21c93',
                        'username' => 'test@test.com',
                    ],
                ], Response::HTTP_OK),
        ]);

        Config::set('services.everestpass.private_key.path', storage_path('everestpass-private.key.test'));
        Config::set('services.everestpass.private_key.passphrase', 'zaq1@WSX');

        Cache::set(EverestPassClient::SESSION_CACHE_KEY, Str::random(26), 600);
        Cache::set(EverestPassClient::CSRF_TOKEN_CACHE_KEY, Str::random(128), 600);

        $this->service->name = 'EverestPass';
        $this->service->link = 'https://pass.everestserver.test';
        $this->service->save();

        $this->actingAs($this->user);

        $response = $this->postLinkService($this->service->id);

        $response->assertRedirect('/');

        $this->assertSame($this->service->id, $this->user->services()->first()->id);
        $this->assertSame($this->user->email, $this->user->services()->first()->pivot->identifier);

        Event::assertDispatched(
            MessageLogged::class,
            fn (MessageLogged $event) => $event->level === 'info' && Str::endsWith($event->message, 'EverestPass account created'),
        );
    }
}
