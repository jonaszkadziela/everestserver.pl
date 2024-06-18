<?php

namespace App\Http\Controllers\ExternalAuth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\AccountCreatedViaProvider;
use App\Providers\RouteServiceProvider;
use App\View\Components\Notification;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class FacebookAuthController extends Controller
{
    public const PROVIDER = 'Facebook';

    /**
     * Redirect user to the provider's authorization page.
     */
    public function redirect(): RedirectResponse
    {
        if (empty(config('services.facebook.client_id')) || empty(config('services.facebook.client_secret'))) {
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
        $facebookUser = null;

        try {
            $facebookUser = Socialite::driver(self::PROVIDER)->user();

            $user = User::where('email', '=', $facebookUser->getEmail())
                ->when(
                    !empty($facebookUser->getNickname()),
                    fn (Builder $query) => $query->orWhere('username', '=', $facebookUser->getNickname())
                )
                ->first();

            if ($user === null) {
                $password = Str::password(16);

                $user = new User([
                    'username' => $facebookUser->getNickname() ?? Str::before($facebookUser->getEmail(), '@'),
                    'email' => $facebookUser->getEmail(),
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
        } catch (Throwable $t) {
            Notification::push(
                Lang::get('notifications.in-app.external-auth-failed.title'),
                Lang::get('notifications.in-app.external-auth-failed.description', ['provider' => self::PROVIDER]),
                Notification::DANGER,
            );

            Log::info(class_basename($this) . ': Authentication failed for ' . self::PROVIDER . ' user ' . $facebookUser?->getEmail() ?? request()->ip(), [
                'code' => $t->getCode(),
                'message' => $t->getMessage(),
                'file' => $t->getFile(),
                'line' => $t->getLine(),
            ]);
        }

        return redirect('/');
    }
}
