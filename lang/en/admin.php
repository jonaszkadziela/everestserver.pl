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
            'title' => 'Users',
            'description' => 'List all users, edit existing user or add a new one',
            'edit' => 'Edit',
            'no-results' => 'No results found',

            'add-user' => [
                'title' => 'Add user',
                'description' => 'Create a new user by filling in this form',
                'description-2' => 'The user will be notified about their new account and credentials via email',
                'toggles' => 'Toggles',
                'is_admin' => 'Admin',
                'is_enabled' => 'Enabled',
                'is_verified' => 'Verified',
            ],

            'columns' => [
                'id' => '#',
                'username' => 'Username',
                'email' => 'Email address',
                'email_verified_at' => 'Verified at',
                'is_admin' => 'Admin',
                'is_enabled' => 'Enabled',
                'actions' => 'Actions',
            ],
        ],
    ],

];
