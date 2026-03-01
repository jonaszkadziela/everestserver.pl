<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Torann\GeoIP\Facades\GeoIP;

class DetermineLanguageFromIp
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('language') === false) {
            $location = GeoIP::getLocation($request->ip());
            $countryCode = Arr::get($location, 'iso_code') ?? Arr::get($location, 'country_code2');

            $language = match ($countryCode) {
                'PL' => 'pl',
                default => 'en',
            };

            Session::put('language', $language);
        }

        return $next($request);
    }
}
