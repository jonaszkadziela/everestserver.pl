<?php

namespace App\Notifications;

use App\Notifications\Traits\HasTranslations;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends BaseResetPassword
{
    use HasTranslations;

    protected array $lang = [
        'en' => [
            'action' => 'Reset password',
            'line-1' => 'You are receiving this email because we received a password reset request for your account.',
            'line-2' => 'This password reset link will expire in :count minutes.',
            'line-3' => 'If you did not request a password reset, you can ignore this message.',
            'subject' => 'EverestServer - Reset password',
        ],
        'pl' => [
            'action' => 'Zresetuj hasło',
            'line-1' => 'Otrzymujesz tego e-maila, ponieważ otrzymaliśmy prośbę o zresetowanie hasła do Twojego konta.',
            'line-2' => 'Link do resetowania hasła wygaśnie za :count minut.',
            'line-3' => 'Jeśli nie poprosiłeś o zresetowanie hasła, zignoruj tę wiadomość.',
            'subject' => 'EverestServer - Zresetuj hasło',
        ],
    ];

    /**
     * Create a notification instance.
     */
    public function __construct(string $token)
    {
        parent::__construct($token);

        $this->placeholdersMap = [
            ':count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire'),
        ];
    }

    /**
     * Get the reset password notification mail message for the given URL.
     *
     * @param string $url
     */
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage())
            ->subject($this->lang('subject'))
            ->line($this->lang('line-1'))
            ->action($this->lang('action'), $url)
            ->line($this->lang('line-2'))
            ->line($this->lang('line-3'));
    }
}
