<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\View\Components\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        Notification::push(
            Lang::get('notifications.in-app.password-updated.title'),
            Lang::get('notifications.in-app.password-updated.description'),
            Notification::SUCCESS,
        );

        return back()->with('status', 'password-updated');
    }
}
