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
            'description' => 'Wyświetlaj, edytuj i usuwaj istniejących użytkowników lub dodaj nowego',
            'delete' => 'Usuń',
            'edit' => 'Edytuj',
            'no-results' => 'Nie znaleziono żadnych wyników',

            'create-user' => [
                'title' => 'Dodaj użytkownika',
                'description' => 'Utwórz nowego użytkownika wypełniając ten formularz',
                'description-2' => 'Użytkownik zostanie powiadomiony o nowym koncie i danych uwierzytelniających za pośrednictwem poczty e-mail',
                'toggles' => 'Przełączniki',
                'is_admin' => 'Administrator',
                'is_enabled' => 'Włączony',
                'is_verified' => 'Zweryfikowany',
            ],

            'update-user' => [
                'title' => 'Edytuj użytkownika',
                'description' => 'W przypadku zmiany danych uwierzytelniających użytkownik zostanie o tym powiadomiony za pośrednictwem poczty e-mail',
                'toggles' => 'Przełączniki',
                'is_admin' => 'Administrator',
                'is_enabled' => 'Włączony',
                'is_verified' => 'Zweryfikowany',
            ],

            'delete-user' => [
                'title' => 'Usuń użytkownika',
                'description' => 'Czy chcesz trwale usunąć użytkownika',
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
