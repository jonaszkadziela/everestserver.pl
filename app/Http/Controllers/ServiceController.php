<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\User;
use App\View\Components\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class ServiceController extends Controller
{
    /**
     * Link service to user account.
     */
    public function link(Request $request): RedirectResponse
    {
        $request->validate([
            'serviceId' => 'required',
        ]);

        /** @var User $user */
        $user = Auth::user();
        $service = Service::enabled()
            ->where('id', '=', $request->serviceId)
            ->when(!$user?->is_admin, fn (Builder $query) => $query->where('is_public', '=', true))
            ->first();

        if ($user === null || $service === null) {
            Notification::push(
                Lang::get('notifications.in-app.service-link-failed.title'),
                Lang::get('notifications.in-app.service-link-failed.description'),
                Notification::DANGER,
            );

            return redirect()->back();
        }

        $redirectLink = match ($service->name) {
            'EverestCloud' => $service->link . '/apps/sociallogin/custom_oauth2/everestserver',
            'EverestGit' => $service->link . '/-/profile/account',
            default => null,
        };

        if ($redirectLink !== null) {
            return redirect($redirectLink);
        }

        $user->services()->save($service);

        Notification::push(
            Lang::get('notifications.in-app.service-linked.title'),
            Lang::get('notifications.in-app.service-linked.description', ['service' => $service->name]),
            Notification::SUCCESS,
        );

        return redirect()->back();
    }
}
