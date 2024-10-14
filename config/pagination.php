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
        'users' => env('PAGINATION_ADMIN_USERS', 20),
    ]

];
