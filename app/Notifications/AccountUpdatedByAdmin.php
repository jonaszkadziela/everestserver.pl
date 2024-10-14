<?php

namespace App\Notifications;

use App\Notifications\Traits\HasTranslations;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountUpdatedByAdmin extends Notification
{
    use HasTranslations;

    protected array $lang = [
        'en' => [
            'action' => 'Log in your account',
            'line-1' => 'Your account has been updated by the administrator.',
            'line-2' => 'A new password has been set for you **:password**. For safety reasons, please change it as soon as possible.',
            'line-3' => 'You can access your account using the **:email** email address, **:username** username and your password.',
            'line-4' => 'If you have any questions regarding this notification, please reach out to the administrator.',
            'subject' => 'EverestServer - Account updated',
        ],
        'pl' => [
            'action' => 'Zaloguj się na swoje konto',
            'line-1' => 'Twoje konto zostało zaktualizowane przez administratora.',
            'line-2' => 'Ustawiono dla Ciebie nowe hasło **:password**. Ze względów bezpieczeństwa zmień je jak najszybciej.',
            'line-3' => 'Dostęp do swojego konta możesz uzyskać za pomocą adresu e-mail **:email**, nazwy użytkownika **:username** i Twojego hasła.',
            'line-4' => 'Jeśli masz jakiekolwiek pytania dotyczące tego powiadomienia, skontaktuj się z administratorem.',
            'subject' => 'EverestServer - Zaktualizowano konto',
        ],
    ];

    /**
     * Create a notification instance.
     */
    public function __construct(private string $username, private string $email, private ?string $password = null)
    {
        $this->placeholdersMap = [
            ':username' => $username,
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
            ->when($this->password !== null, fn (MailMessage $mail) => $mail->line($this->lang('line-2')))
            ->line($this->lang('line-3'))
            ->line($this->lang('line-4'))
            ->action($this->lang('action'), route('login'));
    }
}
