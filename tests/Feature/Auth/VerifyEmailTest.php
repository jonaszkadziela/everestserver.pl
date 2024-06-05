<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\VerifyEmail;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class VerifyEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_email_screen_can_be_rendered(): void
    {
        $user = User::factory()->unverified()->create();

        $response = $this->actingAs($user)->get('/verify-email');

        $response->assertStatus(200);
    }

    public function test_verify_email_notification_can_be_requested(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->post('/email/verification-notification');

        Notification::assertSentTo($user, VerifyEmail::class);
    }

    public function test_email_can_be_verified_with_valid_token(): void
    {
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)->post('/email/verification-notification');

        Notification::assertSentTo($user, VerifyEmail::class, function ($notification) use ($user) {
            $response = $this->get($notification->toMail($user)->actionUrl);

            $response->assertSessionHasNoErrors();
            $response->assertRedirect(RouteServiceProvider::HOME);

            $this->assertNotNull($user->email_verified_at);

            return true;
        });
    }
}
