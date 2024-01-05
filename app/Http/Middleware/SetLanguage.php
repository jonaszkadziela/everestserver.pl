<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLanguage
{
    public function handle(Request $request, Closure $next): mixed
    {
        App::setLocale(Session::get('language', config('app.locale')));

        return $next($request);
    }
}
