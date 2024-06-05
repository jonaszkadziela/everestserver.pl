<?php

namespace App\Http\Middleware;

use App\View\Components\Notification;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        if ($request->expectsJson()) {
            return null;
        }

        $intendedUrl = $request->isMethod('GET') && $request->route()
            ? redirect()->getUrlGenerator()->full()
            : redirect()->getUrlGenerator()->previous();

        Notification::push(
            Lang::get('notifications.in-app.redirected-login.title'),
            Lang::get('notifications.in-app.redirected-login.description', ['url' => Str::limit($intendedUrl, 50)]),
            Notification::INFO,
        );

        return route('login');
    }
}
