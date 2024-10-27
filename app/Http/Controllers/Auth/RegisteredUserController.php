<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterUserRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\View\Components\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     */
    public function store(RegisterUserRequest $request): RedirectResponse
    {
        $user = new User($request->validated());

        $user->is_enabled = (bool)config('auth.new_users_enabled');
        $user->save();

        event(new UserCreated($user));

        Auth::login($user);

        Notification::push(
            Lang::get('notifications.in-app.registered.title'),
            Lang::get('notifications.in-app.registered.description'),
            Notification::SUCCESS,
        );

        return redirect(RouteServiceProvider::HOME);
    }
}
