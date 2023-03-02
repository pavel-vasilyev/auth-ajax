<?php

return [
    /*'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => PavelVasilyev\AuthAjax\Models\User::class,
        ],
    ],*/

    'login' => [
        'min' => 2,
        'max' => 20
    ],

    'password' => [
        'min' => 6,
        'max' => 60
    ],

    'rate_limiter' => [
        'attempts' => 3,
        'decay' => 60
    ]
];
