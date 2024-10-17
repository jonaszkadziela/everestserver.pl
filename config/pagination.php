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
        'linked_services' => env('PAGINATION_ADMIN_LINKED_SERVICES', 20),
        'services' => env('PAGINATION_ADMIN_SERVICES', 20),
        'users' => env('PAGINATION_ADMIN_USERS', 20),
    ],

];
