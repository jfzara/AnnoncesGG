<?php

return [

    'defaults' => [
        'guard' => env('AUTH_GUARD', 'web'),
        // Changez ceci de 'utilisateurs' à 'users'
        'passwords' => env('AUTH_PASSWORD_BROKER', 'users'),
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            // Changez ceci de 'utilisateurs' à 'users'
            'provider' => 'users',
        ],
    ],

    'providers' => [
        // Changez le nom du fournisseur de 'utilisateurs' à 'users'
        'users' => [
            'driver' => 'eloquent',
            // Changez ceci de 'App\Models\Utilisateur::class' à 'App\Models\User::class'
            'model' => App\Models\User::class,
        ],
    ],

    'passwords' => [
        // Changez ceci de 'utilisateurs' à 'users'
        'users' => [
            // Changez ceci de 'utilisateurs' à 'users'
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
