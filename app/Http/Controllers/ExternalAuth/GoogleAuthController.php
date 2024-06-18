<?php

namespace App\Http\Controllers\ExternalAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountCreatedViaProvider;
use App\Providers\RouteServiceProvider;
use App\View\Components\Notification;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public const PROVIDER = 'Google';

    /**
     * Redirect user to the provider's authorization page.
     */
    public function redirect(): RedirectResponse
    {
        if (empty(config('services.google.client_id')) || empty(config('services.google.client_secret'))) {
            Notification::push(
                Lang::get('notifications.in-app.external-auth-failed.title'),
                Lang::get('notifications.in-app.external-auth-failed.description', ['provider' => self::PROVIDER]),
                Notification::DANGER,
            );

            return redirect()->back();
        }

        return Socialite::driver(self::PROVIDER)->redirect();
    }

    /**
     * Process data retrieved from the provider.
     */
    public function callback(): RedirectResponse
    {
        $googleUser = null;

        try {
            $googleUser = Socialite::driver(self::PROVIDER)->user();
            $user = User::where('email', '=', $googleUser->getEmail())
                ->when(
                    !empty($googleUser->getNickname()),
                    fn (Builder $query) => $query->orWhere('username', '=', $googleUser->getNickname())
                )
                ->first();

            if ($user === null) {
                $password = Str::password(16);

                $user = new User([
                    'username' => $googleUser->getNickname() ?? Str::before($googleUser->getEmail(), '@'),
                    'email' => $googleUser->getEmail(),
                    'password' => $password,
                ]);

                $user->email_verified_at = Carbon::now();
                $user->save();

                $user->notify(new AccountCreatedViaProvider(self::PROVIDER, $user->email, $password));
            }

            Auth::login($user, true);

            $notificationType = $user->wasRecentlyCreated ? 'registered' : 'logged-in';

            Notification::push(
                Lang::get('notifications.in-app.' . $notificationType . '.title'),
                Lang::get('notifications.in-app.' . $notificationType . '.description', ['username' => $user->username]),
                $notificationType === 'registered' ? Notification::SUCCESS : Notification::INFO,
            );

            return redirect(RouteServiceProvider::HOME);
        } catch (Exception $e) {
            Notification::push(
                Lang::get('notifications.in-app.external-auth-failed.title'),
                Lang::get('notifications.in-app.external-auth-failed.description', ['provider' => self::PROVIDER]),
                Notification::DANGER,
            );

            Log::info(
                get_class($this) . ': Authentication failed for ' . self::PROVIDER . ' user ' . $googleUser?->getEmail() ?? request()->ip(),
                [$e->getMessage()],
            );
        }

        return redirect('/');
    }
}
