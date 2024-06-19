<?php

namespace App\Http\Controllers\ExternalAuth;

class FacebookAuthController extends ExternalAuthController
{
    protected function getProviderName(): string
    {
        return 'Facebook';
    }
}
