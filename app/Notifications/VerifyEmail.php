<?php

namespace App\Notifications;

use App\Notifications\Traits\HasTranslations;
use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends BaseVerifyEmail
{
    use HasTranslations;

    protected array $lang = [
        'en' => [
            'action' => 'Verify email address',
            'line-1' => 'Please click the button below to verify your email address.',
            'line-2' => 'If you do not verify your email address, you cannot access any services.',
            'subject' => 'EverestServer - Verify email address',
        ],
        'pl' => [
            'action' => 'Zweryfikuj adres e-mail',
            'line-1' => 'Aby zweryfikować swój adres e-mail, kliknij przycisk poniżej.',
            'line-2' => 'Jeśli nie zweryfikujesz swojego adresu e-mail, nie będziesz mieć dostępu do żadnych usług.',
            'subject' => 'EverestServer - Zweryfikuj adres e-mail',
        ],
    ];

    /**
     * Get the verify email notification mail message for the given URL.
     *
     * @param string $url
     */
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage())
            ->subject($this->lang('subject'))
            ->line($this->lang('line-1') . ' ' . $this->lang('line-2'))
            ->action($this->lang('action'), $url);
    }
}
