<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @throws UnauthorizedException
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user()?->is_admin) {
            throw new UnauthorizedException();
        }

        return $next($request);
    }
}
