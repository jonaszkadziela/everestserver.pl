<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        if (Str::startsWith($this->name(), 'test_confirm_password_') === false) {
            $this->withoutMiddleware(RequirePassword::class);
        }
    }

    public function test_confirm_password_before_profile_page_is_displayed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/profile');

        $response->assertRedirect('/confirm-password');
    }

    public function test_profile_page_is_displayed(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_confirm_password_before_profile_information_can_be_updated(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->patch('/profile', [
                'username' => 'test_user',
                'email' => 'test@example.com',
            ]);

        $response->assertRedirect('/confirm-password');
    }

    public function test_profile_information_can_be_updated(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->patch('/profile', [
                'username' => 'test_user',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->user->refresh();

        $this->assertSame('test_user', $this->user->username);
        $this->assertSame('test@example.com', $this->user->email);
        $this->assertNull($this->user->email_verified_at);
    }

    public function test_email_verification_status_is_unchanged_when_the_email_address_is_unchanged(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->patch('/profile', [
                'username' => 'test_user',
                'email' => $this->user->email,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $this->assertNotNull($this->user->refresh()->email_verified_at);
    }

    public function test_confirm_password_before_user_can_delete_their_account(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response->assertRedirect('/confirm-password');
    }

    public function test_user_can_delete_their_account(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertNull($this->user->fresh());
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $response = $this
            ->actingAs($this->user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('deleteProfile', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($this->user->fresh());
    }
}
