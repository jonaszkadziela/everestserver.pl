<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Torann\GeoIP\Facades\GeoIP;

class DetermineLanguageFromIp
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('language') === false) {
            $location = GeoIP::getLocation($request->ip());

            $code = match ($location['iso_code']) {
                'PL' => 'pl',
                default => 'en',
            };

            Session::put('language', $code);
        }

        return $next($request);
    }
}
