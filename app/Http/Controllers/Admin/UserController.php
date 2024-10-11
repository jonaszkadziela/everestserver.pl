<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Notifications\AccountCreatedViaCommand;
use App\View\Components\Notification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Get all users.
     */
    public function index(): Collection
    {
        return User::select([
            'id',
            'username',
            'email',
            'email_verified_at',
            'is_admin',
            'is_enabled',
        ])
        ->get();
    }

    /**
     * Create a new user.
     */
    public function store(CreateUserRequest $request): RedirectResponse
    {
        $password = $request->password ?: Str::password(16);
        $previousLanguage = Lang::getLocale();

        $user = new User();
        $user->username = $request->username;
        $user->email = $request->email;
        $user->email_verified_at = $request->is_verified ? Carbon::now() : null;
        $user->password = $password;
        $user->is_admin = (bool)$request->is_admin;
        $user->is_enabled = (bool)$request->is_enabled;
        $user->save();

        Lang::setLocale($request->language ?: config('app.locale'));

        $user->notify(new AccountCreatedViaCommand($user->email, $password));
        event(new Registered($user));

        Lang::setLocale($previousLanguage);

        Notification::push(
            Lang::get('notifications.in-app.user-added.title'),
            Lang::get('notifications.in-app.user-added.description', ['user' => $user->username]),
            Notification::SUCCESS,
        );

        return redirect()->back();
    }

    /**
     * Update an existing user.
     */
    public function update(User $user, UpdateUserRequest $request): RedirectResponse
    {
        $previousLanguage = Lang::getLocale();
        $validated = $request->validated();

        if ($validated['password'] === null) {
            $validated = Arr::except($validated, 'password');
        }

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->is_admin = Arr::get($validated, 'is_admin') === 'on';
        $user->is_enabled = Arr::get($validated, 'is_enabled') === 'on';
        $user->email_verified_at = Arr::get($validated, 'is_verified') === 'on' ? ($user->email_verified_at ?? Carbon::now()) : null;

        $user->save();

        Lang::setLocale($request->language ?: config('app.locale'));

        if (Arr::has($validated, 'password')) {
            $user->notify(new AccountCreatedViaCommand($user->email, $validated['password']));
        }
        event(new Registered($user));

        Lang::setLocale($previousLanguage);

        Notification::push(
            Lang::get('notifications.in-app.user-updated.title'),
            Lang::get('notifications.in-app.user-updated.description', ['user' => $user->username]),
            Notification::SUCCESS,
        );

        return redirect()->back();
    }
}
