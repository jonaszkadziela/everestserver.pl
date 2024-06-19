<?php

namespace App\Http\Controllers\ExternalAuth;

class GoogleAuthController extends ExternalAuthController
{
    protected function getProviderName(): string
    {
        return 'Google';
    }
}
