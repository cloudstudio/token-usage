<?php

return [
    'model_mappings' => [
        'user' => \App\Models\User::class,
    ],
    'plans' => [
        'basic' => [
            'model_limits' => [
                'user' => [
                    'daily' => 1,
                    'weekly' => 5,
                    'monthly' => 10,
                    'yearly' => 100,
                ],
            ],
        ],
        'premium' => [
            // ...
        ],
    ],
];
