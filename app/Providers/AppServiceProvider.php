<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (App::isProduction()) {
            URL::forceScheme('https');
        }

        Passport::tokensCan([
            'email' => 'auth.scopes.email',
            'openid' => 'auth.scopes.openid',
            'profile' => 'auth.scopes.profile',
            'user' => 'auth.scopes.user',
        ]);
    }
}
