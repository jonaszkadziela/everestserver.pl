<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Providers\RouteServiceProvider;
use App\View\Components\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        Notification::push(
            Lang::get('notifications.in-app.profile-updated.title'),
            Lang::get('notifications.in-app.profile-updated.description'),
            Notification::SUCCESS,
        );

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => 'required|current_password',
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Notification::push(
            Lang::get('notifications.in-app.account-deleted.title'),
            Lang::get('notifications.in-app.account-deleted.description'),
            Notification::SUCCESS,
        );

        return Redirect::to('/');
    }

    /**
     * Display the account disabled page.
     */
    public function disabled(): RedirectResponse|View
    {
        if (Auth::user()->is_enabled) {
            return redirect()->to(RouteServiceProvider::HOME);
        }

        return view('profile.disabled');
    }
}
