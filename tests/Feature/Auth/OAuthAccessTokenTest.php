<?php

namespace Tests\Feature\Auth;

use App\Listeners\LinkServiceToUser;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Illuminate\Testing\TestResponse;
use Laravel\Passport\Client;
use Laravel\Passport\Events\AccessTokenCreated;
use Tests\TestCase;

class OAuthAccessTokenTest extends TestCase
{
    private Client $oAuthClient;
    private string $state;

    protected function setUp(): void
    {
        parent::setUp();

        $this->oAuthClient = Client::create([
            'name' => 'Test',
            'secret' => 'BRyA136tCZiPj76CFxlvNm64LifBP2wSLnnYHFxM',
            'redirect' => 'http://everestserver.test/auth/callback',
            'personal_access_client' => 0,
            'password_client' => 0,
            'revoked' => 0,
        ]);

        $this->state = Str::random(40);
    }

    private function getAuthorizePage(): TestResponse
    {
        return $this->get(route('passport.authorizations.authorize', [
            'client_id' => $this->oAuthClient->id,
            'redirect_uri' => $this->oAuthClient->redirect,
            'response_type' => 'code',
            'scope' => 'email openid',
            'state' => $this->state,
        ]));
    }

    public function test_guest_user_redirected_to_login_screen(): void
    {
        $response = $this->getAuthorizePage();

        $response->assertRedirect('/login');
    }

    public function test_authorization_request_screen_shown_during_initial_setup(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->getAuthorizePage();

        $response
            ->assertOk()
            ->assertViewIs('passport::authorize');
    }

    public function test_user_redirected_to_redirect_uri_with_error_access_denied_after_declining_authorization_request(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getAuthorizePage();
        $authToken = Str::betweenFirst($response->getContent(), 'name="auth_token" value="', '">');

        $response = $this->delete(route('passport.authorizations.deny'), [
            'state' => $this->state,
            'client_id' => $this->oAuthClient->id,
            'auth_token' => $authToken,
        ]);

        $response->assertRedirect(
            $this->oAuthClient->redirect .
            '?state=' . $this->state .
            '&error=access_denied' .
            '&error_description=The+resource+owner+or+authorization+server+denied+the+request.' .
            '&hint=The+user+denied+the+request' .
            '&message=The+resource+owner+or+authorization+server+denied+the+request.'
        );
    }

    public function test_user_redirected_to_redirect_uri_with_code_after_approving_authorization_request(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getAuthorizePage();
        $authToken = Str::betweenFirst($response->getContent(), 'name="auth_token" value="', '">');

        $response = $this->post(route('passport.authorizations.approve'), [
            'state' => $this->state,
            'client_id' => $this->oAuthClient->id,
            'auth_token' => $authToken,
        ]);

        $response
            ->assertRedirectContains($this->oAuthClient->redirect . '?code=')
            ->assertRedirectContains('&state=' . $this->state);
    }

    public function test_user_can_create_access_token(): void
    {
        Event::fake();

        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getAuthorizePage();
        $authToken = Str::betweenFirst($response->getContent(), 'name="auth_token" value="', '">');

        $response = $this->post(route('passport.authorizations.approve'), [
            'state' => $this->state,
            'client_id' => $this->oAuthClient->id,
            'auth_token' => $authToken,
        ]);
        $code = Str::betweenFirst($response->headers->get('Location'), 'code=', '&state');

        $response = $this->post(route('passport.token'), [
            'grant_type' => 'authorization_code',
            'client_id' => $this->oAuthClient->id,
            'client_secret' => $this->oAuthClient->secret,
            'redirect_uri' => $this->oAuthClient->redirect,
            'code' => $code,
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'token_type',
                'expires_in',
                'access_token',
                'refresh_token',
            ]);

        Event::assertDispatched(AccessTokenCreated::class);
        Event::assertListening(AccessTokenCreated::class, LinkServiceToUser::class);
    }

    public function test_service_linked_to_user_after_access_token_is_created(): void
    {
        $user = User::factory()->create();
        $service = Service::factory()->create(['name' => 'Test']);

        $this->actingAs($user);

        $response = $this->getAuthorizePage();
        $authToken = Str::betweenFirst($response->getContent(), 'name="auth_token" value="', '">');

        $response = $this->post(route('passport.authorizations.approve'), [
            'state' => $this->state,
            'client_id' => $this->oAuthClient->id,
            'auth_token' => $authToken,
        ]);
        $code = Str::betweenFirst($response->headers->get('Location'), 'code=', '&state');

        $response = $this->post(route('passport.token'), [
            'grant_type' => 'authorization_code',
            'client_id' => $this->oAuthClient->id,
            'client_secret' => $this->oAuthClient->secret,
            'redirect_uri' => $this->oAuthClient->redirect,
            'code' => $code,
        ]);

        $this->assertSame($service->id, $user->services()->first()->id);
    }
}
