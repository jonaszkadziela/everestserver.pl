<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\View\Components\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        Notification::push(
            Lang::get('notifications.in-app.redirected-password.title'),
            Lang::get('notifications.in-app.redirected-password.description', ['url' => Session::get('url.intended')]),
            Notification::INFO,
        );

        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        $validCredentials = Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ]);

        if (!$validCredentials) {
            throw ValidationException::withMessages([
                'password' => Lang::get('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
