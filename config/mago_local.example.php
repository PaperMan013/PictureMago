<?php

use app\processors\Resize;

return [
    'components' => [
        'mago' => [
            'token' => 'secret',
            'processors' => [
                [
                    'class' => Resize::class,
                    'id' => 'side500',
                    'maxSide' => 500,
                ],
            ],
            'versions' => [
                'preview' => ['side500'],
            ],
        ]
    ]
];
