<?php

namespace App\Listeners;

use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Passport\Client;
use Laravel\Passport\Events\AccessTokenCreated;
use Laravel\Passport\Token;

class LinkServiceToUser
{
    /**
     * Handle the event.
     */
    public function handle(AccessTokenCreated $event): void
    {
        $eventArray = [
            'clientId' => $event->clientId,
            'tokenId' => Str::substr($event->tokenId, 0, 5) . '...',
            'userId' => $event->userId,
        ];

        $oAuthClient = Client::find($event->clientId);
        $user = User::find($event->userId);

        if ($oAuthClient === null || $user === null) {
            Log::info(class_basename($this) . ': ' . ($user === null ? 'User' : 'OAuth Client') . ' not found', $eventArray);
            return;
        }

        $service = Service::where('name', '=', $oAuthClient->name)->first();

        if ($service === null) {
            Log::info(class_basename($this) . ': Service not found', $eventArray);
            return;
        }

        $oAuthAccessToken = Token::find($event->tokenId);

        if ($oAuthAccessToken === null) {
            Log::info(class_basename($this) . ': OAuth Access Token not found', $eventArray);
            return;
        }

        $linkedService = $user
            ->services()
            ->where('service_id', '=', $service->id)
            ->first();

        if ($linkedService !== null) {
            Log::debug(class_basename($this) . ': Service already linked', $eventArray);
            return;
        }

        $user->services()->save($service, ['identifier' => $oAuthAccessToken->name]);
    }
}
