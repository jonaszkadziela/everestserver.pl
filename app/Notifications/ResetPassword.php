<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

class ResetPassword extends BaseResetPassword
{
    public const LANG = [
        'pl' => [
            'action' => 'Zresetuj hasło',
            'line-1' => 'Otrzymujesz tego e-maila, ponieważ otrzymaliśmy prośbę o zresetowanie hasła do Twojego konta.',
            'line-2' => 'Link do resetowania hasła wygaśnie za :count minut.',
            'line-3' => 'Jeśli nie poprosiłeś o zresetowanie hasła, zignoruj tę wiadomość.',
            'subject' => 'EverestServer - Zresetuj hasło',
        ],
        'en' => [
            'action' => 'Reset password',
            'line-1' => 'You are receiving this email because we received a password reset request for your account.',
            'line-2' => 'This password reset link will expire in :count minutes.',
            'line-3' => 'If you did not request a password reset, you can ignore this message.',
            'subject' => 'EverestServer - Reset password',
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
            ->line(self::LANG[Lang::getLocale()]['line-1'])
            ->action(self::LANG[Lang::getLocale()]['action'], $url)
            ->line(Str::replace(':count', config('auth.passwords.' . config('auth.defaults.passwords') . '.expire'), self::LANG[Lang::getLocale()]['line-2']))
            ->line(self::LANG[Lang::getLocale()]['line-3']);
    }
}
