<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during authentication for various
    | messages that we need to display to the user. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    'failed' => 'Dane uwierzytelniające są nieprawidłowe.',
    'password' => 'Podane hasło jest nieprawidłowe.',
    'throttle' => 'Zbyt wiele prób logowania. Spróbuj ponownie za :seconds sekund.',

    'actions' => [
        'log-out' => 'Wyloguj się',
    ],
    'authorization-request' => [
        'title' => 'Żądanie autoryzacji',
        'description' => '<b>:name</b> prosi o pozwolenie na dostęp do Twojego konta',
        'scopes' => 'Ta aplikacja będzie w stanie',
        'authorize' => 'Upoważnij',
        'cancel' => 'Anuluj',
    ],
    'confirm-password' => [
        'title' => 'Potwierdź dostęp, aby kontynuować',
        'description' => 'To jest zastrzeżony obszar aplikacji. Przed kontynuowaniem potwierdź dostęp swoim hasłem',
        'confirm' => 'Potwierdź',
    ],
    'forgot-password' => [
        'title' => 'Nie pamiętasz hasła?',
        'description' => 'Podaj nam tylko swój adres e-mail, a wyślemy Ci link do resetowania hasła, który umożliwi Ci wybranie nowego',
        'reset-password' => 'Poproś o zresetowanie hasła',
    ],
    'login' => [
        'title' => 'Zaloguj się na swoje konto',
        'remember-me' => 'Zapamiętaj mnie',
        'forgot-password' => 'Nie pamiętasz hasła?',
        'log-in' => 'Zaloguj',
    ],
    'register' => [
        'title' => 'Utwórz nowe konto',
        'already-registered' => 'Posiadasz już konto?',
        'register' => 'Załóż konto',
    ],
    'reset-password' => [
        'title' => 'Zresetuj swoje hasło',
        'save' => 'Zapisz',
    ],
    'verify-email' => [
        'title' => 'Zweryfikuj swój adres e-mail',
        'description' => 'Dziękujemy za zarejestrowanie się! Zanim zaczniesz korzystać z aplikacji, czy możesz zweryfikować swój adres e-mail, klikając w link, który wysłaliśmy do Ciebie e-mailem?',
        'description-2' => 'Jeśli nie dotarł do Ciebie e-mail, z przyjemnością wyślemy Ci link ponownie',
        'resend-verification' => 'Wyślij ponownie e-mail weryfikacyjny',
        'verification-link-sent' => 'Na adres e-mail podany podczas rejestracji został wysłany nowy link weryfikacyjny',
    ],

];
