<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\UpdatePasswordRequest;
use App\View\Components\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(UpdatePasswordRequest $request): RedirectResponse
    {
        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        Notification::push(
            Lang::get('notifications.in-app.password-updated.title'),
            Lang::get('notifications.in-app.password-updated.description'),
            Notification::SUCCESS,
        );

        return back()->with('status', 'password-updated');
    }
}
