<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class WebPageTest extends TestCase
{
    public function test_home_page_is_displayed(): void
    {
        $response = $this->get('/');

        $response->assertOk();
    }

    public function test_privacy_page_is_displayed(): void
    {
        $response = $this->get('/privacy');

        $response->assertOk();
    }

    public function test_dashboard_page_is_displayed_for_logged_in_user(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/dashboard');

        $response->assertOk();
    }

    public function test_dashboard_page_is_not_displayed_for_guests(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }
}
