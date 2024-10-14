<?php

namespace Tests\Feature\ExternalAuth;

use App\Events\UserCreated;
use App\Models\User;
use App\Notifications\AccountCreatedViaProvider;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\FacebookProvider;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class FacebookAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.facebook.enabled', true);
        Str::createRandomStringsUsing(fn () => '6XRVFqGe42nIWRhrfNc84xlNlopjmt2NL3AihLmk');
    }

    private function mockFacebookProvider(array $userData): void
    {
        $mockFacebookProvider = Mockery::mock(FacebookProvider::class)
            ->makePartial()
            ->shouldReceive('user')
            ->andReturn((new SocialiteUser())->map($userData))
            ->getMock();

        Socialite::partialMock()->shouldReceive('createFacebookDriver')->andReturn($mockFacebookProvider);
    }

    public function test_authenticated_user_redirected_to_dashboard_page(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('external-auth.facebook'));

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_guest_not_redirected_to_facebook_authorization_page_when_service_is_disabled(): void
    {
        Config::set('services.facebook.enabled', false);

        $response = $this->get(route('external-auth.facebook'));

        $response->assertRedirect('/');
    }

    public function test_guest_redirected_to_facebook_authorization_page(): void
    {
        $response = $this->get(route('external-auth.facebook'));

        $response->assertRedirect(Socialite::driver('Facebook')->redirect()->getTargetUrl());
    }

    public function test_guest_cannot_create_account_via_facebook_when_username_is_already_taken(): void
    {
        Event::fake();

        User::factory()->create([
            'username' => 'mark-smith',
        ]);

        $facebookUser = [
            'id' => '1234567812345678',
            'nickname' => null,
            'name' => 'Mark Smith',
            'email' => 'mark-smith@test.com',
            'avatar' => 'https://graph.facebook.com/v3.3/1234567812345678/picture',
            'avatar_original' => 'https://graph.facebook.com/v3.3/1234567812345678/picture',
            'profileUrl' => null,
        ];

        $this->mockFacebookProvider($facebookUser);

        $response = $this->get(route('external-auth.facebook-callback'));

        $this->assertFalse($this->isAuthenticated());

        Event::assertDispatched(
            MessageLogged::class,
            fn (MessageLogged $event) =>
                $event->level === 'info' &&
                Str::endsWith($event->message, 'Authentication failed for Facebook user ' . $facebookUser['email']),
        );

        $response->assertRedirect('/');
    }

    public function test_guest_can_create_account_via_facebook(): void
    {
        Event::fake();

        $facebookUser = [
            'id' => '1234567812345678',
            'nickname' => null,
            'name' => 'John Doe',
            'email' => 'john-doe@test.com',
            'avatar' => 'https://graph.facebook.com/v3.3/1234567812345678/picture',
            'avatar_original' => 'https://graph.facebook.com/v3.3/1234567812345678/picture',
            'profileUrl' => null,
        ];

        $this->mockFacebookProvider($facebookUser);

        $response = $this->get(route('external-auth.facebook-callback'));

        $user = User::first();

        $this->assertSame(1, User::count());
        $this->assertSame(Str::before($facebookUser['email'], '@'), $user->username);
        $this->assertSame($facebookUser['email'], $user->email);
        $this->assertNotNull($user->email_verified_at);
        $this->assertNotNull($user->remember_token);
        $this->assertFalse($user->is_admin);

        Event::assertDispatched(
            NotificationSent::class,
            fn (NotificationSent $event) => $event->notification instanceof AccountCreatedViaProvider,
        );

        Event::assertDispatched(
            UserCreated::class,
            fn (UserCreated $event) => $event->user->id === $user->id,
        );

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_existing_user_can_login_via_facebook(): void
    {
        $user = User::factory()->create([
            'email' => 'john-doe@test.com',
        ]);

        $facebookUser = [
            'id' => '1234567812345678',
            'nickname' => null,
            'name' => 'John Doe',
            'email' => 'john-doe@test.com',
            'avatar' => 'https://graph.facebook.com/v3.3/1234567812345678/picture',
            'avatar_original' => 'https://graph.facebook.com/v3.3/1234567812345678/picture',
            'profileUrl' => null,
        ];

        $this->mockFacebookProvider($facebookUser);

        $response = $this->get(route('external-auth.facebook-callback'));

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
