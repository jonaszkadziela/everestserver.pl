<?php

namespace App\Http\Controllers\Admin;

use App\Events\UserCreated;
use App\Events\UserDeleted;
use App\Events\UserUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Http\Requests\Admin\IndexRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use App\Notifications\AccountCreatedByAdmin;
use App\Notifications\AccountUpdatedByAdmin;
use App\View\Components\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Get all users.
     */
    public function index(IndexRequest $request): LengthAwarePaginator
    {
        return User::select([
            'id',
            'username',
            'email',
            'email_verified_at',
            'is_admin',
            'is_enabled',
        ])
        ->when(
            $request->search !== null,
            fn (Builder $query) => $query->where(
                fn (Builder $query) => $query
                    ->where('id', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('username', 'LIKE', '%' . $request->search . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->search . '%')
            ),
        )
        ->when(
            $request->column !== null,
            fn (Builder $query) => $query->orderBy($request->column, $request->direction),
        )
        ->paginate(config('pagination.admin.users'));
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

        $user->notify(new AccountCreatedByAdmin($user->email, $password));
        event(new UserCreated($user));

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
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $previousLanguage = Lang::getLocale();

        if ($request->new_password !== null) {
            $user->password = $request->new_password;
        }

        $user->username = $request->new_username;
        $user->email = $request->new_email;
        $user->email_verified_at = $request->new_is_verified === 'on' ? ($user->email_verified_at ?? Carbon::now()) : null;
        $user->is_admin = $request->new_is_admin === 'on';
        $user->is_enabled = $request->new_is_enabled === 'on';

        $shouldNotify = $user->isDirty(['username', 'email', 'password']);

        $user->save();

        Lang::setLocale($request->new_language ?: config('app.locale'));

        if ($shouldNotify) {
            $user->notify(new AccountUpdatedByAdmin($user->username, $user->email, $request->new_password));
        }
        event(new UserUpdated($user));

        Lang::setLocale($previousLanguage);

        Notification::push(
            Lang::get('notifications.in-app.user-updated.title'),
            Lang::get('notifications.in-app.user-updated.description', ['user' => $user->username]),
            Notification::SUCCESS,
        );

        return redirect()->back();
    }

    /**
     * Delete an existing user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        event(new UserDeleted($user));

        Notification::push(
            Lang::get('notifications.in-app.user-deleted.title'),
            Lang::get('notifications.in-app.user-deleted.description', ['user' => $user->username]),
            Notification::SUCCESS,
        );

        return redirect()->back();
    }
}
