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

    'failed' => 'These credentials do not match our records.',
    'password' => 'The provided password is incorrect.',
    'throttle' => 'Too many login attempts. Please try again in :seconds seconds.',

    'actions' => [
        'log-out' => 'Log out',
    ],
    'authorization-request' => [
        'title' => 'Authorization request',
        'description' => '<b>:name</b> is requesting permission to access your account',
        'scopes' => 'This application will be able to',
        'authorize' => 'Authorize',
        'cancel' => 'Cancel',
    ],
    'confirm-password' => [
        'title' => 'Confirm access to continue',
        'description' => 'This is a secure area of the application. Please confirm access with your password before continuing',
        'confirm' => 'Confirm',
    ],
    'forgot-password' => [
        'title' => 'Forgot your password?',
        'description' => 'Just let us know your email address and we will email you a password reset link that will allow you to choose a new one',
        'reset-password' => 'Request password reset',
    ],
    'login' => [
        'title' => 'Log in to your account',
        'remember-me' => 'Remember me',
        'forgot-password' => 'Forgot your password?',
        'log-in' => 'Log in',
    ],
    'register' => [
        'title' => 'Create a new account',
        'already-registered' => 'Already registered?',
        'register' => 'Register',
    ],
    'reset-password' => [
        'title' => 'Reset your password',
        'save' => 'Save',
    ],
    'scopes' => [
        'email' => 'View your email address',
        'openid' => 'Connect OpenID applications to your account',
        'profile' => 'View information about your profile, such as username, preferred locale and more',
        'user' => 'View your account information, such as email address, username, role and more',
    ],
    'verify-email' => [
        'title' => 'Verify your email address',
        'description' => 'Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?',
        'description-2' => 'If you didn\'t receive the email, we will gladly send you another',
        'resend-verification' => 'Resend verification email',
        'verification-link-sent' => 'A new verification link has been sent to the email address you provided during registration',
    ],

];
