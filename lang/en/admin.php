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
        'services' => [
            'title' => 'Services',
            'description' => 'List, edit and delete existing services or add a new one',
            'delete' => 'Delete',
            'edit' => 'Edit',
            'no-results' => 'No services found',

            'create-service' => [
                'title' => 'Add service',
                'description' => 'Create a new service by filling in this form',
                'toggles' => 'Toggles',
                'is_public' => 'Public',
                'is_enabled' => 'Enabled',
            ],

            'update-service' => [
                'title' => 'Edit service',
                'description' => 'Update an existing service by filling in this form',
                'toggles' => 'Toggles',
                'is_public' => 'Public',
                'is_enabled' => 'Enabled',
            ],

            'delete-service' => [
                'title' => 'Delete service',
                'description' => 'Do you want to permanently delete service',
            ],

            'columns' => [
                'id' => '#',
                'name' => 'Name',
                'description' => 'Description',
                'icon' => 'Icon',
                'link' => 'Link',
                'is_public' => 'Public',
                'is_enabled' => 'Enabled',
                'actions' => 'Actions',
            ],
        ],

        'users' => [
            'title' => 'Users',
            'description' => 'List, edit and delete existing users or add a new one',
            'delete' => 'Delete',
            'edit' => 'Edit',
            'no-results' => 'No users found',

            'create-user' => [
                'title' => 'Add user',
                'description' => 'Create a new user by filling in this form',
                'description-2' => 'The user will be notified about their new account and credentials via email',
                'toggles' => 'Toggles',
                'is_admin' => 'Admin',
                'is_enabled' => 'Enabled',
                'is_verified' => 'Verified',
            ],

            'update-user' => [
                'title' => 'Edit user',
                'description' => 'If credentials are changed, the user will be notified about it via email',
                'toggles' => 'Toggles',
                'is_admin' => 'Admin',
                'is_enabled' => 'Enabled',
                'is_verified' => 'Verified',
            ],

            'delete-user' => [
                'title' => 'Delete user',
                'description' => 'Do you want to permanently delete user',
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
