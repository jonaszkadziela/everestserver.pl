<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Models\User;
use App\Notifications\AccountCreatedViaCommand;
use App\View\Components\Notification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Get all users.
     */
    public function index(): array
    {
        return User::select([
            'id',
            'username',
            'email',
            'email_verified_at',
            'is_admin',
            'is_enabled',
        ])
        ->get()
        ->map(function (User $user) {
            return [
                ...$user->getAttributes(),
                'email_verified_at' => $user->email_verified_at?->toDateTimeString() ?? '-',
                'is_admin' => $user->is_admin ? Lang::get('main.yes') : Lang::get('main.no'),
                'is_enabled' => $user->is_enabled ? Lang::get('main.yes') : Lang::get('main.no'),
            ];
        })
        ->toArray();
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
        Lang::setLocale($previousLanguage);

        event(new Registered($user));

        Notification::push(
            Lang::get('notifications.in-app.user-added.title'),
            Lang::get('notifications.in-app.user-added.description', ['user' => $user->username]),
            Notification::SUCCESS,
        );

        return redirect()->back();
    }
}
