<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Admin Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used on admin pages.
    |
    */

    'panel' => [
        'users' => [
            'title' => 'Użytkownicy',
            'description' => 'Wyświetl listę wszystkich użytkowników, edytuj istniejącego użytkownika lub dodaj nowego',
            'edit' => 'Edytuj',
            'no-results' => 'Nie znaleziono żadnych wyników',

            'add-user' => [
                'title' => 'Dodaj użytkownika',
                'description' => 'Utwórz nowego użytkownika wypełniając ten formularz',
                'description-2' => 'Użytkownik zostanie powiadomiony o nowym koncie i danych uwierzytelniających za pośrednictwem poczty e-mail',
                'toggles' => 'Przełączniki',
                'is_admin' => 'Administrator',
                'is_enabled' => 'Włączony',
                'is_verified' => 'Zweryfikowany',
            ],

            'columns' => [
                'id' => '#',
                'username' => 'Nazwa użytkownika',
                'email' => 'Adres e-mail',
                'email_verified_at' => 'Zweryfikowano',
                'is_admin' => 'Administrator',
                'is_enabled' => 'Włączony',
                'actions' => 'Akcje',
            ],
        ],
    ],

];
