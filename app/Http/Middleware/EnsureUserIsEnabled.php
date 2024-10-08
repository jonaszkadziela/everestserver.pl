<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class EnsureUserIsEnabled
{
    /**
     * Handle an incoming request.
     *
     * @throws HttpException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && !$request->user()->is_enabled) {
            return $request->expectsJson() ? abort(Response::HTTP_FORBIDDEN, 'Your account is disabled.') : redirect()->route('profile.disabled');
        }

        return $next($request);
    }
}
