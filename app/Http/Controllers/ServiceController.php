<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use App\Services\EverestPass\EverestPassClient;
use App\View\Components\Notification;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Link service to user account.
     *
     * @throws AuthenticationException
     * @throws RequestException
     * @throws RuntimeException
     */
    public function link(Request $request): RedirectResponse
    {
        $request->validate([
            'serviceId' => 'required',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $pivotAttributes = [];

        try {
            $service = Service::enabled()
                ->where('id', '=', $request->serviceId)
                ->when(!$user?->is_admin, fn (Builder $query) => $query->where('is_public', '=', true))
                ->firstOrFail();

            $redirectLink = match ($service->name) {
                'EverestCloud' => $service->link . '/apps/sociallogin/custom_oauth2/everestserver',
                'EverestGit' => $service->link . '/-/profile/account',
                default => null,
            };

            if ($redirectLink !== null) {
                return redirect($redirectLink);
            }

            if ($service->name === 'EverestPass') {
                $response = $this->findOrCreateEverestPassAccount($service, $user);
                $pivotAttributes = [
                    'identifier' => $user->email,
                ];

                Log::info(
                    get_class($this) . (Str::startsWith($response['header']['message'], 'The user was successfully added')
                        ? ': EverestPass account created' : ': EverestPass account found'),
                    $response['header'],
                );
            }

            $user->services()->save($service, $pivotAttributes);

            Notification::push(
                Lang::get('notifications.in-app.service-linked.title'),
                Lang::get('notifications.in-app.service-linked.description', ['service' => $service->name]),
                Notification::SUCCESS,
            );
        } catch (Exception $e) {
            Notification::push(
                Lang::get('notifications.in-app.service-link-failed.title'),
                Lang::get('notifications.in-app.service-link-failed.description'),
                Notification::DANGER,
            );

            Log::info(
                get_class($this) . ': ' . $service->name . ' service could not be linked to user ' . $user->username,
                [$e->getMessage()],
            );
        }

        return redirect()->back();
    }

    /**
     * Find or create EverestPass account.
     *
     * @throws AuthenticationException
     * @throws RequestException
     * @throws RuntimeException
     */
    private function findOrCreateEverestPassAccount(Service $service, User $user): array
    {
        $client = new EverestPassClient($service->link);

        $response = $client->getUsers($user->email);

        if (count($response['body']) === 0) {
            $response = $client->createUser($user->email, $user->username, ' ');
        }

        return $response;
    }
}
