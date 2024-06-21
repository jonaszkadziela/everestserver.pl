<?php

namespace App\Notifications;

use App\Notifications\Traits\HasTranslations;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountCreatedViaCommand extends Notification
{
    use HasTranslations;

    protected array $lang = [
        'en' => [
            'action' => 'Log in your account',
            'line-1' => 'Welcome to EverestServer! An account has been created for you by the administrator.',
            'line-2' => 'We generated the **:password** password for you. For safety reasons, please change it as soon as possible.',
            'line-3' => 'You can access your account using the **:email** email address and the password mentioned above.',
            'line-4' => 'If you did not create an account, you can ignore this message.',
            'subject' => 'EverestServer - Account created',
        ],
        'pl' => [
            'action' => 'Zaloguj się na swoje konto',
            'line-1' => 'Witamy w EverestServer! Twoje konto zostało utworzone przez administratora.',
            'line-2' => 'Wygenerowaliśmy dla Ciebie hasło **:password**. Ze względów bezpieczeństwa prosimy o jak najszybszą zmianę.',
            'line-3' => 'Możesz uzyskać dostęp do swojego konta za pomocą adresu e-mail **:email** i hasła wymienionego powyżej.',
            'line-4' => 'Jeśli nie utworzyłeś konta, zignoruj tę wiadomość.',
            'subject' => 'EverestServer - Utworzono konto',
        ],
    ];

    /**
     * Create a notification instance.
     */
    public function __construct(private string $email, private string $password)
    {
        $this->placeholdersMap = [
            ':email' => $email,
            ':password' => $password,
        ];
    }

    /**
     * Get the notification's channels.
     */
    public function via(): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->subject($this->lang('subject'))
            ->line($this->lang('line-1'))
            ->line($this->lang('line-2'))
            ->line($this->lang('line-3'))
            ->line($this->lang('line-4'))
            ->action($this->lang('action'), route('login'));
    }
}
