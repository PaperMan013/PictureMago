<?php

use app\processors\Resize;
use app\processors\Square;

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
                [
                    'class' => Square::class,
                    'id' => 'square'
                ],
            ],
            'versions' => [
                'preview' => ['side500', 'square'],
            ],
        ]
    ]
];
