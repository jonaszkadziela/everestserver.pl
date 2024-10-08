<?php

namespace App\Http\Middleware;

use App\View\Components\Notification;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @throws HttpException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->is_admin) {
            Notification::push(
                Lang::get('notifications.in-app.unauthorized.title'),
                Lang::get('notifications.in-app.unauthorized.description'),
                Notification::DANGER,
            );

            return $request->expectsJson() ? abort(Response::HTTP_UNAUTHORIZED, 'Unauthorized.') : redirect()->back();
        }

        return $next($request);
    }
}
