<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Lang;
use Tests\TestCase;

class LanguageTest extends TestCase
{
    public function test_language_can_be_changed_to_english(): void
    {
        Lang::setLocale('pl');

        $response = $this->get('/language/en');

        $response->assertRedirect();
        $response->assertSessionHas('language', 'en');
    }

    public function test_language_can_be_changed_to_polish(): void
    {
        Lang::setLocale('en');

        $response = $this->get('/language/pl');

        $response->assertRedirect();
        $response->assertSessionHas('language', 'pl');
    }
}
