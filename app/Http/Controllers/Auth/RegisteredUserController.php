<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use App\View\Components\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
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
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string|lowercase|alpha_dash:ascii|min:3|max:20|unique:' . User::class,
            'email' => 'required|string|lowercase|email|max:255|unique:' . User::class,
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = new User([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
        ]);

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
