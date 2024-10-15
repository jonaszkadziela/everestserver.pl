<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pagination Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the maximum number of results per page.
    |
    */

    'admin' => [
        'services' => env('PAGINATION_ADMIN_SERVICES', 20),
        'users' => env('PAGINATION_ADMIN_USERS', 20),
    ]

];
