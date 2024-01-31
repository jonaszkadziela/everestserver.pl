<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class VerifyEmail extends BaseVerifyEmail
{
    public const LANG = [
        'pl' => [
            'action' => 'Zweryfikuj adres e-mail',
            'line-1' => 'Aby zweryfikować swój adres e-mail, kliknij przycisk poniżej.',
            'line-2' => 'Jeśli nie utworzyłeś konta, zignoruj tę wiadomość.',
            'subject' => 'EverestServer - Zweryfikuj adres e-mail',
        ],
        'en' => [
            'action' => 'Verify email address',
            'line-1' => 'Please click the button below to verify your email address.',
            'line-2' => 'If you did not create an account, you can ignore this message.',
            'subject' => 'EverestServer - Verify email address',
        ],
    ];

    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param string $url
     * @return MailMessage
     */
    protected function buildMailMessage($url)
    {
        return (new MailMessage())
            ->subject(self::LANG[Lang::getLocale()]['subject'])
            ->line(self::LANG[Lang::getLocale()]['line-1'] . ' ' . self::LANG[Lang::getLocale()]['line-2'])
            ->action(self::LANG[Lang::getLocale()]['action'], $url);
    }
}
