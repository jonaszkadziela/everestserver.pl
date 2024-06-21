<?php

namespace Tests\Feature\ExternalAuth;

use App\Models\User;
use App\Notifications\AccountCreatedViaProvider;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Log\Events\MessageLogged;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\GoogleProvider;
use Laravel\Socialite\Two\User as SocialiteUser;
use Mockery;
use Tests\TestCase;

class GoogleAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('services.google.enabled', true);
        Str::createRandomStringsUsing(fn () => '6XRVFqGe42nIWRhrfNc84xlNlopjmt2NL3AihLmk');
    }

    private function mockGoogleProvider(array $userData): void
    {
        $mockGoogleProvider = Mockery::mock(GoogleProvider::class)
            ->makePartial()
            ->shouldReceive('user')
            ->andReturn((new SocialiteUser())->map($userData))
            ->getMock();

        Socialite::partialMock()->shouldReceive('createGoogleDriver')->andReturn($mockGoogleProvider);
    }

    public function test_authenticated_user_redirected_to_dashboard_page(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get(route('external-auth.google'));

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_guest_not_redirected_to_google_authorization_page_when_service_is_disabled(): void
    {
        Config::set('services.google.enabled', false);

        $response = $this->get(route('external-auth.google'));

        $response->assertRedirect('/');
    }

    public function test_guest_redirected_to_google_authorization_page(): void
    {
        $response = $this->get(route('external-auth.google'));

        $response->assertRedirect(Socialite::driver('Google')->redirect()->getTargetUrl());
    }

    public function test_guest_cannot_create_account_via_google_when_username_is_already_taken(): void
    {
        Event::fake();

        User::factory()->create([
            'username' => 'mark-smith',
        ]);

        $googleUser = [
            'id' => '123456781234567812345',
            'nickname' => null,
            'name' => 'Mark Smith',
            'email' => 'mark-smith@test.com',
            'avatar' => 'https://lh3.googleusercontent.com/a/4HaSgDFcvbsWEF346RgHg45tergdfjhg',
            'avatar_original' => 'https://lh3.googleusercontent.com/a/4HaSgDFcvbsWEF346RgHg45tergdfjhg',
        ];

        $this->mockGoogleProvider($googleUser);

        $response = $this->get(route('external-auth.google-callback'));

        $this->assertFalse($this->isAuthenticated());

        Event::assertDispatched(
            MessageLogged::class,
            fn (MessageLogged $event) =>
                $event->level === 'info' &&
                Str::endsWith($event->message, 'Authentication failed for Google user ' . $googleUser['email']),
        );

        $response->assertRedirect('/');
    }

    public function test_guest_can_create_account_via_google(): void
    {
        Event::fake();

        $googleUser = [
            'id' => '123456781234567812345',
            'nickname' => null,
            'name' => 'John Doe',
            'email' => 'john-doe@test.com',
            'avatar' => 'https://lh3.googleusercontent.com/a/4HaSgDFcvbsWEF346RgHg45tergdfjhg',
            'avatar_original' => 'https://lh3.googleusercontent.com/a/4HaSgDFcvbsWEF346RgHg45tergdfjhg',
        ];

        $this->mockGoogleProvider($googleUser);

        $response = $this->get(route('external-auth.google-callback'));

        $user = User::first();

        $this->assertSame(1, User::count());
        $this->assertSame(Str::before($googleUser['email'], '@'), $user->username);
        $this->assertSame($googleUser['email'], $user->email);
        $this->assertNotNull($user->email_verified_at);
        $this->assertNotNull($user->remember_token);
        $this->assertFalse($user->is_admin);

        Event::assertDispatched(
            NotificationSent::class,
            fn (NotificationSent $event) => $event->notification instanceof AccountCreatedViaProvider,
        );

        Event::assertDispatched(
            Registered::class,
            fn (Registered $event) => $event->user->id === $user->id,
        );

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(RouteServiceProvider::HOME);
    }

    public function test_existing_user_can_login_via_google(): void
    {
        $user = User::factory()->create([
            'email' => 'john-doe@test.com',
        ]);

        $googleUser = [
            'id' => '123456781234567812345',
            'nickname' => null,
            'name' => 'John Doe',
            'email' => 'john-doe@test.com',
            'avatar' => 'https://lh3.googleusercontent.com/a/4HaSgDFcvbsWEF346RgHg45tergdfjhg',
            'avatar_original' => 'https://lh3.googleusercontent.com/a/4HaSgDFcvbsWEF346RgHg45tergdfjhg',
        ];

        $this->mockGoogleProvider($googleUser);

        $response = $this->get(route('external-auth.google-callback'));

        $this->assertAuthenticatedAs($user);

        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
