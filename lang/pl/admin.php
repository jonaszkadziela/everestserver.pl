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
        'delete' => 'Usuń',
        'edit' => 'Edytuj',
        'search' => 'Wyszukaj',
        'unlink' => 'Odłącz',

        'linked-services' => [
            'title' => 'Powiązane usługi',
            'description' => 'Wyświetlaj powiązane usługi i zarządzaj połączeniami',
            'no-results' => 'Nie znaleziono powiązanych usług',

            'link-service' => [
                'title' => 'Powiąż usługę',
                'description' => 'Połącz usługę z użytkownikiem, wypełniając ten formularz',
                'service_id' => 'Usługa',
                'user_id' => 'Użytkownik',
                'identifier' => 'Identyfikator',
            ],

            'unlink-service' => [
                'title' => 'Odłącz usługę',
                'description' => 'Czy chcesz odłączyć usługę',
                'description-2' => 'od użytkownika'
            ],

            'columns' => [
                'service' => 'Usługa',
                'user' => 'Użytkownik',
                'identifier' => 'Identyfikator',
                'actions' => 'Akcje',
            ],
        ],

        'services' => [
            'title' => 'Usługi',
            'description' => 'Wyświetlaj, edytuj i usuwaj istniejące usługi lub dodaj nową',
            'no-results' => 'Nie znaleziono żadnych usług',

            'create-service' => [
                'title' => 'Dodaj usługę',
                'description' => 'Utwórz nową usługę, wypełniając ten formularz',
                'toggles' => 'Przełączniki',
                'is_public' => 'Publiczna',
                'is_enabled' => 'Włączona',
            ],

            'update-service' => [
                'title' => 'Edytuj usługę',
                'description' => 'Zaktualizuj istniejącą usługę, wypełniając ten formularz',
                'toggles' => 'Przełączniki',
                'is_public' => 'Publiczna',
                'is_enabled' => 'Włączona',
            ],

            'delete-service' => [
                'title' => 'Usuń usługę',
                'description' => 'Czy chcesz trwale usunąć usługę',
            ],

            'columns' => [
                'id' => '#',
                'name' => 'Nazwa',
                'description' => 'Opis',
                'icon' => 'Ikona',
                'link' => 'Link',
                'is_public' => 'Publiczna',
                'is_enabled' => 'Włączona',
                'actions' => 'Akcje',
            ],
        ],

        'users' => [
            'title' => 'Użytkownicy',
            'description' => 'Wyświetlaj, edytuj i usuwaj istniejących użytkowników lub dodaj nowego',
            'no-results' => 'Nie znaleziono żadnych użytkowników',

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
