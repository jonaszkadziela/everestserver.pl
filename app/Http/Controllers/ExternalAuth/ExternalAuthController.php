<?php

namespace App\Http\Controllers\ExternalAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountCreatedViaProvider;
use App\Providers\RouteServiceProvider;
use App\View\Components\Notification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

abstract class ExternalAuthController extends Controller
{
    /**
     * Redirect user to the provider's authorization page.
     */
    public function redirect(): RedirectResponse
    {
        if (!config(Str::lower('services.' . $this->getProviderName() . '.enabled'))) {
            Notification::push(
                Lang::get('notifications.in-app.external-auth-failed.title'),
                Lang::get('notifications.in-app.external-auth-failed.description', ['provider' => $this->getProviderName()]),
                Notification::DANGER,
            );

            return redirect()->back();
        }

        return Socialite::driver($this->getProviderName())->redirect();
    }

    /**
     * Process data retrieved from the provider.
     */
    public function callback(): RedirectResponse
    {
        $externalUser = null;

        try {
            $externalUser = Socialite::driver($this->getProviderName())->user();

            $user = User::where('email', '=', $externalUser->getEmail())->first();

            if ($user === null) {
                $password = Str::password(16);

                $user = new User([
                    'username' => $externalUser->getNickname() ?? Str::before($externalUser->getEmail(), '@'),
                    'email' => $externalUser->getEmail(),
                    'password' => $password,
                ]);

                $user->email_verified_at = Carbon::now();
                $user->save();

                $user->notify(new AccountCreatedViaProvider($this->getProviderName(), $user->email, $password));

                event(new Registered($user));
            }

            Auth::login($user, true);

            $notificationType = $user->wasRecentlyCreated ? 'registered' : 'logged-in';

            Notification::push(
                Lang::get('notifications.in-app.' . $notificationType . '.title'),
                Lang::get('notifications.in-app.' . $notificationType . '.description', ['username' => $user->username]),
                $notificationType === 'registered' ? Notification::SUCCESS : Notification::INFO,
            );

            return redirect(RouteServiceProvider::HOME);
        } catch (Throwable $t) {
            Notification::push(
                Lang::get('notifications.in-app.external-auth-failed.title'),
                Lang::get('notifications.in-app.external-auth-failed.description', ['provider' => $this->getProviderName()]),
                Notification::DANGER,
            );

            Log::info(class_basename($this) . ': Authentication failed for ' . $this->getProviderName() . ' user ' . $externalUser?->getEmail() ?? request()->ip(), [
                'code' => $t->getCode(),
                'message' => $t->getMessage(),
                'file' => $t->getFile(),
                'line' => $t->getLine(),
            ]);
        }

        return redirect('/');
    }

    /**
     * Get name of the Socialite provider.
     */
    abstract protected function getProviderName(): string;
}
